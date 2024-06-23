<?php

namespace App\Http\Controllers;

use App\Models\categorie;
use App\Models\corps;
use App\Models\grade;
use App\Models\nommination;
use App\Models\personnel;
use App\Models\situation;
use App\Models\stage;
use Illuminate\Http\Request;
use stdClass;

class SituationController extends Controller
{
    public function getAllSituation()
    {
        try {
            $situations = situation::all();
            $result = array();
            if (count($situations) > 0) {
                foreach ($situations as $situation) {
                    $stage = stage::find($situation->id_stage_seminaire);
                    $corps = corps::find($situation->id_corps);
                    $grade = grade::find($situation->id_grade);
                    $personnel = personnel::find($situation->id_personnel);
                    $categorie = categorie::find($situation->id_categorie);
                    $newSituation = new stdClass;

                    $newSituation->id = $situation->id;
                    $newSituation->date_recrutement = $situation->date_recrutement;
                    $newSituation->nature_acte = $situation->nature_acte;
                    $newSituation->statut_acte = $situation->statut_acte;
                    $newSituation->corps = $corps;
                    $newSituation->grade = $grade;
                    $newSituation->categorie = $categorie;
                    $newSituation->nommination = $situation->nommination;
                    $newSituation->age_dep_retraite = $situation->age_dep_retraite;
                    $newSituation->date_dep_retraite = $situation->date_dep_retraite;
                    $newSituation->poste_actuel = $situation->poste_actuel;
                    $newSituation->niv_instruction = $situation->niv_instruction;
                    $newSituation->stage_seminaire = $stage;
                    $newSituation->personnel = $personnel;

                    array_push($result, $newSituation);
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

    public function getOneSituation($id)
    {
        try {
            $situation = situation::find($id);
            if ($situation) {
                $stage = stage::find($situation->id_stage_seminaire);
                $corps = corps::find($situation->id_corps);
                $grade = grade::find($situation->id_grade);
                $categorie = categorie::find($situation->id_categorie);
                $newSituation = new stdClass;

                $newSituation->id = $situation->id;
                $newSituation->date_recrutement = $situation->date_recrutement;
                $newSituation->nature_acte = $situation->nature_acte;
                $newSituation->statut_acte = $situation->statut_acte;
                $newSituation->corps = $corps;
                $newSituation->grade = $grade;
                $newSituation->categorie = $categorie;
                $newSituation->nommination = $situation->nommination;
                $newSituation->age_dep_retraite = $situation->age_dep_retraite;
                $newSituation->date_dep_retraite = $situation->date_dep_retraite;
                $newSituation->poste_actuel = $situation->poste_actuel;
                $newSituation->niv_instruction = $situation->niv_instruction;
                $newSituation->stage_seminaire = $stage;

                return response()->json([
                    "status" => "200",
                    "datas" => $newSituation
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
            "dateRecrutement" => "nullable",
            "natureActe" => "nullable",
            "statut" => "nullable",
            "corps" => "nullable",
            "grade" => "nullable",
            "categorie" => "nullable",
            "nommination" => "nullable",
            "ageDepRetraite" => "nullable",
            "dateDepRetraite" => "nullable",
            "posteActuel" => "nullable",
            "nivInstruction" => "nullable",
            "personnel" => "nullable",
        ]);

        try {
            $nommination = $request->nommination;
            if ($request->estNomme == 'true') {
                $nommination = nommination::create([
                    "id_rang" => $nommination['rang'],
                    "id_nh" => $nommination['niveauHierarchique'],
                    "fonction" => $nommination['fonction'],
                    "ref_acte" => $nommination['refActe'],
                    "date_nommination" => $nommination['dateNommination'],
                    "id_personnel" => $nommination['personnel'],
                ]);
            }
            $situation = situation::create([
                "date_recrutement" => $request->dateRecrutement,
                "nature_acte" => $request->natureActe,
                "statut" => $request->statut,
                "id_corps" => $request->corps,
                "id_grade" => $request->grade,
                "id_categorie" => $request->categorie,
                "age_dep_retraite" => $request->ageDepRetraite,
                "date_dep_retraite" => $request->dateDepRetraite,
                "poste_actuel" => $request->posteActuel,
                "niv_instruction" => $request->nivInstruction,
                "id_personnel" => $request->personnel,
            ]);

            return response()->json([
                "status" => "201",
                "datas" => ['situation' => $situation, 'nommination' => $nommination]
            ], 201);
        } catch (\Throwable $th) {
            return response()->json(["message" => "Error : " . $th->getMessage()], 500);
        }
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            "date_recrutement" => "nullable",
            "nature_acte" => "nullable",
            "statut_acte" => "nullable",
            "corps" => "nullable",
            "grade" => "nullable",
            "categorie" => "nullable",
            "nommination" => "nullable",
            "age_dep_retraite" => "nullable",
            "date_dep_retraite" => "nullable",
            "poste_actuel" => "nullable",
            "niv_instruction" => "nullable",
        ]);

        try {

            $situation = situation::find($id);

            if ($situation) {
                if (isset($request->stage_seminaire)) {
                    $id_stage_seminaire = $request->stage_seminaire;
                    $stage = stage::find($id_stage_seminaire);
                    if ($stage) {
                        $situation->update([
                            "date_recrutement" => $request->date_recrutement,
                            "nature_acte" => $request->nature_acte,
                            "statut_acte" => $request->statut_acte,
                            "id_corps" => $request->corps,
                            "id_grade" => $request->grade,
                            "id_categorie" => $request->categorie,
                            "nommination" => $request->nommination,
                            "age_dep_retraite" => $request->age_dep_retraite,
                            "date_dep_retraite" => $request->date_dep_retraite,
                            "poste_actuel" => $request->poste_actuel,
                            "niv_instruction" => $request->niv_instruction,
                            "id_stage_seminaire" => $id_stage_seminaire
                        ]);
                        return response()->json([
                            "status" => "200",
                            "datas" => $situation
                        ]);
                    } else {
                        return response()->json([
                            "status" => "200",
                            "situation" => "Stage/séminaire non existant"
                        ]);
                    }
                } else {
                    $id_stage_seminaire = null;
                    $situation->update([
                        "date_recrutement" => $request->date_recrutement,
                        "nature_acte" => $request->nature_acte,
                        "statut_acte" => $request->statut_acte,
                        "id_corps" => $request->corps,
                        "id_grade" => $request->grade,
                        "id_categorie" => $request->categorie,
                        "nommination" => $request->nommination,
                        "age_dep_retraite" => $request->age_dep_retraite,
                        "date_dep_retraite" => $request->date_dep_retraite,
                        "poste_actuel" => $request->poste_actuel,
                        "niv_instruction" => $request->niv_instruction,
                        "id_stage_seminaire" => $id_stage_seminaire
                    ]);
                    return response()->json([
                        "status" => "201",
                        "datas" => $situation
                    ]);
                }
            } else {
                return response()->json([
                    "status" => "200",
                    "message" => "Situation Not found"
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
            $situation = situation::find($id);

            if ($situation) {

                $situation->delete();

                return response()->json([
                    'status' => '200',
                    'message' => "Deletd successfully"
                ]);
            } else {
                return response()->json([
                    'status' => '200',
                    'message' => "Situation Not found"
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
