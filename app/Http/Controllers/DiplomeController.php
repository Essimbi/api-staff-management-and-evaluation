<?php

namespace App\Http\Controllers;

use App\Models\diplome;
use Illuminate\Http\Request;

class DiplomeController extends Controller
{
    public function getAllDiplome()
    {
        try {
            $diplomes = diplome::all();
            if (count($diplomes) > 0) {
                return response()->json([
                    "status" => "200",
                    "datas" => $diplomes
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

    public function getOneDiplome($id)
    {
        try {
            $diplome = diplome::find($id);
            if ($diplome) {
                return response()->json([
                    "status" => "200",
                    "datas" => $diplome
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
            "libelle" => "nullable",
            "date_optention" => "nullable",
            "domaine" => "nullable",
            "etablissement" => "nullable",
            "option" => "nullable",
            "ville" => "nullable",
            "pays" => "nullable",
            "statut" => "nullable",
            "personnel" => "nullable"
        ]);


        try {

            $diplome = diplome::create([
                "libelle" => $request->libelle,
                "date_optention" => $request->date_optention,
                "domaine" => $request->domaine,
                "etablissement" => $request->etablissement,
                "option" => $request->option,
                "id_ville" => $request->ville,
                "pays" => $request->pays,
                "statut" => $request->statut,
                "id_personnel" => $request->personnel
            ]);
            return response()->json([
                "status" => "200",
                "datas" => $diplome
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
            "libelle" => "nullable",
            "date_optention" => "nullable",
            "domaine" => "nullable",
            "etablissement" => "nullable",
            "option" => "nullable",
            "ville" => "nullable",
            "pays" => "nullable",
            "statut" => "nullable",
            "personnel" => "nullable"
        ]);


        try {

            $diplome = diplome::find($id);

            if ($diplome) {
                $diplome->update([
                    "libelle" => $request->libelle,
                    "date_optention" => $request->date_optention,
                    "domaine" => $request->domaine,
                    "etablissement" => $request->etablissement,
                    "option" => $request->option,
                    "id_ville" => $request->ville,
                    "pays" => $request->pays,
                    "statut" => $request->statut,
                    "id_personnel" => $request->personnel
                ]);
                return response()->json([
                    "status" => "200",
                    "datas" => $diplome
                ]);
            } else {
                return response()->json([
                    "status" => "200",
                    "message" => "Diplome non existant"
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
            $diplome = diplome::find($id);

            if ($diplome) {

                $diplome->delete();

                return response()->json([
                    'status' => '200',
                    'message' => "Deletd successfully"
                ]);
            } else {
                return response()->json([
                    'status' => '200',
                    'message' => "Diplome non existant"
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
