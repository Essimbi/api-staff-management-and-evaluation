<?php

namespace App\Http\Controllers;

use App\Models\corps;
use Illuminate\Http\Request;

class CorpsController extends Controller
{
    public function getAll()
    {
        try {
            $corps = corps::all();

            if (count($corps) > 0) {
                return response()->json([
                    "status" => "200",
                    "datas" => $corps
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
            $corps = corps::create(["intitule" => $request->nom]);
            return response()->json([
                "status" => "201",
                "datas" => $corps
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
            $corps = corps::find($id);

            if ($corps) {
                return response()->json([
                    "status" => "200",
                    "datas" => $corps
                ]);
            } else {
                return response()->json([
                    "status" => "200",
                    "message" => "Corps pas disponible"
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
            $corps = corps::find($id);

            if ($corps) {
                $corps->update(["intitule" => $request->nom]);
                return response()->json([
                    "status" => "200",
                    "message" => "Updated successfully",
                    "datas" => $corps
                ]);
            } else {
                return response()->json([
                    "status" => "200",
                    "message" => "Corps pas disponible"
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
            $corps = corps::find($id);

            if ($corps) {

                $message = "Coprs " . $corps->intitule . " supprimé avec success";
                $corps->delete();

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
