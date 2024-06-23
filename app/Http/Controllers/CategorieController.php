<?php

namespace App\Http\Controllers;

use App\Models\categorie;
use Illuminate\Http\Request;

class CategorieController extends Controller
{
    public function getAll()
    {
        try {
            $categires = categorie::all();

            if (count($categires) > 0) {
                return response()->json([
                    "status" => "200",
                    "datas" => $categires
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
            $categire = categorie::create(["intitule" => $request->nom]);
            return response()->json([
                "status" => "201",
                "datas" => $categire
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
            $categire = categorie::find($id);

            if ($categire) {
                return response()->json([
                    "status" => "200",
                    "datas" => $categire
                ]);
            } else {
                return response()->json([
                    "status" => "200",
                    "message" => "Catégorie pas disponible"
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
            $categire = categorie::find($id);

            if ($categire) {
                $categire->update(["intitule" => $request->nom]);
                return response()->json([
                    "status" => "200",
                    "message" => "Updated successfully",
                    "datas" => $categire
                ]);
            } else {
                return response()->json([
                    "status" => "200",
                    "message" => "Catégorie pas disponible"
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
            $categire = categorie::find($id);

            if ($categire) {

                $message = "Catégorie " . $categire->intitule . " supprimée avec success";
                $categire->delete();

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
