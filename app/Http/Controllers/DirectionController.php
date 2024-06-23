<?php

namespace App\Http\Controllers;

use App\Models\direction;
use Illuminate\Http\Request;

class DirectionController extends Controller
{
    public function getAll()
    {
        try {
            $directions = direction::all();

            if (count($directions) > 0) {
                return response()->json([
                    "status" => "200",
                    "datas" => $directions
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
            $direction = direction::create(["nom" => $request->nom]);
            return response()->json([
                "status" => "201",
                "datas" => $direction
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
            $direction = direction::find($id);

            if ($direction) {
                return response()->json([
                    "status" => "200",
                    "datas" => $direction
                ]);
            } else {
                return response()->json([
                    "status" => "200",
                    "message" => "Direction pas disponible"
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
            $direction = direction::find($id);

            if ($direction) {
                $direction->update(["nom" => $request->nom]);
                return response()->json([
                    "status" => "200",
                    "message" => "Updated successfully",
                    "datas" => $direction
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
            $direction = direction::find($id);

            if ($direction) {

                $message = "Catégorie " . $direction->nom . " supprimée avec success";
                $direction->delete();

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
