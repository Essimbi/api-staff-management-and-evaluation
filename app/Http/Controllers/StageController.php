<?php

namespace App\Http\Controllers;

use App\Models\stage;
use Illuminate\Http\Request;

class StageController extends Controller
{
    // Tous les stages ou séminaires
    public function getAllStage()
    {
        try {
            $stages = stage::all();

            if (count($stages) > 0) {
                return response()->json([
                    "status" => "200",
                    "datas" => $stages
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

    public function getOneStage($id)
    {
        try {
            $stage = stage::find($id);

            if ($stage) {
                return response()->json([
                    "status" => "200",
                    "datas" => $stage
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
        $request->validate([
            "theme" => "nullable",
            "domaine" => "nullable",
            "institut" => "nullable",
            "nbreJour" => "nullable|numeric",
            "localisation" => "nullable"
        ]);

        try {
            $stage = stage::create([
                "theme_stage" => $request->theme,
                "domaine" => $request->domaine,
                "institut" => $request->institut,
                "nbre_jour" => $request->nbreJour,
                "localisation" => $request->localisation
            ]);
            return response()->json([
                "status" => "201",
                "datas" => $stage
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
            "theme" => "nullable",
            "domaine" => "nullable",
            "institut" => "nullable",
            "nbre_jour" => "nullable|numeric",
            "localisation" => "nullable"
        ]);

        try {
            $stage = stage::find($id);

            if ($stage) {
                $stage->update([
                    "theme_stage" => $request->theme,
                    "domaine" => $request->domaine,
                    "institut" => $request->institut,
                    "nbre_jour" => $request->nbre_jour,
                    "localisation" => $request->localisation
                ]);
                return response()->json([
                    "status" => "200",
                    "message" => "Updated successfully",
                    "datas" => $stage
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

    public function delete($id)
    {
        try {
            $stage = stage::find($id);

            if ($stage) {
                $message = "Stage/Séminaire " . $stage->theme_stage . " supprimé avec success";
                $stage->delete();
                return response()->json([
                    "status" => "200",
                    "message" => $message
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
}
