<?php

namespace App\Http\Controllers;

use App\Models\arrondissement;
use App\Models\departement;
use Illuminate\Http\Request;
use stdClass;

class ArrondController extends Controller
{
    /**
     * Retourne tous les arrondissements
     *
     * @retrun result
     **/
    // Tous les arrondissements
    public function getAllArrond()
    {
        try {
            $arronds = arrondissement::all();
            $result = array();
            if (count($arronds) > 0) {
                foreach ($arronds as $arrond) {
                    $dep = departement::find($arrond->id_depart);
                    $newArrond = new stdClass;

                    $newArrond->id = $arrond->id;
                    $newArrond->nom = $arrond->nom_arrond;
                    $newArrond->departement = $dep;

                    array_push($result, $newArrond);
                }
                return response()->json([
                    "status" => "200",
                    "datas" => $result
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

    // Un arrondissement en particuler
    public function getOneArrond($id)
    {
        try {
            $arrond = arrondissement::find($id);

            if ($arrond) {
                $dep = departement::find($arrond->id_depart);
                $newArrond = new stdClass;

                $newArrond->id = $arrond->id;
                $newArrond->nom = $arrond->nom_arrond;
                $newArrond->departement = $dep;
                return response()->json([
                    "status" => "200",
                    "datas" => $newArrond
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

    // Enrégister un arrondissement
    public function store(Request $request)
    {
        $request->validate([
            "nom",
            "departement"
        ]);

        try {
            $id = $request->departement;

            $departement = departement::find($id);

            if ($departement) {
                $arrond = arrondissement::create([
                    "nom" => $request->nom,
                    "id_depart" => $request->departement
                ]);
                return response()->json([
                    "status" => "201",
                    "message" => "Created successfully",
                    "Departement" => $arrond
                ]);
            } else {
                return response()->json([
                    "status" => "404",
                    "message" => "Département pas disponible"
                ]);
            }
        } catch (\Throwable $th) {
            return response()->json([
                "status" => "500",
                "message" => $th
            ]);
        }
    }

    // Mise à jour d'un arrondissement
    public function update(Request $request, $id)
    {
        $request->validate([
            "nom" => "required",
            "departement" => "required"
        ]);

        try {

            $id_depart = $request->departement;

            $arrond = arrondissement::find($id);

            if ($arrond) {

                $departement = departement::find($id_depart);

                if ($departement) {
                    $arrond->update([
                        "nom" => $request->nom,
                        "id_depart" => $request->departement
                    ]);
                    return response()->json([
                        "status" => "200",
                        "message" => "Updated successfully",
                        "datas" => $arrond
                    ]);
                } else {
                    return response()->json([
                        "status" => "200",
                        "message" => "Departement pas disponible"
                    ]);
                }
            } else {
                return response()->json([
                    "status" => "200",
                    "message" => "Arrondissement pas disponible"
                ]);
            }
        } catch (\Throwable $th) {
            return response()->json([
                "status" => "500",
                "message" => $th
            ]);
        }
    }

    // Suppression d'un arrondissement
    public function delete($id)
    {
        try {
            $arrond = arrondissement::find($id);

            if ($arrond) {

                $message = "Arrondissement " . $arrond->nom . " supprimé avec success";
                $arrond->delete();

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
