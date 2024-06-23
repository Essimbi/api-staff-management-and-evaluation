<?php

namespace App\Http\Controllers;

use App\Models\departement;
use App\Models\region;
use App\Models\ville;
use Illuminate\Http\Request;
use stdClass;

class DepartController extends Controller
{
    // Retourne toutes les régions
    public function getAllDepart()
    {
        try {
            $departements = departement::all();
            $result = array();
            if (count($departements) > 0) {
                foreach ($departements as $departement) {
                    $reg = region::find($departement->id_region);
                    $ville = ville::find($departement->chef_lieu);
                    $newDep = new stdClass;

                    $newDep->id = $departement->id;
                    $newDep->nom = $departement->nom;
                    $newDep->chef_lieu = $ville;
                    $newDep->region = $reg;

                    array_push($result, $newDep);
                }
                return response()->json([
                    "status" => "200",
                    "departements" => $result
                ]);
            } else {
                return response()->json([
                    "status" => "200",
                    "message" => "Region not found"
                ]);
            }
        } catch (\Throwable $th) {
            return response()->json([
                "status" => "500",
                "message" => $th
            ]);
        }
    }

    // Enrégister une région
    public function store(Request $request)
    {
        $request->validate([
            "nom" => "required",
            "chef_lieu" => "required",
            "region" => "required"
        ]);

        try {

            $departement = departement::create([
                "nom" => $request->nom,
                "chef_lieu" => $request->chef_lieu,
                "id_region" => $request->region
            ]);
            return response()->json([
                "status" => "201",
                "message" => "Created successfully",
                "datas" => $departement
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                "status" => "500",
                "message" => $th
            ]);
        }
    }

    // Retourne un région en particulier
    public function getOneDepart($id)
    {
        try {
            $departement = departement::find($id);
            if ($departement) {
                $reg = region::find($departement->id_region);
                $ville = ville::find($departement->chef_lieu);
                $newDep = new stdClass;

                $newDep->id = $departement->id;
                $newDep->depart = $departement->nom;
                $newDep->chef_lieu = $ville;
                $newDep->region = $reg;
                return response()->json([
                    "status" => "200",
                    "datas" => $newDep
                ]);
            } else {
                return response()->json([
                    "status" => "200",
                    "message" => "Not found"
                ]);
            }
        } catch (\Throwable $th) {
            return response()->json([
                "status" => "500",
                "message" => $th
            ]);
        }
    }

    // Mise à jour
    public function update(Request $request, $id)
    {
        $request->validate([
            "nom" => "required",
            "chef_lieu" => "required",
            "region" => "required"
        ]);

        try {

            $departement = departement::find($id);

            if ($departement) {

                $departement->update([
                    "nom" => $request->nom,
                    "chef_lieu" => $request->chef_lieu,
                    "id_region" => $request->region
                ]);
                return response()->json([
                    "status" => "200",
                    "message" => "Updated successfully",
                    "datas" => $departement
                ]);
            } else {
                return response()->json([
                    "status" => "200",
                    "message" => "Departement pas disponible"
                ]);
            }
        } catch (\Throwable $th) {
            return response()->json([
                "status" => "500",
                "message" => $th
            ]);
        }
    }

    // Suppression d'un département
    public function delete($id)
    {
        try {
            $departement = departement::find($id);

            if ($departement) {

                $message = "Département " . $departement->nom . " supprimé avec success";
                $departement->delete();

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
