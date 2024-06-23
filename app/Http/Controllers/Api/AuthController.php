<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\compte;
use App\Models\personnel;
use App\Models\role;
//use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;
use stdClass;

/**
 * @group Authentification
 **/
class AuthController extends Controller
{
    public function _construct()
    {
        return $this->middleware("api:auth", [
            "except" => [
                "login",
                "register"
            ]
        ]);
    }

    public function register(Request $request)
    {
        $request->validate([
            "name" => "required",
            "email" => "required|email",
            "password" => "required",
            "role" => "required",
            "personnel" => "nullable"
        ]);

        if ($user = DB::table('comptes')->where('email', '=', $request->email)->first()) {
            return response()->json([
                'message' => 'Cette adresse mail est déjà utilisé '
            ], 400);
        } else {
            $personnel = personnel::find($request->personnel);
            if ($personnel) {

                $pwd = Hash::make($request->password);

                $user = compte::create([
                    'name' => $request->name,
                    'email' => $request->email,
                    'role' => strtoupper($request->role),
                    'password' => $pwd,
                    'id_personnel' => $request->personnel,
                    'firstLog' => true
                ]);


                $token = Auth::login($user);

                $personnel = personnel::find($user->id_personnel);

                $result = new stdClass;
                $result->compte = $user;
                $result->personnel = $personnel;
                $result->role = $user->role;

                return response()->json([
                    'message' => 'Enrégistrement réussie',
                    'user' => $result,
                    'token' => $token
                ], 201);
            } else {
                return response()->json([
                    'message' => 'Personnel inexistant'
                ], 200);
            }
        }
    }

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required',
            'password' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        // $user = compte::where('email', $request->email)->first();


        // $user = DB::table('comptes')->select('email', $request->email)
        // ->join() ;

        $user = compte::join('personnels as p', 'comptes.id_personnel', '=', 'p.id')
            ->where('comptes.email', '=', $request->email)
            ->orWhere('p.matricule_perso', '=', $request->email)
            ->first();

