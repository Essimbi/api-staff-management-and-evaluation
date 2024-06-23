<?php

namespace App\Http\Controllers;

use App\Models\grade;
use Illuminate\Http\Request;

class GradeController extends Controller
{
    public function getAll()
    {
        try {
            $grades = grade::all();

            if (count($grades) > 0) {
                return response()->json([
                    "status" => "200",
                    "datas" => $grades
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
            $grade = grade::create(["intitule" => $request->nom]);
            return response()->json([
                "status" => "201",
                "datas" => $grade
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
            $grade = grade::find($id);

            if ($grade) {
                return response()->json([
                    "status" => "200",
                    "datas" => $grade
                ]);
            } else {
                return response()->json([
                    "status" => "200",
                    "message" => "Grade pas disponible"
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
            $grade = grade::find($id);

            if ($grade) {
                $grade->update(["intitule" => $request->nom]);
                return response()->json([
                    "status" => "200",
                    "message" => "Updated successfully",
                    "datas" => $grade
                ]);
            } else {
                return response()->json([
                    "status" => "200",
                    "message" => "Grade pas disponible"
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
            $grade = grade::find($id);

            if ($grade) {

                $message = "Grade " . $grade->intitule . " supprimé avec success";
                $grade->delete();

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
