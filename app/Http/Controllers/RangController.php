<?php

namespace App\Http\Controllers;

use App\Models\rang;
use Illuminate\Http\Request;

class RangController extends Controller
{
    public function getAll()
    {
        try {
            $rangs = rang::all();

            if (count($rangs) > 0) {
                return response()->json([
                    "status" => "200",
                    "datas" => $rangs
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
            $rang = rang::create(["intitule" => $request->nom]);
            return response()->json([
                "status" => "201",
                "datas" => $rang
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
            $rang = rang::find($id);

            if ($rang) {
                return response()->json([
                    "status" => "200",
                    "datas" => $rang
                ]);
            } else {
                return response()->json([
                    "status" => "200",
                    "message" => "Rang pas disponible"
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
            $rang = rang::find($id);

            if ($rang) {
                $rang->update(["intitule" => $request->nom]);
                return response()->json([
                    "status" => "200",
                    "message" => "Updated successfully",
                    "datas" => $rang
                ]);
            } else {
                return response()->json([
                    "status" => "200",
                    "message" => "Rang pas disponible"
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
            $rang = rang::find($id);

            if ($rang) {

                $message = "Rang " . $rang->intitule . " supprimé avec success";
                $rang->delete();

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
