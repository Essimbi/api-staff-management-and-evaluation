<?php

namespace App\Http\Controllers;

use App\Models\typeStructure;   
use Illuminate\Http\Request;

class TypeStructController extends Controller
{
    public function getAll()
    {
        try {
            $type_struct = typeStructure::all();

            if (count($type_struct) > 0) {
                return response()->json([
                    "status" => "200",
                    "datas" => $type_struct
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

    // Somme type structure
    public function sommeTypeStructure()
    {
        $type_struct = typeStructure::all();
        $somme = count($type_struct);

        return response()->json([
            "status" => "200",
            "datas" => $somme
        ]);
    }

    public function store(Request $request)
    {
        $request->validate(["nom" => "required"]);
        try {
            $type_struct = typeStructure::create(["nom" => $request->nom]);
            return response()->json([
                "status" => "201",
                "datas" => $type_struct
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
            $type_struct = typeStructure::find($id);

            if ($type_struct) {
                return response()->json([
                    "status" => "200",
                    "datas" => $type_struct
                ]);
            } else {
                return response()->json([
                    "status" => "200",
                    "message" => "Type de structure pas disponible"
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
            $type_struct = typeStructure::find($id);

            if ($type_struct) {
                $type_struct->update(["nom" => $request->nom]);
                return response()->json([
                    "status" => "200",
                    "message" => "Updated successfully",
                    "datas" => $type_struct
                ]);
            } else {
                return response()->json([
                    "status" => "200",
                    "message" => "Type de structure pas disponible"
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
            $type_struct = typeStructure::find($id);

            if ($type_struct) {

                $message = "Type de structure " . $type_struct->nom . " supprimé avec success";
                $type_struct->delete();

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
