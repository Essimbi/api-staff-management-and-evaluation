<?php

namespace App\Http\Controllers;

use App\Models\struct_gene;
use App\Models\structure_gene;
use Illuminate\Http\Request;

class StructGeneController extends Controller
{
    public function getAll()
    {
        try {
            $struct = structure_gene::all();

            if (count($struct) > 0) {
                return response()->json([
                    "status" => "200",
                    "datas" => $struct
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
            $struct = structure_gene::create(["nom" => $request->nom]);
            return response()->json([
                "status" => "201",
                "datas" => $struct
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
            $struct = structure_gene::find($id);

            if ($struct) {
                return response()->json([
                    "status" => "200",
                    "datas" => $struct
                ]);
            } else {
                return response()->json([
                    "status" => "200",
                    "message" => "Structure générale pas disponible"
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
            $struct = structure_gene::find($id);

            if ($struct) {
                $struct->update(["nom" => $request->nom]);
                return response()->json([
                    "status" => "200",
                    "message" => "Updated successfully",
                    "datas" => $struct
                ]);
            } else {
                return response()->json([
                    "status" => "200",
                    "message" => "Structure générale pas disponible"
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
            $struct = structure_gene::find($id);

            if ($struct) {

                $message = "Structure générale " . $struct->nom . " supprimée avec success";
                $struct->delete();

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