        // return response()->json([
        //     'message' => $user,
        // ], 401);

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                'message' => 'Identifiants invalides',
            ], 401);
        }

        $token = auth()->login($user);

        if ($token) {
            $personnel = personnel::find($user->id_personnel);

            // return response()->json([
            //     'message' => $user,
            // ], 401);
            // $role = role::where($user->roles->first()->id);
            $result = new stdClass;
            $result->compte = $user;
            $result->personnel = $personnel;
            $result->role = $user->role;
            return response()->json([
                'message' => 'Login réussie',
                'datas' => $result,
                'token' => $token
            ], 200);
        }
        return response()->json([
            'status' => '403',
            'message' => "Votre token a expiré"
        ], 403);
    }

    public function update(Request $request, $id)
    {

        $request->validate([
            "name" => "nullable",
            "email" => "required",
            "password" => "nullable",
            "role" => "nullable",
            "personnel" => "nullable"
        ]);

        $user = compte::find($id);
        if ($user) {
            if (DB::table('comptes')->where('email', '=', $request->email)->first()) {
                return response()->json([
                    'message' => 'Cette adresse mail est déjà utilisé '
                ], 200);
            } else {
                $pwd = Hash::make($request->password);
                if (personnel::find($request->personnel)) {

                    $user->update([
                        'name' => $request->name,
                        'email' => $request->email,
                        'role' => strtoupper($request->role),
                        'password' => $pwd,
                        'id_personnel' => $request->personnel
                    ]);

                    $token = Auth::login($user);

                    return response()->json([
                        'message' => 'Mise à jour réussie',
                        'user' => $user,
                        'token' => $token
                    ], 200);
                }
            }
        } else {
            return response()->json([
                'status' => '200',
                'message' => 'Compte non trouvé'
            ], 200);
        }
    }

    public function changePassword(Request $request)
    {

        $request->validate([
            "old" => "required",
            "new" => "required",
        ]);

        $user = compte::join('personnels as p', 'comptes.id_personnel', '=', 'p.id')
            ->where('p.id', '=', $request->user_id)
            ->select('comptes.*')
            ->first();
        $curentUser = compte::find($user->id);
        if ($curentUser) {
            if (!$curentUser || !Hash::check($request->old, $curentUser->password)) {
                return response()->json([
                    'message' => 'Identifiants invalides',
                ], 401);
            }
            //Change password here.
            $password = $request->new;
            $pwd = Hash::make($password);
            $curentUser->update([
                'password' => $pwd,
                'firstLog' => false
            ]);

            $token = Auth::login($curentUser);

            $user = compte::join('personnels as p', 'comptes.id_personnel', '=', 'p.id')
                ->where('comptes.id', '=', $curentUser->id)
                ->first();

            $personnel = personnel::find($user->id_personnel);

            // return response()->json([
            //     'message' => $user,
            // ], 401);
            // $role = role::where($user->roles->first()->id);
            $result = new stdClass;
            $result->compte = $user;
            $result->personnel = $personnel;
            $result->role = $user->role;
            return response()->json([
                'message' => 'Mot de passe changé avec succès',
                'datas' => $result,
                'token' => $token
            ], 200);
        } else {
            return response()->json([
                'status' => '200',
                'message' => 'Compte non trouvé'
            ], 200);
        }
    }

    public function resetPassword(Request $request, $id)
    {

        $user = compte::find($id);
        if ($user) {
            $password = "000000";
            $pwd = Hash::make($password);
            $user->update([
                'password' => $pwd,
                'firstLog' => true
            ]);

            $token = Auth::login($user);

            return response()->json([
                'message' => 'Mise à jour réussie',
                'user' => $user,
                'token' => $token
            ], 200);
        } else {
            return response()->json([
                'status' => '200',
                'message' => 'Compte non trouvé'
            ], 200);
        }
    }

    public function delete(Request $request, $id)
    {
        $user = compte::find($id);
        if ($user) {

            $message = "Utilisateur " . $user->name . " supprimé avec success";
            // $roles = DB::table('compte_role')->where('compte_id', '=', $user->id)->delete();
            $user->delete();

            return response()->json([
                'status' => '200',
                'message' => $message
            ]);
        } else {
            return response()->json([
                'status' => '200',
                'message' => 'Not found'
            ]);
        }
    }

    public function getAllUsers()
    {
        try {
            $users = compte::all();

            if (count($users) > 0) {
                $result = array();
                foreach ($users as $compte) {
                    $personnel = personnel::find($compte->id_personnel);

                    $newCompte = new stdClass;

                    $newCompte->id = $compte->id;
                    $newCompte->name = $compte->name;
                    $newCompte->email = $compte->email;
                    $newCompte->role = $compte->role;
                    $newCompte->personnel = $personnel;

                    array_push($result, $newCompte);
                }
                return response()->json([
                    "status" => "200",
                    "datas" => $result
                ]);
            } else {
                return response()->json([
                    "status" => "200",
                    "message" => "Data not found"
                ]);
            }
        } catch (\Throwable $th) {
            return response()->json([
                "status" => "500",
                "message" => $th
            ]);
        }
    }

    public function getOneUser(Request $request, $id)
    {
        try {
            $user = compte::find($id);

            if ($user) {
                $personnel = personnel::find($user->id_personnel);

                $newCompte = new stdClass;

                $newCompte->name = $user->name;
                $newCompte->email = $user->email;
                $newCompte->role = $user->roles()->get();
                $newCompte->personnel = $personnel;
                return response()->json([
                    "status" => "200",
                    "datas" => $newCompte
                ]);
            } else {
                return response()->json([
                    "status" => "200",
                    "message" => " Not found"
                ]);
            }
        } catch (\Throwable $th) {
            return response()->json([
                "status" => "500",
                "message" => $th
            ]);
        }
    }
}
