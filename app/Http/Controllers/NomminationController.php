<?php

namespace App\Http\Controllers;

use App\Models\niveau_hierarchique;
use App\Models\nommination;
use App\Models\personnel;
use App\Models\rang;
use Illuminate\Http\Request;
use stdClass;

class NomminationController extends Controller
{
    public function getAllNommination()
    {
        try {
            $nomminations = nommination::all();
            $result = array();
            if (count($nomminations) > 0) {
                foreach ($nomminations as $nommination) {
                    $personnel = personnel::find($nommination->id_personnel);
                    $rang = rang::find($nommination->id_rang);
                    $nh = niveau_hierarchique::find($nommination->id_nh);
                    $newNom = new stdClass;

                    $newNom->id = $nommination->id;
                    $newNom->rang = $rang;
                    $newNom->niveau_hierarchique = $nh;
                    $newNom->fonction = $nommination->fonction;
                    $newNom->ref_acte = $nommination->ref_acte;
                    $newNom->date_nommination = $nommination->date_nommination;
                    $newNom->personnel = $personnel;

                    array_push($result, $newNom);
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
                "message" => "Internal server error"
            ]);
        }
    }

    public function getOneNommination($id)
    {
        try {
            $nommination = nommination::find($id);
            if ($nommination) {
                $personnel = personnel::find($nommination->id_personnel);
                $rang = rang::find($nommination->id_rang);
                $nh = niveau_hierarchique::find($nommination->id_nh);
                $newNom = new stdClass;

                $newNom->id = $nommination->id;
                $newNom->rang = $rang;
                $newNom->niveau_hierarchique = $nh;
                $newNom->fonction = $nommination->fonction;
                $newNom->ref_acte = $nommination->ref_acte;
                $newNom->date_nommination = $nommination->date_nommination;
                $newNom->personnel = $personnel;
                return response()->json([
                    "status" => "200",
                    "datas" => $newNom
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
                "message" => "Internal server error"
            ]);
        }
    }

    public function store(Request $request)
    {
        $request->validate([
            "rang" => "required",
            "niveau_hierarchique" => "required",
            "fonction" => "required",
            "ref_acte" => "required",
            "date_nommination" => "required",
            "personnel" => "required"
        ]);


        try {
            $nommination = nommination::create([
                "id_rang" => $request->rang,
                "id_nh" => $request->niveau_hierarchique,
                "fonction" => $request->fonction,
                "ref_acte" => $request->ref_acte,
                "date_nommination" => $request->date_nommination,
                "id_personnel" => $request->personnel
            ]);
            return response()->json([
                "status" => "201",
                "datas" => $nommination
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                "status" => "500",
                "message" => $th
            ]);
        }
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            "rang" => "required",
            "niveau_hierarchique" => "required",
            "fonction" => "required",
            "ref_acte" => "required",
            "date_nommination" => "required",
            "personnel" => "required"
        ]);

        try {

            $nommination = nommination::find($id);

            if ($nommination) {

                $nommination->update([
                    "id_rang" => $request->rang,
                    "id_nh" => $request->niveau_hierarchique,
                    "fonction" => $request->fonction,
                    "ref_acte" => $request->ref_acte,
                    "date_nommination" => $request->date_nommination,
                    "personnel" => $request->personnel
                ]);
                return response()->json([
                    "status" => "201",
                    "datas" => $nommination
                ]);
            } else {
                return response()->json([
                    "status" => "200",
                    "message" => "Nommination non existante"
                ]);
            }
        } catch (\Throwable $th) {
            return response()->json([
                "status" => "500",
                "message" => $th
            ]);
        }
    }

    public function delete($id)
    {
        try {
            $nommination = nommination::find($id);

            if ($nommination) {

                $nommination->delete();

                return response()->json([
                    'status' => '200',
                    'message' => "Deletd successfully"
                ]);
            } else {
                return response()->json([
                    'status' => '200',
                    'message' => "Nommination non existante"
                ]);
            }
        } catch (\Throwable $th) {
            return response()->json([
                'status' => '500',
                'message' => $th
            ]);
        }
    }
}
