<?php

namespace App\Http\Controllers;

use App\Models\region;
use App\Models\ville;
use Illuminate\Http\Request;
use stdClass;

class RegionController extends Controller
{
    // Retourne toutes les régions
    public function getAllRegion()
    {
        try {
            $regions = region::all();
            if (count($regions) > 0) {
                $datas = array();
                foreach ($regions as $reg) {
                    $ville = ville::find($reg->chef_lieu);
                    $newReg = new stdClass;

                    $newReg->id = $reg->id;
                    $newReg->nom = $reg->nom;
                    $newReg->chef_lieu = $ville;

                    array_push($datas, $newReg);
                }
                return response()->json([
                    "status" => "200",
                    "datas" => $datas
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
            "chef_lieu" => "required"
        ]);

        try {
            $ville = ville::find($request->chef_lieu);
            if ($ville) {
                $region = region::create([
                    "nom" => $request->nom,
                    "chef_lieu" => $ville->id
                ]);
                $newReg = new stdClass;

                $newReg->id = $region->id;
                $newReg->nom = $region->nom;
                $newReg->chef_lieu = $ville;
                return response()->json([
                    "status" => "201",
                    "message" => "Created successfully",
                    "datas" => $newReg
                ]);
            } else {
                return response()->json([
                    "status" => "400",
                    "message" => "La ville n'existe pas",
                ]);
            }
        } catch (\Throwable $th) {
            return response()->json([
                "status" => "500",
                "message" => $th
            ]);
        }
    }

    // Retourne un région en particulier
    public function getOneRegion($id)
    {
        try {
            $region = region::find($id);
            if ($region) {
                $ville = ville::find($region->chef_lieu);
                $newReg = new stdClass;

                $newReg->id = $region->id;
                $newReg->nom = $region->nom;
                $newReg->chef_lieu = $ville;
                return response()->json([
                    "status" => "200",
                    "datas" => $newReg
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

    // Mise à jour d'une région
    public function update(Request $request, $id)
    {
        $request->validate([
            "nom" => "required",
            "chef_lieu" => "required"
        ]);

        try {
            $region = region::find($id);
            if ($region) {
                $ville = ville::find($region->chef_lieu);
                if ($ville) {
                    $region->update([
                        "nom" => $request->nom,
                        "chef_lieu" => $request->chef_lieu
                    ]);
                    $newReg = new stdClass;

                    $newReg->id = $region->id;
                    $newReg->nom = $region->nom;
                    $newReg->chef_lieu = $ville;
                    return response()->json([
                        "status" => "200",
                        "message" => "Updated successfully",
                        "datas" => $newReg
                    ]);
                } else {
                    return response()->json([
                        "status" => "400",
                        "message" => "La ville n'existe pas",
                    ]);
                }
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

    // Suppression d'une région
    public function delete($id)
    {
        try {
            $region = region::find($id);

            if ($region) {

                $message = "Région " . $region->nom . " supprimée avec success";
                $region->delete();

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
