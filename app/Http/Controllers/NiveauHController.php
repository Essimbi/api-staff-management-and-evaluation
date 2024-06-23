<?php

namespace App\Http\Controllers;

use App\Models\niveau_hierarchique;
use Illuminate\Http\Request;

class NiveauHController extends Controller
{
    public function getAll()
    {
        try {
            $nh = niveau_hierarchique::all();

            if (count($nh) > 0) {
                return response()->json([
                    "status" => "200",
                    "datas" => $nh
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

    public function store(Request $request)
    {
        $request->validate(["nom" => "required"]);
        try {
            $nh = niveau_hierarchique::create(["intitule" => $request->nom]);
            return response()->json([
                "status" => "201",
                "datas" => $nh
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                "status" => "500",
                "message" => $th
            ]);
        }
    }

    public function getOne(Request $request, $id)
    {
        try {
            $nh = niveau_hierarchique::find($id);

            if ($nh) {
                return response()->json([
                    "status" => "200",
                    "datas" => $nh
                ]);
            } else {
                return response()->json([
                    "status" => "200",
                    "message" => "Niveau hierarchique pas disponible"
                ]);
            }
        } catch (\Throwable $th) {
            return response()->json([
                "status" => "500",
                "message" => $th
            ]);
        }
    }

    public function update(Request $request, $id)
    {
        $request->validate(["nom" => "required"]);
        try {
            $nh = niveau_hierarchique::find($id);

            if ($nh) {
                $nh->update(["intitule" => $request->nom]);
                return response()->json([
                    "status" => "200",
                    "message" => "Updated successfully",
                    "datas" => $nh
                ]);
            } else {
                return response()->json([
                    "status" => "200",
                    "message" => "Niveau hierarchique pas disponible"
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
            $nh = niveau_hierarchique::find($id);

            if ($nh) {

                $message = "Niveau hierarchique " . $nh->intitule . " supprimé avec success";
                $nh->delete();

                return response()->json([
                    'status' => '200',
                    'message' => $message
                ]);
            } else {
                return response()->json([
                    'status' => '200',
                    'message' => 'Aucune ressource disponible'
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
