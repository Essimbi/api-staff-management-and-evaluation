<?php

namespace App\Http\Controllers;

use App\Models\campagne;
use App\Models\clean;
use App\Models\commentaire;
use App\Models\compte;
use App\Models\concerne;
use App\Models\culture;
use App\Models\discipline;
use App\Models\leadership;
use App\Models\learning;
use App\Models\living;
use App\Models\note;
use App\Models\objectif;
use App\Models\personnel;
use App\Models\qualite;
use App\Models\question;
use App\Models\recommandation;
use App\Models\souhait;
use App\Models\synthese;
use App\Models\typeStructure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;
use stdClass;

class ObjectifController extends Controller
{
    public function getAll(Request $request)
    {
        try {

            $token = true;

            if ($token) {
                // Récupérer le payload du token
                // $payload = JWTAuth::getPayload($token);

                // Récupérer la valeur de la clé 'user_id' du payload
                // $compte = compte::find($payload->get('sub'));
                $user_id = $request->user_id;

                $year = date('Y');
                $objectifs = DB::table('objectifs')
                    // ->join('campagnes', 'objectifs.id_campagne', '=', 'campagnes.id')
                    ->where('objectifs.id_personnel', '=', $user_id)
                    ->whereYear('created_at', $year)
                    // ->where('campagnes.statut', '=', 'En cours')
                    ->select('objectifs.*')
                    ->get();

                // $objectifs = objectif::all();
                if (count($objectifs) > 0) {
                    $result = array();
                    foreach ($objectifs as $objectif) {
                        // $note = DB::table('notes')
                        //     ->where('id_objectif', '=', $objectif->id)
                        //     ->where('id_personnel', '=', $user_id)
                        //     ->first();


                        $newObjectif = new stdClass;
                        $newObjectif->id = $objectif->id;
                        $newObjectif->specifique = $objectif->specifique;
                        $newObjectif->operationnel = $objectif->operationnel;
                        $newObjectif->source = $objectif->source_collecte;
                        $newObjectif->frequence = $objectif->frequence;
                        $newObjectif->indicateur = $objectif->indicateur;
                        $newObjectif->statut = $objectif->statut;
                        $newObjectif->valeur = $objectif->valeur;
                        $newObjectif->cible = $objectif->cible;
                        $newObjectif->created_at = $objectif->created_at;

                        array_push($result, $newObjectif);
                    }
                    return response()->json([
                        "datas" => $result,
                        "nbreObjectif" => count($result)
                    ], 200);
                }
                return response()->json(["message" => "Data not found"], 200);
            }
            return response()->json(["message" => "Token not found"], 401);
        } catch (\Throwable $th) {
            return response()->json(["message" => "Error : " . $th->getMessage()], 500);
        }
    }

    public function getCampagne(Request $request)
    {
        $token = true;
        // $token = substr($tokenRequest, 6);

        if ($token) {
            try {
                // Récupérer le payload du token
                // $payload = JWTAuth::getPayload($token);

                // Récupérer la valeur de la clé 'user_id' du payload
                // $compte = compte::find($payload->get('sub'));
                $user_id = $request->user_id;

                $campagne = DB::table('campagnes')
                    ->join('concernes', 'campagnes.id', '=', 'concernes.id_campagne')
                    ->where('concernes.id_personnel', '=', $user_id)
                    ->where('campagnes.statut', '=', 'En cours')
                    // ->where('concernes.score', '=', null)
                    ->select('campagnes.*', 'concernes.*')
                    ->first();

                return response()->json(["datas" => $campagne], 200);
            } catch (\Exception $e) {
                return response()->json(["message" => "Error : " . $e->getMessage()], 500);
            }
        } else {
            return response()->json(["message" => "UNAUTHORIZED"], 401);
        }
    }

    public function getCampagneEvaluated(Request $request)
    {
        $token = true;
        // $token = substr($tokenRequest, 6);

        if ($token) {
            try {
                // Récupérer le payload du token
                // $payload = JWTAuth::getPayload($token);

                // Récupérer la valeur de la clé 'user_id' du payload
                // $compte = compte::find($payload->get('sub'));
                $user_id = $request->id_personnel;

                $campagne = DB::table('campagnes')
                    ->join('concernes', 'campagnes.id', '=', 'concernes.id_campagne')
                    ->where('concernes.id_personnel', '=', $user_id)
                    ->where('campagnes.statut', '=', 'En cours')
                    // ->where('concernes.score', '=', null)
                    ->select('campagnes.*', 'concernes.*')
                    ->first();

                return response()->json(["datas" => $campagne], 200);
            } catch (\Exception $e) {
                return response()->json(["message" => "Error : " . $e->getMessage()], 500);
            }
        } else {
            return response()->json(["message" => "UNAUTHORIZED"], 401);
        }
    }

    // public function getObjectifPerCampagne($id)
    // {
    //     try {
    //         $objectifs = objectif::all();
    //         if (count($objectifs) > 0) {
    //             $result = array();
    //             foreach ($objectifs as $objectif) {
    //                 $personnel = personnel::find($objectif->id_personnel);
    //                 $campagne = campagne::find($objectif->id_campagne);

    //                 $newObjectif = new stdClass;
    //                 $newObjectif->id = $objectif->id;
    //                 $newObjectif->contenu = $objectif->contenu;
    //                 $newObjectif->campagne = $campagne;
    //                 $newObjectif->personnel = $personnel;

    //                 array_push($result, $newObjectif);
    //             }
    //             return response()->json([
    //                 "datas" => $$result
    //             ], 200);
    //         }
    //         return response()->json(["message" => "Data not found"], 200);
    //     } catch (\Throwable $th) {
    //         return response()->json(["message" => "Error : " . $th->getMessage()], 500);
    //     }
    // }

    public function getOne($id)
    {
        try {
            $objectif = objectif::find($id);
            if ($objectif) {
                $personnel = personnel::find($objectif->id_personnel);
                $campagne = campagne::find($objectif->id_campagne);

                $newObjectif = new stdClass;
                $newObjectif->id = $objectif->id;
                $newObjectif->fonction = $objectif->fonction;
                $newObjectif->indicateur = $objectif->indicateur;
                $newObjectif->valeur = $objectif->valeur;
                $newObjectif->cible = $objectif->cible;
                // $newObjectif->campagne = $campagne;
                $newObjectif->personnel = $personnel;
                return response()->json([
                    "datas" => $newObjectif
                ], 200);
            }
            return response()->json(["message" => "Data not found"], 200);
        } catch (\Throwable $th) {
            return response()->json(["message" => "Error : " . $th->getMessage()], 500);
        }
    }

    public function store(Request $request)
    {
        $request->validate([
            "specifique" => "required",
            "operationnel" => "required",
            "indicateur" => "required",
            "cible" => "required",
            // "valeur" => "required",
            "source" => "required",
            "frequence" => "required",
            // "note" => "required",
            "id_campagne" => "nullable"
        ]);

        try {

            $token = true;

            if ($token) {
                // Récupérer le payload du token
                // $payload = JWTAuth::getPayload($token);

                // Récupérer la valeur de la clé 'user_id' du payload
                // $compte = compte::find($payload->get('sub'));
                $user_id = $request->user_id;

                $id_camp = (isset($request->id_campagne)) ? $request->id_campagne : null;

                $objectif = new objectif([
                    "specifique" => $request->specifique,
                    "operationnel" => $request->operationnel,
                    "indicateur" => $request->indicateur,
                    "source_collecte" => $request->source,
                    "frequence" => $request->frequence,
                    "valeur" => 0,
                    "cible" => $request->cible,
                    "id_personnel" => $user_id,
                    "id_campagne" => $id_camp,
                    "statut" => "enregistré"
                ]);
                $objectif->save();
                // note::create([
                //     "valeur" => $request->note,
                //     "observation" => $request->observation,
                //     "id_objectif" => $objectif->id,
                //     "id_personnel" => $user_id
                // ]);
                return response()->json([
                    "message" => "Objectif ajouté",
                    "datas" => $objectif
                ], 201);
            }
            return response()->json(["message" => "UNAUTHORIZED"], 401);
        } catch (\Throwable $th) {
            return response()->json(["message" => "Error : " . $th->getMessage()], 500);
        }
    }

    public function update(Request $request, $id)
    {

        $request->validate([
            "specifique" => "required",
            "operationnel" => "required",
            "indicateur" => "required",
            "cible" => "required",
            "valeur" => "required",
            "source" => "required",
            "frequence" => "required",
        ]);
        try {

            if ($objectif = objectif::find($id)) {
                $objectif->update([
                    "specifique" => $request->specifique,
                    "operationnel" => $request->operationnel,
                    "indicateur" => $request->indicateur,
                    "cible" => $request->cible,
                    "valeur" => $request->valeur,
                    "source_collecte" => $request->source,
                    "frequence" => $request->frequence
                ]);
                // $note = note::where('id_objectif', '=', $objectif->id);
                // $note->update([
                //     "valeur" => $request->note,
                //     "observation" => $request->observation
                // ]);
                return response()->json([
                    "message" => "Objectif mise à jour",
                    "datas" => $objectif
                ], 200);
            }
            return response()->json(["message" => "Objectif inexistant"], 200);
        } catch (\Throwable $th) {
            return response()->json(["message" => "Error : " . $th->getMessage()], 500);
        }

        // if ($request->input("contenu")) {

        // } else {
        //     if ($objectif = objectif::find($id)) {
        //         $objectif->update([
        //             "statut" => "soumis",
        //         ]);
        //         return response()->json([
        //             "message" => "Objectif mise à jour",
        //             "datas" => $objectif
        //         ], 200);
        //     }
        //     return response()->json(["message" => "Objectif inexistant"], 200);
        // }
    }

    public function updateAll($id)
    {
        try {
            $year = date('Y');
            $objectifs = objectif::whereYear('created_at', $year)->get();
            foreach ($objectifs as $objectif) {
                $objectif->statut = 'soumis';
                $objectif->save();
            }
            return response()->json([
                "message" => "Objectifs mis à jour"
            ], 200);
        } catch (\Throwable $th) {
            return response()->json(["message" => "Error : " . $th->getMessage()], 500);
        }
    }

    /*  public function updateAfterSubmit(Request $request, $id)
    {
        $request->validate([
            "contenu" => "required",
            "description" => "required",
            //"observation" => "required"
        ]);

        try {
            if ($objectif = objectif::find($id)) {
                $objectif->update([
                    "contenu" => $request->contenu,
                    "description" => $request->description
                ]);
                return response()->json([
                    "message" => "Objectif mise à jour",
                    "datas" => $objectif
                ], 200);
            }
            return response()->json(["message" => "Objectif inexistant"], 200);
        } catch (\Throwable $th) {
            return response()->json(["message" => "Error : " . $th->getMessage()], 500);
        }
    } */

    public function delete($id)
    {
        try {
            if ($objectif = objectif::find($id)) {
                $objectif->delete();
                return response()->json(["message" => "Objectif supprimé"], 200);
            }
            return response()->json(["message" => "Objectif inexistant"], 200);
        } catch (\Throwable $th) {
            return response()->json(["message" => "Error : " . $th->getMessage()], 500);
        }
    }

    public function changeN2(Request $request)
    {
        $request->validate([
            "eval" => "required",
            "id_campagne" => "required",
        ]);

        $token = true;

        if ($token) {
            try {
                // Récupérer le payload du token
                // $payload = JWTAuth::getPayload($token);

                // Récupérer la valeur de la clé 'user_id' du payload
                // $compte = compte::find($payload->get('sub'));
                $user_id = $request->user_id;

                $rec = concerne::where('id_campagne', '=', $request->id_campagne)->where('id_personnel', '=', $user_id)->first();
                $rec->n2 = $request->eval;
                $rec->save();



                return response()->json(["datas" => "Modification éffectuée"], 200);
            } catch (\Exception $e) {
                return response()->json(["message" => "Error : " . $e->getMessage()], 500);
            }
        } else {
            return response()->json(["message" => "UNAUTHORIZED"], 401);
        }
    }

    public function auto_evaluate(Request $request)
    {
        $request->validate([
            "evaluateurs" => "required",
            "question" => "required",
            "note_objectif" => "required",
            "qualites" => "required",
            "discipline" => "required",
            "id_campagne" => "nullable",
            "valeurs" => "nullable",
        ]);

        $token = true;

        if ($token) {
            try {
                // Récupérer le payload du token
                // $payload = JWTAuth::getPayload($token);

                // Récupérer la valeur de la clé 'user_id' du payload
                // $compte = compte::find($payload->get('sub'));
                $user_id = $request->user_id;

                $evaluateurs = $request->evaluateurs;

                $rec = concerne::where('id_campagne', '=', $request->id_campagne)->where('id_personnel', '=', $user_id)->first();
                $rec->n1 = $evaluateurs['evaluateur1'];
                $rec->n2 = $evaluateurs['evaluateur2'];

                // $personnel = personnel::find($user_id);
                // $personnel->update([
                //     "n1" => $evaluateurs['evaluateur1'],
                //     "n2" => $evaluateurs['evaluateur2']
                // ]);

                $questions = $request->question;
                $note_objectif = $request->note_objectif;
                $qualites = $request->qualites;
                $discipline = $request->discipline;
                $valeurs = $request->valeurs;

                foreach ($valeurs as $val) {
                    $objectif = objectif::find($val['id_objectif']);
                    $objectif->update([
                        "valeur" => $val['valeur']
                    ]);
                }

                $responseQ = question::create([
                    "q1_a" => $questions['res1_a'],
                    "q1_b" => $questions['res1_b'],
                    "q2" => $questions['res2'],
                    "q3" => $questions['res3'],
                    "id_personnel" => $user_id,
                    "id_campagne" => $request->id_campagne
                ]);

                $d = discipline::create([
                    "ponctualite" => $discipline['ponctualite'],
                    "presentation" => $discipline['presentation'],
                    "relation" => $discipline['relation'],
                    "id_personnel" => $user_id,
                    "id_campagne" => $request->id_campagne
                ]);

                $noteDiscipline = ($discipline['ponctualite'] + $discipline['presentation'] + $discipline['relation']);

                $q = qualite::create([
                    "creativite" => $qualites['creativite'],
                    "esprit_equipe" => $qualites['esprit_equipe'],
                    "adaptation" => $qualites['adaptation'],
                    "relation" => $qualites['relation'],
                    "communication" => $qualites['communication'],
                    "id_personnel" => $user_id,
                    "id_campagne" => $request->id_campagne
                ]);

                $noteQualite = ($qualites['creativite'] + $qualites['esprit_equipe'] + $qualites['adaptation'] + $qualites['relation'] + $qualites['communication']);

                $total_objectif = 0;
                $total_notes = 0;
                foreach ($note_objectif as $note) {
                    note::create([
                        "valeur" => $note['valeur'],
                        "id_objectif" => $note['id_objectif'],
                        "id_personnel" => $user_id,
                        "id_campagne" => $request->id_campagne
                    ]);

                    $total_notes += $note['valeur'];
                    $total_objectif += 1;
                }

                $note_rendement = ($total_notes * 20) / ($total_objectif * 5);

                $synthese = new stdClass;
                $synthese->id_campagne = $request->id_campagne;
                $synthese->suivi_objectif = $note_rendement;
                $synthese->qualite_personnelle = $noteQualite;
                $synthese->discipline = $noteDiscipline;

                $scoreObjectif = ($note_rendement / 20) * 70;
                $scoreQualite = ($noteQualite / 20) * 15;
                $scoreDiscipline = ($noteDiscipline / 12) * 15;

                $synthese->scroreObjectif = $scoreObjectif;
                $synthese->scoreQualite = $scoreQualite;
                $synthese->scoreDiscipline = $scoreDiscipline;

                $scoreTotal = $scoreDiscipline + $scoreObjectif + $scoreQualite;

                // Déterminer l'appréciation en fonction du score total
                if ($scoreTotal >= 96 && $scoreTotal <= 100) {
                    $appreciation = 'Excellent';
                } else if ($scoreTotal >= 86 && $scoreTotal <= 95) {
                    $appreciation = 'Très Bien';
                } else if ($scoreTotal >= 76 && $scoreTotal <= 85) {
                    $appreciation = 'Bien';
                } else if ($scoreTotal >= 61 && $scoreTotal <= 75) {
                    $appreciation = 'Passable';
                } else if ($scoreTotal >= 50 && $scoreTotal <= 60) {
                    $appreciation = 'Faible';
                }

                $result = array();
                // Enrégistrement des syntèses
                $s1 = synthese::create([
                    "critere" => "Suivi des objectifs",
                    "note" => $note_rendement,
                    "note_max" => 20,
                    "poids" => 70,
                    "score" => $scoreObjectif,
                    "id_personnel" => $user_id,
                    "id_campagne" => $request->id_campagne
                ]);

                array_push($result, $s1);

                $s2 = synthese::create([
                    "critere" => "Qualités personnelles et relationnelles",
                    "note" => $noteQualite,
                    "note_max" => 20,
                    "poids" => 15,
                    "score" => $scoreQualite,
                    "id_personnel" => $user_id,
                    "id_campagne" => $request->id_campagne
                ]);

                array_push($result, $s2);

                $s3 = synthese::create([
                    "critere" => "Discipline",
                    "note" => $noteDiscipline,
                    "note_max" => 20,
                    "poids" => 15,
                    "score" => $scoreDiscipline,
                    "id_personnel" => $user_id,
                    "id_campagne" => $request->id_campagne
                ]);

                array_push($result, $s3);


                $rec->score = $scoreTotal;
                $rec->appreciation = $appreciation;
                $rec->save();

                return response()->json(["datas" => $result, "scoreTotal" => $scoreTotal, "appreciation" => $appreciation], 201);
            } catch (\Exception $e) {
                return response()->json(["message" => "Error : " . $e->getMessage()], 500);
            }
        } else {
            return response()->json(["message" => "UNAUTHORIZED"], 401);
        }
    }

    public function re_auto_evaluate(Request $request)
    {
        $request->validate([
            "evaluateurs" => "required",
            "question" => "required",
            "note_objectif" => "required",
            "qualites" => "required",
            "discipline" => "required",
            "id_campagne" => "nullable",
            "valeurs" => "nullable",
            // "ids" => "nullable",
        ]);

        $token = true;

        if ($token) {
            try {
                // Récupérer le payload du token
                // $payload = JWTAuth::getPayload($token);

                // Récupérer la valeur de la clé 'user_id' du payload
                // $compte = compte::find($payload->get('sub'));
                $user_id = $request->user_id;

                $evaluateurs = $request->evaluateurs;

                $rec = concerne::where('id_campagne', '=', $request->id_campagne)->where('id_personnel', '=', $user_id)->first();
                $rec->n1 = $evaluateurs['evaluateur1'];
                $rec->n2 = $evaluateurs['evaluateur2'];

                // $personnel = personnel::find($user_id);
                // $personnel->update([
                //     "n1" => $evaluateurs['evaluateur1'],
                //     "n2" => $evaluateurs['evaluateur2']
                // ]);

                $questions = $request->question;
                $note_objectif = $request->note_objectif;
                $qualites = $request->qualites;
                $discipline = $request->discipline;
                $valeurs = $request->valeurs;
                // $ids = $request->ids;

                foreach ($valeurs as $val) {
                    $objectif = objectif::find($val['id_objectif']);
                    $objectif->update([
                        "valeur" => $val['valeur']
                    ]);
                }

                $responseQ = question::where('id_campagne', '=', $request->id_campagne)->where('id_personnel', '=', $user_id)->first();
                $responseQ->q1_a = $questions['res1_a'];
                $responseQ->q1_b = $questions['res1_b'];
                $responseQ->q2 = $questions['res2'];
                $responseQ->q3 = $questions['res3'];
                $responseQ->save();
                // $responseQ = question::find($ids['id_question']) ;
                // $responseQ->update([
                //     "q1_a" => $questions['res1_a'],
                //     "q1_b" => $questions['res1_b'],
                //     "q2" => $questions['res2'],
                //     "q3" => $questions['res3']
                //     // "id_personnel" => $user_id,
                //     // "id_campagne" => $request->id_campagne
                // ]);

                $d = discipline::where('id_campagne', '=', $request->id_campagne)->where('id_personnel', '=', $user_id)->first();
                $d->ponctualite = $discipline['ponctualite'];
                $d->presentation = $discipline['presentation'];
                $d->relation = $discipline['relation'];
                $d->save();

                // $d = discipline::create([
                //     "ponctualite" => $discipline['ponctualite'],
                //     "presentation" => $discipline['presentation'],
                //     "relation" => $discipline['relation'],
                //     "id_personnel" => $user_id,
                //     "id_campagne" => $request->id_campagne
                // ]);

                $noteDiscipline = ($discipline['ponctualite'] + $discipline['presentation'] + $discipline['relation']);

                $q = qualite::where('id_campagne', '=', $request->id_campagne)->where('id_personnel', '=', $user_id)->first();
                $q->creativite = $qualites['creativite'];
                $q->esprit_equipe = $qualites['esprit_equipe'];
                $q->adaptation = $qualites['adaptation'];
                $q->relation = $qualites['relation'];
                $q->communication = $qualites['communication'];
                $q->save();

                // $q = qualite::create([
                //     "creativite" => $qualites['creativite'],
                //     "esprit_equipe" => $qualites['esprit_equipe'],
                //     "adaptation" => $qualites['adaptation'],
                //     "relation" => $qualites['relation'],
                //     "communication" => $qualites['communication'],
                //     "id_personnel" => $user_id,
                //     "id_campagne" => $request->id_campagne
                // ]);

                $noteQualite = ($qualites['creativite'] + $qualites['esprit_equipe'] + $qualites['adaptation'] + $qualites['relation'] + $qualites['communication']);

                $total_objectif = 0;
                $total_notes = 0;
                foreach ($note_objectif as $note) {
                    $note = note::where('id_campagne', '=', $request->id_campagne)
                        ->where('id_objectif', '=', $note['id_objectif'])
                        ->where('id_personnel', '=', $user_id)->first();
                    $note->valeur = $note['valeur'];
                    $note->save();

                    $total_notes += $note['valeur'];
                    $total_objectif += 1;
                }

                $note_rendement = ($total_notes * 20) / ($total_objectif * 5);

                $synthese = new stdClass;
                $synthese->id_campagne = $request->id_campagne;
                $synthese->suivi_objectif = $note_rendement;
                $synthese->qualite_personnelle = $noteQualite;
                $synthese->discipline = $noteDiscipline;

                $scoreObjectif = ($note_rendement / 20) * 70;
                $scoreQualite = ($noteQualite / 20) * 15;
                $scoreDiscipline = ($noteDiscipline / 12) * 15;

                $synthese->scroreObjectif = $scoreObjectif;
                $synthese->scoreQualite = $scoreQualite;
                $synthese->scoreDiscipline = $scoreDiscipline;

                $scoreTotal = $scoreDiscipline + $scoreObjectif + $scoreQualite;

                // Déterminer l'appréciation en fonction du score total
                if ($scoreTotal >= 96 && $scoreTotal <= 100) {
                    $appreciation = 'Excellent';
                } else if ($scoreTotal >= 86 && $scoreTotal <= 95) {
                    $appreciation = 'Très Bien';
                } else if ($scoreTotal >= 76 && $scoreTotal <= 85) {
                    $appreciation = 'Bien';
                } else if ($scoreTotal >= 61 && $scoreTotal <= 75) {
                    $appreciation = 'Passable';
                } else if ($scoreTotal >= 50 && $scoreTotal <= 60) {
                    $appreciation = 'Faible';
                }

                $result = array();
                // Enrégistrement des syntèses
                $syntheses = synthese::where('id_campagne', '=', $request->id_campagne)->where('id_personnel', '=', $user_id)->get();
                foreach ($syntheses as $synth) {
                    $synth = synthese::find($synth->id);
                    $synth->delete();
                }
                $s1 = synthese::create([
                    "critere" => "Suivi des objectifs",
                    "note" => $note_rendement,
                    "note_max" => 20,
                    "poids" => 70,
                    "score" => $scoreObjectif,
                    "id_personnel" => $user_id,
                    "id_campagne" => $request->id_campagne
                ]);

                array_push($result, $s1);

                $s2 = synthese::create([
                    "critere" => "Qualités personnelles et relationnelles",
                    "note" => $noteQualite,
                    "note_max" => 20,
                    "poids" => 15,
                    "score" => $scoreQualite,
                    "id_personnel" => $user_id,
                    "id_campagne" => $request->id_campagne
                ]);

                array_push($result, $s2);

                $s3 = synthese::create([
                    "critere" => "Discipline",
                    "note" => $noteDiscipline,
                    "note_max" => 20,
                    "poids" => 15,
                    "score" => $scoreDiscipline,
                    "id_personnel" => $user_id,
                    "id_campagne" => $request->id_campagne
                ]);

                array_push($result, $s3);


                $rec->score = $scoreTotal;
                $rec->appreciation = $appreciation;
                $rec->save();

                return response()->json(["datas" => $result, "scoreTotal" => $scoreTotal, "appreciation" => $appreciation], 201);
            } catch (\Exception $e) {
                return response()->json(["message" => "Error : " . $e->getMessage()], 500);
            }
        } else {
            return response()->json(["message" => "UNAUTHORIZED"], 401);
        }
    }


    public function manager_evaluation(Request $request)
    {
        $request->validate([
            "evaluateurs" => "required",
            "question" => "required",
            "note_objectif" => "required",
            "living" => "required",
            "learning" => "required",
            "leadership" => "required",
            "culture" => "required",
            "clean" => "required",
            "id_campagne" => "nullable",
            "valeurs" => "nullable",
        ]);

        $token = true;

        if ($token) {
            try {
                // Récupérer le payload du token
                // $payload = JWTAuth::getPayload($token);

                // Récupérer la valeur de la clé 'user_id' du payload
                // $compte = compte::find($payload->get('sub'));
                $user_id = $request->user_id;

                $evaluateurs = $request->evaluateurs;

                $rec = concerne::where('id_campagne', '=', $request->id_campagne)->where('id_personnel', '=', $user_id)->first();
                $rec->n1 = $evaluateurs['evaluateur1'];
                $rec->n2 = $evaluateurs['evaluateur2'];
                // $rec->save();


                $personnel = personnel::find($user_id);
                $categorie = $personnel->categorie;
                // $personnel->update([
                //     "n1" => $evaluateurs['evaluateur1'],
                //     "n2" => $evaluateurs['evaluateur2']
                // ]);

                $questions = $request->question;
                $note_objectif = $request->note_objectif;
                $leadership = $request->leadership;
                $learninig = $request->learning;
                $living = $request->living;
                $culture = $request->culture;
                $clean = $request->clean;

                $valeurs = $request->valeurs;

                foreach ($valeurs as $val) {
                    $objectif = objectif::find($val['id_objectif']);
                    $objectif->update([
                        "valeur" => $val['valeur']
                    ]);
                }

                $responseQ = question::create([
                    "q1_a" => $questions['res1_a'],
                    "q1_b" => $questions['res1_b'],
                    "q2" => $questions['res2'],
                    "q3" => $questions['res3'],
                    "id_personnel" => $user_id,
                    "id_campagne" => $request->id_campagne
                ]);

                // ddd('toto') ;

                living::create([
                    "integrity" => $living['integrity'],
                    "courage" => $living['courage'],
                    "creativity" => $living['creativity'],
                    "value" => $living['value'],
                    "note" => $living['note'],
                    "id_personnel" => $user_id,
                    "id_campagne" => $request->id_campagne
                ]);

                learning::create([
                    "training" => $learninig['training'],
                    "capability" => $learninig['capability'],
                    "improvement" => $learninig['improvement'],
                    "grow" => $learninig['grow'],
                    "note" => $learninig['note'],
                    "id_personnel" => $user_id,
                    "id_campagne" => $request->id_campagne
                ]);

                leadership::create([
                    "accountability" => $leadership['accountability'],
                    "result" => $leadership['result'],
                    "best" => $leadership['best'],
                    "edge" => $leadership['edge'],
                    "note" => $leadership['note'],
                    "id_personnel" => $user_id,
                    "id_campagne" => $request->id_campagne
                ]);

                culture::create([
                    "strong" => $culture['strong'],
                    "best" => $culture['best'],
                    "team" => $culture['team'],
                    "diversity" => $culture['diversity'],
                    "reward" => $culture['reward'],
                    "emotional" => $culture['emotional'],
                    "note" => $culture['note'],
                    "id_personnel" => $user_id,
                    "id_campagne" => $request->id_campagne
                ]);

                clean::create([
                    "office" => $clean['office'],
                    "cars" => $clean['cars'],
                    "employees" => $clean['employees'],
                    "note" => $clean['note'],
                    "id_personnel" => $user_id,
                    "id_campagne" => $request->id_campagne
                ]);

                $noteQualite = ($living['note'] + $learninig['note'] + $leadership['note'] + $culture['note'] + $clean['note']);

                $total_objectif = 0;
                $total_notes = 0;
                foreach ($note_objectif as $note) {
                    note::create([
                        "valeur" => $note['valeur'],
                        "id_objectif" => $note['id_objectif'],
                        "id_personnel" => $user_id,
                        "id_campagne" => $request->id_campagne
                    ]);

                    $total_notes += $note['valeur'];
                    $total_objectif += 1;
                }

                $note_rendement = ($total_notes * 20) / ($total_objectif * 5);
                // return response()->json(["scoreTotal" => $total_notes], 201);
                $note_rendement = ($note_rendement * 100) / 20;

                $synthese = new stdClass;
                $synthese->id_campagne = $request->id_campagne;
                $synthese->suivi_objectif = $note_rendement;
                $synthese->qualite_personnelle = $noteQualite;

                switch ($categorie) {
                    case 'CAT-X':
                        $poid1 = 60;
                        $poid2 = 40;
                        break;

                    case 'CAT-XI':
                        $poid1 = 50;
                        $poid2 = 50;
                        break;

                    case 'CAT-XII':
                        $poid1 = 40;
                        $poid2 = 60;
                        break;
                }

                $scoreObjectif = ($note_rendement * $poid1) / 100;
                $scoreQualite = ($noteQualite * $poid2) / 100;

                $synthese->scroreObjectif = $scoreObjectif;
                $synthese->scoreQualite = $scoreQualite;

                $scoreTotal = $scoreObjectif + $scoreQualite;


                // Déterminer l'appréciation en fonction du score total
                if ($scoreTotal >= 96 && $scoreTotal <= 100) {
                    $appreciation = 'Excellent';
                } else if ($scoreTotal >= 86 && $scoreTotal <= 95) {
                    $appreciation = 'Très Bien';
                } else if ($scoreTotal >= 76 && $scoreTotal <= 85) {
                    $appreciation = 'Bien';
                } else if ($scoreTotal >= 61 && $scoreTotal <= 75) {
                    $appreciation = 'Passable';
                } else if ($scoreTotal >= 50 && $scoreTotal <= 60) {
                    $appreciation = 'Faible';
                }

                $result = array();
                // Enrégistrement des syntèses
                $s1 = synthese::create([
                    "critere" => "Strategic result",
                    "note" => $note_rendement,
                    "note_max" => 100,
                    "poids" => $poid1,
                    "score" => $scoreObjectif,
                    "id_personnel" => $user_id,
                    "id_campagne" => $request->id_campagne
                ]);

                array_push($result, $s1);

                $s2 = synthese::create([
                    "critere" => "LEADERSHIP AND MANAGEMENT OBJECTIVES",
                    "note" => $noteQualite,
                    "note_max" => 100,
                    "poids" => $poid2,
                    "score" => $scoreQualite,
                    "id_personnel" => $user_id,
                    "id_campagne" => $request->id_campagne
                ]);

                array_push($result, $s2);

                // $rec = concerne::where('id_campagne', '=', $request->id_campagne)->where('id_personnel', '=', $user_id)->first();
                $rec->score = $scoreTotal;
                $rec->appreciation = $appreciation;
                $rec->save();

                return response()->json(["datas" => $result, "scoreTotal" => $scoreTotal, "appreciation" => $appreciation], 201);
            } catch (\Exception $e) {
                return response()->json(["message" => "Error : " . $e->getMessage()], 500);
            }
        } else {
            return response()->json(["message" => "UNAUTHORIZED"], 401);
        }
    }

    public function re_manager_evaluation(Request $request)
    {
        $request->validate([
            "evaluateurs" => "required",
            "question" => "required",
            "note_objectif" => "required",
            "living" => "required",
            "learning" => "required",
            "leadership" => "required",
            "culture" => "required",
            "clean" => "required",
            "id_campagne" => "nullable",
            "valeurs" => "nullable",
        ]);

        $token = true;

        if ($token) {
            try {
                // Récupérer le payload du token
                // $payload = JWTAuth::getPayload($token);

                // Récupérer la valeur de la clé 'user_id' du payload
                // $compte = compte::find($payload->get('sub'));
                $user_id = $request->user_id;

                $evaluateurs = $request->evaluateurs;

                $rec = concerne::where('id_campagne', '=', $request->id_campagne)->where('id_personnel', '=', $user_id)->first();
                $rec->n1 = $evaluateurs['evaluateur1'];
                $rec->n2 = $evaluateurs['evaluateur2'];
                // $rec->save();


                $personnel = personnel::find($user_id);
                $categorie = $personnel->categorie;
                // $personnel->update([
                //     "n1" => $evaluateurs['evaluateur1'],
                //     "n2" => $evaluateurs['evaluateur2']
                // ]);

                $questions = $request->question;
                $note_objectif = $request->note_objectif;
                $leadership = $request->leadership;
                $learninig = $request->learning;
                $living = $request->living;
                $culture = $request->culture;
                $clean = $request->clean;

                $valeurs = $request->valeurs;

                foreach ($valeurs as $val) {
                    $objectif = objectif::find($val['id_objectif']);
                    $objectif->update([
                        "valeur" => $val['valeur']
                    ]);
                }

                $responseQ = question::where('id_campagne', '=', $request->id_campagne)->where('id_personnel', '=', $user_id)->first();
                $responseQ->q1_a = $questions['res1_a'];
                $responseQ->q1_b = $questions['res1_b'];
                $responseQ->q2 = $questions['res2'];
                $responseQ->q3 = $questions['res3'];
                $responseQ->save();

                // ddd('toto') ;
                $livingS = living::where('id_campagne', '=', $request->id_campagne)->where('id_personnel', '=', $user_id)->first();
                $livingS->integrity = $living['integrity'];
                $livingS->courage = $living['courage'];
                $livingS->value = $living['value'];
                $livingS->note = $living['note'];
                $livingS->save();
                // living::create([
                //     "integrity" => $living['integrity'],
                //     "courage" => $living['courage'],
                //     "creativity" => $living['creativity'],
                //     "value" => $living['value'],
                //     "note" => $living['note'],
                //     "id_personnel" => $user_id,
                //     "id_campagne" => $request->id_campagne
                // ]);
                $learningS = learning::where('id_campagne', '=', $request->id_campagne)->where('id_personnel', '=', $user_id)->first();
                $learningS->training = $learninig['training'];
                $learningS->capability = $learninig['capability'];
                $learningS->improvement = $learninig['improvement'];
                $learningS->grow = $learninig['grow'];
                $learningS->note = $learninig['note'];
                $learningS->save();
                // learning::create([
                //     "training" => $learninig['training'],
                //     "capability" => $learninig['capability'],
                //     "improvement" => $learninig['improvement'],
                //     "grow" => $learninig['grow'],
                //     "note" => $learninig['note'],
                //     "id_personnel" => $user_id,
                //     "id_campagne" => $request->id_campagne
                // ]);
                $leadershipS = leadership::where('id_campagne', '=', $request->id_campagne)->where('id_personnel', '=', $user_id)->first();
                $leadershipS->accountability = $leadership['accountability'];
                $leadershipS->result = $leadership['result'];
                $leadershipS->best = $leadership['best'];
                $leadershipS->edge = $leadership['edge'];
                $leadershipS->note = $leadership['note'];
                $leadershipS->save();
                // leadership::create([
                //     "accountability" => $leadership['accountability'],
                //     "result" => $leadership['result'],
                //     "best" => $leadership['best'],
                //     "edge" => $leadership['edge'],
                //     "note" => $leadership['note'],
                //     "id_personnel" => $user_id,
                //     "id_campagne" => $request->id_campagne
                // ]);

                $cultureS = culture::where('id_campagne', '=', $request->id_campagne)->where('id_personnel', '=', $user_id)->first();
                $cultureS->strong = $culture['strong'];
                $cultureS->best = $culture['best'];
                $cultureS->team = $culture['team'];
                $cultureS->diversity = $culture['diversity'];
                $cultureS->reward = $culture['reward'];
                $cultureS->emotional = $culture['emotional'];
                $cultureS->note = $culture['note'];
                $cultureS->save();
                // culture::create([
                //     "strong" => $culture['strong'],
                //     "best" => $culture['best'],
                //     "team" => $culture['team'],
                //     "diversity" => $culture['diversity'],
                //     "reward" => $culture['reward'],
                //     "emotional" => $culture['emotional'],
                //     "note" => $culture['note'],
                //     "id_personnel" => $user_id,
                //     "id_campagne" => $request->id_campagne
                // ]);

                $cleanS = clean::where('id_campagne', '=', $request->id_campagne)->where('id_personnel', '=', $user_id)->first();
                $cleanS->office = $clean['office'];
                $cleanS->cars = $clean['cars'];
                $cleanS->employees = $clean['employees'];
                $cleanS->note = $clean['note'];
                $cleanS->save();
                // clean::create([
                //     "office" => $clean['office'],
                //     "cars" => $clean['cars'],
                //     "employees" => $clean['employees'],
                //     "note" => $clean['note'],
                //     "id_personnel" => $user_id,
                //     "id_campagne" => $request->id_campagne
                // ]);

                $noteQualite = ($living['note'] + $learninig['note'] + $leadership['note'] + $culture['note'] + $clean['note']);

                $total_objectif = 0;
                $total_notes = 0;
                foreach ($note_objectif as $note) {
                    $note = note::where('id_campagne', '=', $request->id_campagne)
                        ->where('id_objectif', '=', $note['id_objectif'])
                        ->where('id_personnel', '=', $user_id)->first();
                    $note->valeur = $note['valeur'];
                    $note->save();

                    $total_notes += $note['valeur'];
                    $total_objectif += 1;
                }

                $note_rendement = ($total_notes * 20) / ($total_objectif * 5);
                $note_rendement = ($note_rendement * 100) / 20;

                $synthese = new stdClass;
                $synthese->id_campagne = $request->id_campagne;
                $synthese->suivi_objectif = $note_rendement;
                $synthese->qualite_personnelle = $noteQualite;

                switch ($categorie) {
                    case 'CAT-X':
                        $poid1 = 60;
                        $poid2 = 40;
                        break;

                    case 'CAT-XI':
                        $poid1 = 50;
                        $poid2 = 50;
                        break;

                    case 'CAT-XII':
                        $poid1 = 40;
                        $poid2 = 60;
                        break;
                }

                $scoreObjectif = ($note_rendement * $poid1) / 100;
                $scoreQualite = ($noteQualite * $poid2) / 100;

                $synthese->scroreObjectif = $scoreObjectif;
                $synthese->scoreQualite = $scoreQualite;

                $scoreTotal = $scoreObjectif + $scoreQualite;

                // Déterminer l'appréciation en fonction du score total
                if ($scoreTotal >= 96 && $scoreTotal <= 100) {
                    $appreciation = 'Excellent';
                } else if ($scoreTotal >= 86 && $scoreTotal <= 95) {
                    $appreciation = 'Très Bien';
                } else if ($scoreTotal >= 76 && $scoreTotal <= 85) {
                    $appreciation = 'Bien';
                } else if ($scoreTotal >= 61 && $scoreTotal <= 75) {
                    $appreciation = 'Passable';
                } else if ($scoreTotal >= 50 && $scoreTotal <= 60) {
                    $appreciation = 'Faible';
                }

                $result = array();
                // Enrégistrement des syntèses
                $syntheses = synthese::where('id_campagne', '=', $request->id_campagne)->where('id_personnel', '=', $user_id)->get();
                foreach ($syntheses as $synth) {
                    $synth = synthese::find($synth->id);
                    $synth->delete();
                }
                $s1 = synthese::create([
                    "critere" => "Strategic result",
                    "note" => $note_rendement,
                    "note_max" => 100,
                    "poids" => $poid1,
                    "score" => $scoreObjectif,
                    "id_personnel" => $user_id,
                    "id_campagne" => $request->id_campagne
                ]);

                array_push($result, $s1);

                $s2 = synthese::create([
                    "critere" => "LEADERSHIP AND MANAGEMENT OBJECTIVES",
                    "note" => $noteQualite,
                    "note_max" => 100,
                    "poids" => $poid2,
                    "score" => $scoreQualite,
                    "id_personnel" => $user_id,
                    "id_campagne" => $request->id_campagne
                ]);

                array_push($result, $s2);

                // $rec = concerne::where('id_campagne', '=', $request->id_campagne)->where('id_personnel', '=', $user_id)->first();
                $rec->score = $scoreTotal;
                $rec->appreciation = $appreciation;
                $rec->save();

                return response()->json(["datas" => $result, "scoreTotal" => $scoreTotal, "appreciation" => $appreciation], 201);
            } catch (\Exception $e) {
                return response()->json(["message" => "Error : " . $e->getMessage()], 500);
            }
        } else {
            return response()->json(["message" => "UNAUTHORIZED"], 401);
        }
    }

    public function saveAvis(Request $request)
    {
        $request->validate([
            "commentaire" => "required",
            "souhait" => "required",
            "id_campagne" => "required"
        ]);

        $token = true;

        if ($token) {
            try {
                // Récupérer le payload du token
                // $payload = JWTAuth::getPayload($token);

                // Récupérer la valeur de la clé 'user_id' du payload
                // $compte = compte::find($payload->get('sub'));
                $user_id = $request->user_id;

                $commentaire = commentaire::create([
                    "commentaire" => $request->commentaire,
                    "id_personnel" => $user_id,
                    "id_campagne" => $request->id_campagne
                ]);

                $souhait = souhait::create([
                    "souhait" => $request->souhait,
                    "id_personnel" => $user_id,
                    "id_campagne" => $request->id_campagne
                ]);

                return response()->json(["datas" => "Evaluation terminée"], 200);
            } catch (\Exception $e) {
                return response()->json(["message" => "Error : " . $e->getMessage()], 500);
            }
        } else {
            return response()->json(["message" => "UNAUTHORIZED"], 401);
        }
    }

    public function getObjectifPerPersonnel(Request $request, $id)
    {
        $year = date('Y');
        $objectifs = DB::table('objectifs')
            // ->join('campagnes', 'objectifs.id_campagne', '=', 'campagnes.id')
            ->where('objectifs.id_personnel', '=', $id)
            ->whereYear('created_at', $year)
            // ->where('campagnes.statut', '=', 'En cours')
            ->select('objectifs.*')
            ->get();

        $result = array();
        $personnel = personnel::find($id);
        foreach ($objectifs as $objectif) {
            // $note = DB::table('notes')
            //     ->where('id_objectif', '=', $objectif->id)
            //     ->where('id_personnel', '=', $user_id)
            //     ->first();


            $newObjectif = new stdClass;
            $newObjectif->id = $objectif->id;
            $newObjectif->specifique = $objectif->specifique;
            $newObjectif->operationnel = $objectif->operationnel;
            $newObjectif->source = $objectif->source_collecte;
            $newObjectif->frequence = $objectif->frequence;
            $newObjectif->indicateur = $objectif->indicateur;
            $newObjectif->statut = $objectif->statut;
            $newObjectif->valeur = $objectif->valeur;
            $newObjectif->cible = $objectif->cible;
            $newObjectif->created_at = $objectif->created_at;

            array_push($result, $newObjectif);
        }
        return response()->json(["datas" => $result, "personnel" => $personnel], 200);
    }

    public function getEvaluatedPersonnel(Request $request)
    {

        try {

            $token = true;

            if ($token) {
                // Récupérer le payload du token
                // $payload = JWTAuth::getPayload($token);

                // Récupérer la valeur de la clé 'user_id' du payload
                // $compte = compte::find($payload->get('sub'));
                $user_id = $request->user_id;


                $year = date('Y');
                $syntheses = DB::table('concernes')
                    ->join('personnels as p1', 'concernes.id_personnel', '=', 'p1.id')
                    ->where('concernes.n1', '=', $user_id)
                    ->where('concernes.score', '!=', null)
                    ->whereYear('concernes.created_at', $year)
                    ->get();


                // $objectifs = objectif::all();
                if (count($syntheses) > 0) {
                    $result = array();
                    foreach ($syntheses as $score) {

                        $personnel = personnel::find($score->id_personnel);

                        $synthese = new stdClass;
                        $synthese->id = $score->id;
                        $synthese->nom = $personnel->nom_perso;
                        $synthese->prenom = $personnel->prenom_perso;
                        $synthese->score = $score->score;
                        $synthese->scoreFinal = $score->score_final;
                        $synthese->approuved = $score->approuved;
                        $synthese->id_campagne = $score->id_campagne;

                        array_push($result, $synthese);
                    }
                    return response()->json([
                        "datas" => $result,
                        "nbreObjectif" => count($result)
                    ], 200);
                }
                return response()->json(["message" => "Data not found"], 200);
            }
            return response()->json(["message" => "Token not found"], 401);
        } catch (\Throwable $th) {
            return response()->json(["message" => "Error : " . $th->getMessage()], 500);
        }
    }

    public function getEvaluatedPersonnel2(Request $request)
    {

        try {

            $token = true;

            if ($token) {
                // Récupérer le payload du token
                // $payload = JWTAuth::getPayload($token);

                // Récupérer la valeur de la clé 'user_id' du payload
                // $compte = compte::find($payload->get('sub'));
                $user_id = $request->user_id;


                $year = date('Y');
                $syntheses = DB::table('concernes')
                    ->join('personnels as p1', 'concernes.id_personnel', '=', 'p1.id')
                    ->where('concernes.n2', '=', $user_id)
                    ->where('concernes.score_final', '!=', null)
                    ->whereYear('concernes.created_at', $year)
                    ->get();


                // $objectifs = objectif::all();
                if (count($syntheses) > 0) {
                    $result = array();
                    foreach ($syntheses as $score) {

                        $personnel = personnel::find($score->id_personnel);

                        $synthese = new stdClass;
                        $synthese->id = $score->id;
                        $synthese->nom = $personnel->nom_perso;
                        $synthese->prenom = $personnel->prenom_perso;
                        $synthese->score = $score->score;
                        $synthese->approuved = $score->approuved;
                        $synthese->scoreFinal = $score->score_final;
                        $synthese->id_campagne = $score->id_campagne;

                        array_push($result, $synthese);
                    }
                    return response()->json([
                        "datas" => $result,
                        "nbreObjectif" => count($result)
                    ], 200);
                }
                return response()->json(["message" => "Data not found"], 200);
            }
            return response()->json(["message" => "Token not found"], 401);
        } catch (\Throwable $th) {
            return response()->json(["message" => "Error : " . $th->getMessage()], 500);
        }
    }


    public function evaluateN1(Request $request)
    {
        $request->validate([
            "note_objectif" => "required",
            "qualites" => "required",
            "discipline" => "required",
            "id_campagne" => "nullable",
            "id_personnel" => "nullable",
            "valeurs" => "nullable",
        ]);

        $token = true;

        if ($token) {
            try {
                // Récupérer le payload du token
                // $payload = JWTAuth::getPayload($token);

                // Récupérer la valeur de la clé 'user_id' du payload
                // $compte = compte::find($payload->get('sub'));
                $user_id = $request->user_id;

                $note_objectif = $request->note_objectif;
                $qualites = $request->qualites;
                $discipline = $request->discipline;

                $valeurs = $request->valeurs;

                foreach ($valeurs as $val) {
                    $objectif = objectif::find($val['id_objectif']);
                    $objectif->update([
                        "valeur" => $val['valeur']
                    ]);
                }

                $d = discipline::create([
                    "ponctualite" => $discipline['ponctualite'],
                    "presentation" => $discipline['presentation'],
                    "relation" => $discipline['relation'],
                    "id_personnel" => $request->id_personnel,
                    "responsable" => $user_id,
                    "id_campagne" => $request->id_campagne
                ]);

                $noteDiscipline = ($discipline['ponctualite'] + $discipline['presentation'] + $discipline['relation']);

                $q = qualite::create([
                    "creativite" => $qualites['creativite'],
                    "esprit_equipe" => $qualites['esprit_equipe'],
                    "adaptation" => $qualites['adaptation'],
                    "relation" => $qualites['relation'],
                    "communication" => $qualites['communication'],
                    "id_personnel" => $request->id_personnel,
                    "responsable" => $user_id,
                    "id_campagne" => $request->id_campagne
                ]);

                $noteQualite = ($qualites['creativite'] + $qualites['esprit_equipe'] + $qualites['adaptation'] + $qualites['relation'] + $qualites['communication']);

                $total_objectif = 0;
                $total_notes = 0;
                foreach ($note_objectif as $note) {
                    note::create([
                        "valeur" => $note['valeur'],
                        "id_objectif" => $note['id_objectif'],
                        "id_personnel" => $request->id_personnel,
                        "responsable" => $user_id,
                        "id_campagne" => $request->id_campagne
                    ]);

                    $total_notes += $note['valeur'];
                    $total_objectif += 1;
                }

                $note_rendement = ($total_notes * 20) / ($total_objectif * 5);

                $synthese = new stdClass;
                $synthese->id_campagne = $request->id_campagne;
                $synthese->suivi_objectif = $note_rendement;
                $synthese->qualite_personnelle = $noteQualite;
                $synthese->discipline = $noteDiscipline;

                $scoreObjectif = ($note_rendement / 20) * 70;
                $scoreQualite = ($noteQualite / 20) * 15;
                $scoreDiscipline = ($noteDiscipline / 12) * 15;

                $synthese->scroreObjectif = $scoreObjectif;
                $synthese->scoreQualite = $scoreQualite;
                $synthese->scoreDiscipline = $scoreDiscipline;

                $scoreTotal = $scoreDiscipline + $scoreObjectif + $scoreQualite;

                // Déterminer l'appréciation en fonction du score total
                if ($scoreTotal >= 96 && $scoreTotal <= 100) {
                    $appreciation = 'Excellent';
                } else if ($scoreTotal >= 86 && $scoreTotal <= 95) {
                    $appreciation = 'Très Bien';
                } else if ($scoreTotal >= 76 && $scoreTotal <= 85) {
                    $appreciation = 'Bien';
                } else if ($scoreTotal >= 61 && $scoreTotal <= 75) {
                    $appreciation = 'Passable';
                } else if ($scoreTotal >= 50 && $scoreTotal <= 60) {
                    $appreciation = 'Faible';
                }

                $result = array();
                // Enrégistrement des syntèses
                $s1 = synthese::create([
                    "critere" => "Suivi des objectifs",
                    "note" => $note_rendement,
                    "note_max" => 20,
                    "poids" => 70,
                    "score" => $scoreObjectif,
                    "id_personnel" => $request->id_personnel,
                    "responsable" => $user_id,
                    "id_campagne" => $request->id_campagne
                ]);

                array_push($result, $s1);

                $s2 = synthese::create([
                    "critere" => "Qualités personnelles et relationnelles",
                    "note" => $noteQualite,
                    "note_max" => 20,
                    "poids" => 15,
                    "score" => $scoreQualite,
                    "id_personnel" => $request->id_personnel,
                    "responsable" => $user_id,
                    "id_campagne" => $request->id_campagne
                ]);

                array_push($result, $s2);

                $s3 = synthese::create([
                    "critere" => "Discipline",
                    "note" => $noteDiscipline,
                    "note_max" => 20,
                    "poids" => 15,
                    "score" => $scoreDiscipline,
                    "id_personnel" => $user_id,
                    "id_campagne" => $request->id_campagne
                ]);

                array_push($result, $s3);

                $rec = concerne::where('id_campagne', '=', $request->id_campagne)->where('id_personnel', '=', $request->id_personnel)->first();
                $rec->score_final  = $scoreTotal;
                $rec->appreciation = $appreciation;
                $rec->save();

                return response()->json(["datas" => $result, "scoreTotal" => $scoreTotal, "appreciation" => $appreciation], 201);
            } catch (\Exception $e) {
                return response()->json(["message" => "Error : " . $e->getMessage()], 500);
            }
        } else {
            return response()->json(["message" => "UNAUTHORIZED"], 401);
        }
    }

    public function re_evaluateN1(Request $request)
    {
        $request->validate([
            "note_objectif" => "required",
            "qualites" => "required",
            "discipline" => "required",
            "id_campagne" => "nullable",
            "id_personnel" => "nullable",
            "valeurs" => "nullable",
        ]);

        $token = true;

        if ($token) {
            try {
                // Récupérer le payload du token
                // $payload = JWTAuth::getPayload($token);

                // Récupérer la valeur de la clé 'user_id' du payload
                // $compte = compte::find($payload->get('sub'));
                $user_id = $request->user_id;

                $note_objectif = $request->note_objectif;
                $qualites = $request->qualites;
                $discipline = $request->discipline;

                $valeurs = $request->valeurs;

                foreach ($valeurs as $val) {
                    $objectif = objectif::find($val['id_objectif']);
                    $objectif->update([
                        "valeur" => $val['valeur']
                    ]);
                }

                $d = discipline::where('id_campagne', '=', $request->id_campagne)->where('id_personnel', '=', $request->id_personnel)->where('responsable', '=', $user_id)->first();
                $d->update([
                    "ponctualite" => $discipline['ponctualite'],
                    "presentation" => $discipline['presentation'],
                    "relation" => $discipline['relation']
                    // "id_personnel" => $request->id_personnel,
                    // "responsable" => $user_id,
                    // "id_campagne" => $request->id_campagne
                ]);

                $noteDiscipline = ($discipline['ponctualite'] + $discipline['presentation'] + $discipline['relation']);

                $q = qualite::where('id_campagne', '=', $request->id_campagne)->where('id_personnel', '=', $request->id_personnel)->where('responsable', '=', $user_id)->first();
                $q->update([
                    "creativite" => $qualites['creativite'],
                    "esprit_equipe" => $qualites['esprit_equipe'],
                    "adaptation" => $qualites['adaptation'],
                    "relation" => $qualites['relation'],
                    "communication" => $qualites['communication']
                    // "id_personnel" => $request->id_personnel,
                    // "responsable" => $user_id,
                    // "id_campagne" => $request->id_campagne
                ]);

                $noteQualite = ($qualites['creativite'] + $qualites['esprit_equipe'] + $qualites['adaptation'] + $qualites['relation'] + $qualites['communication']);

                $total_objectif = 0;
                $total_notes = 0;
                foreach ($note_objectif as $note) {
                    $note = note::where('id_campagne', '=', $request->id_campagne)
                        ->where('id_objectif', '=', $note['id_objectif'])
                        ->where('id_personnel', '=', $request->id_personnel)
                        ->where('responsable', '=', $user_id)->first();
                    $note->valeur = $note['valeur'];
                    $note->save();

                    $total_notes += $note['valeur'];
                    $total_objectif += 1;
                }

                $note_rendement = ($total_notes * 20) / ($total_objectif * 5);

                $synthese = new stdClass;
                $synthese->id_campagne = $request->id_campagne;
                $synthese->suivi_objectif = $note_rendement;
                $synthese->qualite_personnelle = $noteQualite;
                $synthese->discipline = $noteDiscipline;

                $scoreObjectif = ($note_rendement / 20) * 70;
                $scoreQualite = ($noteQualite / 20) * 15;
                $scoreDiscipline = ($noteDiscipline / 12) * 15;

                $synthese->scroreObjectif = $scoreObjectif;
                $synthese->scoreQualite = $scoreQualite;
                $synthese->scoreDiscipline = $scoreDiscipline;

                $scoreTotal = $scoreDiscipline + $scoreObjectif + $scoreQualite;

                // Déterminer l'appréciation en fonction du score total
                if ($scoreTotal >= 96 && $scoreTotal <= 100) {
                    $appreciation = 'Excellent';
                } else if ($scoreTotal >= 86 && $scoreTotal <= 95) {
                    $appreciation = 'Très Bien';
                } else if ($scoreTotal >= 76 && $scoreTotal <= 85) {
                    $appreciation = 'Bien';
                } else if ($scoreTotal >= 61 && $scoreTotal <= 75) {
                    $appreciation = 'Passable';
                } else if ($scoreTotal >= 50 && $scoreTotal <= 60) {
                    $appreciation = 'Faible';
                }

                $result = array();
                // Enrégistrement des syntèses
                $syntheses = synthese::where('id_campagne', '=', $request->id_campagne)->where('id_personnel', '=', $request->id_personnel)->where('responsable', '=', $user_id)->get();
                foreach ($syntheses as $synth) {
                    $synth = synthese::find($synth->id);
                    $synth->delete();
                }
                $s1 = synthese::create([
                    "critere" => "Suivi des objectifs",
                    "note" => $note_rendement,
                    "note_max" => 20,
                    "poids" => 70,
                    "score" => $scoreObjectif,
                    "id_personnel" => $request->id_personnel,
                    "responsable" => $user_id,
                    "id_campagne" => $request->id_campagne
                ]);

                array_push($result, $s1);

                $s2 = synthese::create([
                    "critere" => "Qualités personnelles et relationnelles",
                    "note" => $noteQualite,
                    "note_max" => 20,
                    "poids" => 15,
                    "score" => $scoreQualite,
                    "id_personnel" => $request->id_personnel,
                    "responsable" => $user_id,
                    "id_campagne" => $request->id_campagne
                ]);

                array_push($result, $s2);

                $s3 = synthese::create([
                    "critere" => "Discipline",
                    "note" => $noteDiscipline,
                    "note_max" => 20,
                    "poids" => 15,
                    "score" => $scoreDiscipline,
                    "id_personnel" => $user_id,
                    "id_campagne" => $request->id_campagne
                ]);

                array_push($result, $s3);

                $rec = concerne::where('id_campagne', '=', $request->id_campagne)->where('id_personnel', '=', $request->id_personnel)->first();
                $rec->score_final  = $scoreTotal;
                $rec->appreciation = $appreciation;
                $rec->save();

                return response()->json(["datas" => $result, "scoreTotal" => $scoreTotal, "appreciation" => $appreciation], 201);
            } catch (\Exception $e) {
                return response()->json(["message" => "Error : " . $e->getMessage()], 500);
            }
        } else {
            return response()->json(["message" => "UNAUTHORIZED"], 401);
        }
    }


    public function evaluateN2(Request $request)
    {
        $request->validate([
            "note_objectif" => "required",
            "living" => "required",
            "learning" => "required",
            "leadership" => "required",
            "culture" => "required",
            "clean" => "required",
            "id_personnel" => "nullable",
            "id_campagne" => "nullable",
            "valeurs" => "nullable",
        ]);

        $token = true;

        if ($token) {
            try {
                // Récupérer le payload du token
                // $payload = JWTAuth::getPayload($token);

                // Récupérer la valeur de la clé 'user_id' du payload
                // $compte = compte::find($payload->get('sub'));
                $user_id = $request->user_id;

                $note_objectif = $request->note_objectif;
                $leadership = $request->leadership;
                $learninig = $request->learning;
                $living = $request->living;
                $culture = $request->culture;
                $clean = $request->clean;

                $valeurs = $request->valeurs;

                foreach ($valeurs as $val) {
                    $objectif = objectif::find($val['id_objectif']);
                    $objectif->update([
                        "valeur" => $val['valeur']
                    ]);
                }


                living::create([
                    "integrity" => $living['integrity'],
                    "courage" => $living['courage'],
                    "creativity" => $living['creativity'],
                    "value" => $living['value'],
                    "note" => $living['note'],
                    "id_personnel" => $request->id_personnel,
                    "responsable" => $user_id,
                    "id_campagne" => $request->id_campagne
                ]);

                learning::create([
                    "training" => $learninig['training'],
                    "capability" => $learninig['capability'],
                    "improvement" => $learninig['improvement'],
                    "grow" => $learninig['grow'],
                    "note" => $learninig['note'],
                    "id_personnel" => $request->id_personnel,
                    "responsable" => $user_id,
                    "id_campagne" => $request->id_campagne
                ]);

                leadership::create([
                    "accountability" => $leadership['accountability'],
                    "result" => $leadership['result'],
                    "best" => $leadership['best'],
                    "edge" => $leadership['edge'],
                    "note" => $leadership['note'],
                    "id_personnel" => $request->id_personnel,
                    "responsable" => $user_id,
                    "id_campagne" => $request->id_campagne
                ]);

                culture::create([
                    "trong" => $culture['strong'],
                    "best" => $culture['best'],
                    "team" => $culture['team'],
                    "diversity" => $culture['diversity'],
                    "reward" => $culture['reward'],
                    "emotional" => $culture['emotional'],
                    "note" => $culture['note'],
                    "id_personnel" => $request->id_personnel,
                    "responsable" => $user_id,
                    "id_campagne" => $request->id_campagne
                ]);

                clean::create([
                    "office" => $clean['office'],
                    "cars" => $clean['cars'],
                    "employees" => $clean['employees'],
                    "note" => $clean['note'],
                    "id_personnel" => $request->id_personnel,
                    "responsable" => $user_id,
                    "id_campagne" => $request->id_campagne
                ]);

                $noteQualite = ($living['note'] + $learninig['note'] + $leadership['note'] + $culture['note'] + $clean['note']);

                $total_objectif = 0;
                $total_notes = 0;
                foreach ($note_objectif as $note) {
                    note::create([
                        "valeur" => $note['valeur'],
                        "id_objectif" => $note['id_objectif'],
                        "id_personnel" => $request->id_personnel,
                        "responsable" => $user_id,
                        "id_campagne" => $request->id_campagne
                    ]);

                    $total_notes += $note['valeur'];
                    $total_objectif += 1;
                }

                $note_rendement = ($total_notes * 20) / ($total_objectif * 5);
                $note_rendement = ($note_rendement * 100) / 20;

                $synthese = new stdClass;
                $synthese->id_campagne = $request->id_campagne;
                $synthese->suivi_objectif = $note_rendement;
                $synthese->qualite_personnelle = $noteQualite;

                $p = personnel::find($request->id_personnel);
                $categorie = $p->categorie;

                switch ($categorie) {
                    case 'CAT-X':
                        $poid1 = 60;
                        $poid2 = 40;
                        break;

                    case 'CAT-XI':
                        $poid1 = 50;
                        $poid2 = 50;
                        break;

                    case 'CAT-XII':
                        $poid1 = 40;
                        $poid2 = 60;
                        break;
                }

                $scoreObjectif = ($note_rendement * $poid1) / 100;
                $scoreQualite = ($noteQualite * $poid2) / 100;

                $synthese->scroreObjectif = $scoreObjectif;
                $synthese->scoreQualite = $scoreQualite;

                $scoreTotal = $scoreObjectif + $scoreQualite;

                // Déterminer l'appréciation en fonction du score total
                if ($scoreTotal >= 96 && $scoreTotal <= 100) {
                    $appreciation = 'Excellent';
                } else if ($scoreTotal >= 86 && $scoreTotal <= 95) {
                    $appreciation = 'Très Bien';
                } else if ($scoreTotal >= 76 && $scoreTotal <= 85) {
                    $appreciation = 'Bien';
                } else if ($scoreTotal >= 61 && $scoreTotal <= 75) {
                    $appreciation = 'Passable';
                } else if ($scoreTotal >= 50 && $scoreTotal <= 60) {
                    $appreciation = 'Faible';
                }

                $result = array();
                // Enrégistrement des syntèses
                $s1 = synthese::create([
                    "critere" => "Strategic result",
                    "note" => $note_rendement,
                    "note_max" => 100,
                    "poids" => $poid1,
                    "score" => $scoreObjectif,
                    "id_personnel" => $request->id_personnel,
                    "responsable" => $user_id,
                    "id_campagne" => $request->id_campagne
                ]);

                array_push($result, $s1);

                $s2 = synthese::create([
                    "critere" => "LEADERSHIP AND MANAGEMENT OBJECTIVES",
                    "note" => $noteQualite,
                    "note_max" => 100,
                    "poids" => $poid2,
                    "score" => $scoreQualite,
                    "id_personnel" => $request->id_personnel,
                    "responsable" => $user_id,
                    "id_campagne" => $request->id_campagne
                ]);

                array_push($result, $s2);

                $rec = concerne::where('id_campagne', '=', $request->id_campagne)->where('id_personnel', '=', $request->id_personnel)->first();
                $rec->score_final = $scoreTotal;
                $rec->appreciation = $appreciation;
                $rec->save();

                return response()->json(["datas" => $result, "scoreTotal" => $scoreTotal, "appreciation" => $appreciation], 201);
            } catch (\Exception $e) {
                return response()->json(["message" => "Error : " . $e->getMessage()], 500);
            }
        } else {
            return response()->json(["message" => "UNAUTHORIZED"], 401);
        }
    }

    public function re_evaluateN2(Request $request)
    {
        $request->validate([
            "note_objectif" => "required",
            "living" => "required",
            "learning" => "required",
            "leadership" => "required",
            "culture" => "required",
            "clean" => "required",
            "id_personnel" => "nullable",
            "id_campagne" => "nullable",
            "valeurs" => "nullable",
        ]);

        $token = true;

        if ($token) {
            try {
                // Récupérer le payload du token
                // $payload = JWTAuth::getPayload($token);

                // Récupérer la valeur de la clé 'user_id' du payload
                // $compte = compte::find($payload->get('sub'));
                $user_id = $request->user_id;

                $note_objectif = $request->note_objectif;
                $leadership = $request->leadership;
                $learninig = $request->learning;
                $living = $request->living;
                $culture = $request->culture;
                $clean = $request->clean;

                $valeurs = $request->valeurs;

                foreach ($valeurs as $val) {
                    $objectif = objectif::find($val['id_objectif']);
                    $objectif->update([
                        "valeur" => $val['valeur']
                    ]);
                }

                $liv = living::where('id_campagne', '=', $request->id_campagne)->where('id_personnel', '=', $request->id_personnel)->where('responsable', '=', $user_id)->first();
                $liv->update([
                    "integrity" => $living['integrity'],
                    "courage" => $living['courage'],
                    "creativity" => $living['creativity'],
                    "value" => $living['value'],
                    "note" => $living['note']
                    // "id_personnel" => $request->id_personnel,
                    // "responsable" => $user_id,
                    // "id_campagne" => $request->id_campagne
                ]);

                $lear = learning::where('id_campagne', '=', $request->id_campagne)->where('id_personnel', '=', $request->id_personnel)->where('responsable', '=', $user_id)->first();
                $lear->update([
                    "training" => $learninig['training'],
                    "capability" => $learninig['capability'],
                    "improvement" => $learninig['improvement'],
                    "grow" => $learninig['grow'],
                    "note" => $learninig['note']
                    // "id_personnel" => $request->id_personnel,
                    // "responsable" => $user_id,
                    // "id_campagne" => $request->id_campagne
                ]);

                $le = leadership::where('id_campagne', '=', $request->id_campagne)->where('id_personnel', '=', $request->id_personnel)->where('responsable', '=', $user_id)->first();
                $le->update([
                    "accountability" => $leadership['accountability'],
                    "result" => $leadership['result'],
                    "best" => $leadership['best'],
                    "edge" => $leadership['edge'],
                    "note" => $leadership['note']
                    // "id_personnel" => $request->id_personnel,
                    // "responsable" => $user_id,
                    // "id_campagne" => $request->id_campagne
                ]);

                $cul = culture::where('id_campagne', '=', $request->id_campagne)->where('id_personnel', '=', $request->id_personnel)->where('responsable', '=', $user_id)->first();
                $cul->update([
                    "trong" => $culture['strong'],
                    "best" => $culture['best'],
                    "team" => $culture['team'],
                    "diversity" => $culture['diversity'],
                    "reward" => $culture['reward'],
                    "emotional" => $culture['emotional'],
                    "note" => $culture['note']
                    // "id_personnel" => $request->id_personnel,
                    // "responsable" => $user_id,
                    // "id_campagne" => $request->id_campagne
                ]);

                $cle = clean::where('id_campagne', '=', $request->id_campagne)->where('id_personnel', '=', $request->id_personnel)->where('responsable', '=', $user_id)->first();
                $cle->update([
                    "office" => $clean['office'],
                    "cars" => $clean['cars'],
                    "employees" => $clean['employees'],
                    "note" => $clean['note']
                    // "id_personnel" => $request->id_personnel,
                    // "responsable" => $user_id,
                    // "id_campagne" => $request->id_campagne
                ]);

                $noteQualite = ($living['note'] + $learninig['note'] + $leadership['note'] + $culture['note'] + $clean['note']);

                $total_objectif = 0;
                $total_notes = 0;
                foreach ($note_objectif as $note) {
                    $note = note::where('id_campagne', '=', $request->id_campagne)
                        ->where('id_objectif', '=', $note['id_objectif'])
                        ->where('id_personnel', '=', $request->id_personnel)
                        ->where('responsable', '=', $user_id)->first();
                    $note->valeur = $note['valeur'];
                    $note->save();

                    $total_notes += $note['valeur'];
                    $total_objectif += 1;
                }

                $note_rendement = ($total_notes * 20) / ($total_objectif * 5);
                $note_rendement = ($note_rendement * 100) / 20;

                $synthese = new stdClass;
                $synthese->id_campagne = $request->id_campagne;
                $synthese->suivi_objectif = $note_rendement;
                $synthese->qualite_personnelle = $noteQualite;

                $p = personnel::find($request->id_personnel);
                $categorie = $p->categorie;

                switch ($categorie) {
                    case 'CAT-X':
                        $poid1 = 60;
                        $poid2 = 40;
                        break;

                    case 'CAT-XI':
                        $poid1 = 50;
                        $poid2 = 50;
                        break;

                    case 'CAT-XII':
                        $poid1 = 40;
                        $poid2 = 60;
                        break;
                }

                $scoreObjectif = ($note_rendement * $poid1) / 100;
                $scoreQualite = ($noteQualite * $poid2) / 100;

                $synthese->scroreObjectif = $scoreObjectif;
                $synthese->scoreQualite = $scoreQualite;

                $scoreTotal = $scoreObjectif + $scoreQualite;

                // Déterminer l'appréciation en fonction du score total
                if ($scoreTotal >= 96 && $scoreTotal <= 100) {
                    $appreciation = 'Excellent';
                } else if ($scoreTotal >= 86 && $scoreTotal <= 95) {
                    $appreciation = 'Très Bien';
                } else if ($scoreTotal >= 76 && $scoreTotal <= 85) {
                    $appreciation = 'Bien';
                } else if ($scoreTotal >= 61 && $scoreTotal <= 75) {
                    $appreciation = 'Passable';
                } else if ($scoreTotal >= 50 && $scoreTotal <= 60) {
                    $appreciation = 'Faible';
                }

                $result = array();
                // Enrégistrement des syntèses
                $syntheses = synthese::where('id_campagne', '=', $request->id_campagne)->where('id_personnel', '=', $request->id_personnel)->where('responsable', '=', $user_id)->get();
                foreach ($syntheses as $synth) {
                    $synth = synthese::find($synth->id);
                    $synth->delete();
                }
                $s1 = synthese::create([
                    "critere" => "Strategic result",
                    "note" => $note_rendement,
                    "note_max" => 100,
                    "poids" => $poid1,
                    "score" => $scoreObjectif,
                    "id_personnel" => $request->id_personnel,
                    "responsable" => $user_id,
                    "id_campagne" => $request->id_campagne
                ]);

                array_push($result, $s1);

                $s2 = synthese::create([
                    "critere" => "LEADERSHIP AND MANAGEMENT OBJECTIVES",
                    "note" => $noteQualite,
                    "note_max" => 100,
                    "poids" => $poid2,
                    "score" => $scoreQualite,
                    "id_personnel" => $request->id_personnel,
                    "responsable" => $user_id,
                    "id_campagne" => $request->id_campagne
                ]);

                array_push($result, $s2);

                $rec = concerne::where('id_campagne', '=', $request->id_campagne)->where('id_personnel', '=', $request->id_personnel)->first();
                $rec->score_final = $scoreTotal;
                $rec->appreciation = $appreciation;
                $rec->save();

                return response()->json(["datas" => $result, "scoreTotal" => $scoreTotal, "appreciation" => $appreciation], 201);
            } catch (\Exception $e) {
                return response()->json(["message" => "Error : " . $e->getMessage()], 500);
            }
        } else {
            return response()->json(["message" => "UNAUTHORIZED"], 401);
        }
    }


    public function detailsCadre(Request $request)
    {
        try {

            $reslutObjectifs = array();
            $year = date('Y');
            $objectifs = DB::table('objectifs')
                ->where('objectifs.id_personnel', '=', $request->id_personnel)
                ->whereYear('created_at', $year)
                ->select('objectifs.*')
                ->get();
            foreach ($objectifs as $objectif) {
                $note = note::where("id_objectif", "=", $objectif->id)
                    ->where("id_personnel", "=", $request->id_personnel)
                    ->where("id_campagne", "=", $request->id_campagne)
                    ->first();

                $newObjectif = new stdClass;
                $newObjectif->id = $objectif->id;
                $newObjectif->specifique = $objectif->specifique;
                $newObjectif->operationnel = $objectif->operationnel;
                $newObjectif->indicateur = $objectif->indicateur;
                $newObjectif->valeur = $objectif->valeur;
                $newObjectif->cible = $objectif->cible;
                $newObjectif->frequence = $objectif->frequence;
                $newObjectif->sourceCollecte = $objectif->source_collecte;
                $newObjectif->note = $note->valeur;

                array_push($reslutObjectifs, $newObjectif);
            }

            $living = living::where('id_campagne', '=', $request->id_campagne)->where('id_personnel', '=', $request->id_personnel)->first();
            $learninig = learning::where('id_campagne', '=', $request->id_campagne)->where('id_personnel', '=', $request->id_personnel)->first();
            $leadership = leadership::where('id_campagne', '=', $request->id_campagne)->where('id_personnel', '=', $request->id_personnel)->first();
            $culture = culture::where('id_campagne', '=', $request->id_campagne)->where('id_personnel', '=', $request->id_personnel)->first();
            $clean = clean::where('id_campagne', '=', $request->id_campagne)->where('id_personnel', '=', $request->id_personnel)->first();
            $resultObject = new stdClass;
            $resultObject->objectifs = $reslutObjectifs;
            $resultObject->living = $living;
            $resultObject->learning = $learninig;
            $resultObject->leadership = $leadership;
            $resultObject->culture = $culture;
            $resultObject->clean = $clean;
            $questions = question::where("id_personnel", "=", $request->id_personnel)
                ->where("id_campagne", "=", $request->id_campagne)
                ->first();
            $resultObject->questions = $questions;
            $recommandations = recommandation::where("id_personnel", "=", $request->id_personnel)
                ->where("id_campagne", "=", $request->id_campagne)
                ->first();
            $resultObject->recommandations = $recommandations;
            $souhaits = souhait::where("id_personnel", "=", $request->id_personnel)
                ->where("id_campagne", "=", $request->id_campagne)
                ->first();
            $resultObject->souhaits = $souhaits;
            $c = commentaire::where("id_personnel", "=", $request->id_personnel)
                ->where("id_campagne", "=", $request->id_campagne)
                ->where("n1", "=", null)
                ->where("n2", "=", null)
                ->first();
            $d = commentaire::where("id_personnel", "=", $request->id_personnel)
                ->where("id_campagne", "=", $request->id_campagne)
                ->where("n1", "!=", null)
                ->first();
            $e = commentaire::where("id_personnel", "=", $request->id_personnel)
                ->where("id_campagne", "=", $request->id_campagne)
                ->where("n2", "!=", null)
                ->first();
            $tableComment = array();
            array_push($tableComment, $c);
            array_push($tableComment, $d);
            array_push($tableComment, $e);
            $resultObject->comments = $tableComment;

            return response()->json(["datas" => $resultObject], 200);
        } catch (\Exception $e) {
            return response()->json(["message" => "Error : " . $e->getMessage()], 500);
        }
    }

    public function getAutoCadre(Request $request)
    {
        try {

            $reslutObjectifs = array();
            $year = date('Y');
            $objectifs = DB::table('objectifs')
                ->where('objectifs.id_personnel', '=', $request->id_personnel)
                ->whereYear('created_at', $year)
                ->select('objectifs.*')
                ->get();
            foreach ($objectifs as $objectif) {
                $note = note::where("id_objectif", "=", $objectif->id)
                    ->where("id_personnel", "=", $request->id_personnel)
                    ->where("id_campagne", "=", $request->id_campagne)
                    ->first();

                $newObjectif = new stdClass;
                $newObjectif->id = $objectif->id;
                $newObjectif->specifique = $objectif->specifique;
                $newObjectif->operationnel = $objectif->operationnel;
                $newObjectif->indicateur = $objectif->indicateur;
                $newObjectif->valeur = $objectif->valeur;
                $newObjectif->cible = $objectif->cible;
                $newObjectif->frequence = $objectif->frequence;
                $newObjectif->sourceCollecte = $objectif->source_collecte;
                $newObjectif->note = $note->valeur;

                array_push($reslutObjectifs, $newObjectif);
            }

            $living = living::where('id_campagne', '=', $request->id_campagne)->where('id_personnel', '=', $request->id_personnel)->first();
            $learninig = learning::where('id_campagne', '=', $request->id_campagne)->where('id_personnel', '=', $request->id_personnel)->first();
            $leadership = leadership::where('id_campagne', '=', $request->id_campagne)->where('id_personnel', '=', $request->id_personnel)->first();
            $culture = culture::where('id_campagne', '=', $request->id_campagne)->where('id_personnel', '=', $request->id_personnel)->first();
            $clean = clean::where('id_campagne', '=', $request->id_campagne)->where('id_personnel', '=', $request->id_personnel)->first();
            $resultObject = new stdClass;
            $resultObject->objectifs = $reslutObjectifs;
            $resultObject->living = $living;
            $resultObject->learning = $learninig;
            $resultObject->leadership = $leadership;
            $resultObject->culture = $culture;
            $resultObject->clean = $clean;
            $questions = question::where("id_personnel", "=", $request->id_personnel)
                ->where("id_campagne", "=", $request->id_campagne)
                ->first();
            $resultObject->questions = $questions;
            $recommandations = recommandation::where("id_personnel", "=", $request->id_personnel)
                ->where("id_campagne", "=", $request->id_campagne)
                ->first();
            $resultObject->recommandations = $recommandations;
            $souhaits = souhait::where("id_personnel", "=", $request->id_personnel)
                ->where("id_campagne", "=", $request->id_campagne)
                ->first();
            $resultObject->souhaits = $souhaits;
            $c = commentaire::where("id_personnel", "=", $request->id_personnel)
                ->where("id_campagne", "=", $request->id_campagne)
                ->where("n1", "=", null)
                ->where("n2", "=", null)
                ->first();
            $d = commentaire::where("id_personnel", "=", $request->id_personnel)
                ->where("id_campagne", "=", $request->id_campagne)
                ->where("n1", "!=", null)
                ->first();
            $e = commentaire::where("id_personnel", "=", $request->id_personnel)
                ->where("id_campagne", "=", $request->id_campagne)
                ->where("n2", "!=", null)
                ->first();
            $tableComment = array();
            array_push($tableComment, $c);
            array_push($tableComment, $d);
            array_push($tableComment, $e);
            $resultObject->comments = $tableComment;
            $rec = concerne::where('id_campagne', '=', $request->id_campagne)->where('id_personnel', '=', $request->id_personnel)->first();
            $evaluateurs = new stdClass;
            $eval1 = new stdClass;
            $ev = personnel::find($rec->n1);
            $eval1->id = $ev->id;
            $eval1->nom = $ev->nom_perso . " " . $ev->prenom_perso;
            $evaluateurs->eval1 = $eval1;
            $eval2 = new stdClass;
            $ev2 = personnel::find($rec->n2);
            $eval2->id = $ev2->id;
            $eval2->nom = $ev2->nom_perso . " " . $ev2->prenom_perso;
            $evaluateurs->eval2 = $eval2;
            $resultObject->evaluateurs = $evaluateurs;

            return response()->json(["datas" => $resultObject], 200);
        } catch (\Exception $e) {
            return response()->json(["message" => "Error : " . $e->getMessage()], 500);
        }
    }

    public function getAuto(Request $request)
    {
        $request->validate([
            // "socre" => "required",
            "id_campagne" => "nullable",
            "id_personnel" => "nullable"
        ]);

        try {
            $result = new stdClass;
            $reslutObjectifs = array();
            $year = date('Y');
            $objectifs = DB::table('objectifs')
                ->where('objectifs.id_personnel', '=', $request->id_personnel)
                ->whereYear('created_at', $year)
                ->select('objectifs.*')
                ->get();
            foreach ($objectifs as $objectif) {
                $note = note::where("id_objectif", "=", $objectif->id)
                    ->where("id_personnel", "=", $request->id_personnel)
                    ->where("id_campagne", "=", $request->id_campagne)
                    ->first();

                $newObjectif = new stdClass;
                $newObjectif->id = $objectif->id;
                $newObjectif->specifique = $objectif->specifique;
                $newObjectif->operationnel = $objectif->operationnel;
                $newObjectif->indicateur = $objectif->indicateur;
                $newObjectif->valeur = $objectif->valeur;
                $newObjectif->cible = $objectif->cible;
                $newObjectif->frequence = $objectif->frequence;
                $newObjectif->sourceCollecte = $objectif->source_collecte;
                $newObjectif->note = $note->valeur;

                array_push($reslutObjectifs, $newObjectif);
            }


            $result->objectifs = $reslutObjectifs;

            $questions = question::where("id_personnel", "=", $request->id_personnel)
                ->where("id_campagne", "=", $request->id_campagne)
                ->first();
            $result->questions = $questions;
            $recommandations = recommandation::where("id_personnel", "=", $request->id_personnel)
                ->where("id_campagne", "=", $request->id_campagne)
                ->first();
            $result->recommandations = $recommandations;
            $souhaits = souhait::where("id_personnel", "=", $request->id_personnel)
                ->where("id_campagne", "=", $request->id_campagne)
                ->where("responsable", "=", null)
                ->first();
            $result->souhaits = $souhaits;
            $qualites = qualite::where("id_personnel", "=", $request->id_personnel)
                ->where("id_campagne", "=", $request->id_campagne)
                ->where("responsable", "=", null)->first();
            $result->qualites = $qualites;
            $disciplines = discipline::where("id_personnel", "=", $request->id_personnel)
                ->where("id_campagne", "=", $request->id_campagne)
                ->where("responsable", "=", null)->first();
            $result->discipline = $disciplines;
            $c = commentaire::where("id_personnel", "=", $request->id_personnel)
                ->where("id_campagne", "=", $request->id_campagne)
                ->where("n1", "=", null)
                ->where("n2", "=", null)
                ->first();
            $d = commentaire::where("id_personnel", "=", $request->id_personnel)
                ->where("id_campagne", "=", $request->id_campagne)
                ->where("n1", "!=", null)
                ->first();
            $e = commentaire::where("id_personnel", "=", $request->id_personnel)
                ->where("id_campagne", "=", $request->id_campagne)
                ->where("n2", "!=", null)
                ->first();
            $tableComment = array();
            array_push($tableComment, $c);
            array_push($tableComment, $d);
            array_push($tableComment, $e);
            $result->comments = $tableComment;
            $rec = concerne::where('id_campagne', '=', $request->id_campagne)->where('id_personnel', '=', $request->id_personnel)->first();
            $evaluateurs = new stdClass;
            $eval1 = new stdClass;
            $ev = personnel::find($rec->n1);
            $eval1->id = $ev->id;
            $eval1->nom = $ev->nom_perso . " " . $ev->prenom_perso;
            $evaluateurs->eval1 = $eval1;
            $eval2 = new stdClass;
            $ev2 = personnel::find($rec->n2);
            $eval2->id = $ev2->id;
            $eval2->nom = $ev2->nom_perso . " " . $ev2->prenom_perso;
            $evaluateurs->eval2 = $eval2;
            $result->evaluateurs = $evaluateurs;
            return response()->json(["datas" => $result], 200);
        } catch (\Exception $e) {
            return response()->json(["message" => "Error : " . $e->getMessage()], 500);
        }
    }



    public function rapportN1(Request $request)
    {
        try {
            $rec = concerne::where('id_campagne', '=', $request->id_campagne)->where('id_personnel', '=', $request->id_personnel)->first();

            $reslutObjectifs = array();
            $year = date('Y');
            $objectifs = DB::table('objectifs')
                ->where('objectifs.id_personnel', '=', $request->id_personnel)
                ->whereYear('created_at', $year)
                ->select('objectifs.*')
                ->get();
            foreach ($objectifs as $objectif) {
                $note = note::where("id_objectif", "=", $objectif->id)
                    ->where("id_personnel", "=", $request->id_personnel)
                    ->where("responsable", "=", $rec->n1)
                    ->where("id_campagne", "=", $request->id_campagne)
                    ->first();

                if ($note) {
                    $newObjectif = new stdClass;
                    $newObjectif->id = $objectif->id;
                    $newObjectif->specifique = $objectif->specifique;
                    $newObjectif->operationnel = $objectif->operationnel;
                    $newObjectif->indicateur = $objectif->indicateur;
                    $newObjectif->valeur = $objectif->valeur;
                    $newObjectif->cible = $objectif->cible;
                    $newObjectif->frequence = $objectif->frequence;
                    $newObjectif->sourceCollecte = $objectif->source_collecte;
                    $newObjectif->note = $note->valeur;

                    array_push($reslutObjectifs, $newObjectif);
                }
            }

            $living = living::where('id_campagne', '=', $request->id_campagne)->where('id_personnel', '=', $request->id_personnel)->where('responsable', '=', $rec->n1)->first();
            $learninig = learning::where('id_campagne', '=', $request->id_campagne)->where('id_personnel', '=', $request->id_personnel)->where('responsable', '=', $rec->n1)->first();
            $leadership = leadership::where('id_campagne', '=', $request->id_campagne)->where('id_personnel', '=', $request->id_personnel)->where('responsable', '=', $rec->n1)->first();
            $culture = culture::where('id_campagne', '=', $request->id_campagne)->where('id_personnel', '=', $request->id_personnel)->where('responsable', '=', $rec->n1)->first();
            $clean = clean::where('id_campagne', '=', $request->id_campagne)->where('id_personnel', '=', $request->id_personnel)->where('responsable', '=', $rec->n1)->first();
            $resultObject = new stdClass;
            $resultObject->objectifs = $reslutObjectifs;
            $resultObject->living = $living;
            $resultObject->learning = $learninig;
            $resultObject->leadership = $leadership;
            $resultObject->culture = $culture;
            $resultObject->clean = $clean;
            $questions = question::where("id_personnel", "=", $request->id_personnel)
                ->where("id_campagne", "=", $request->id_campagne)
                ->first();
            $resultObject->questions = $questions;
            $recommandations = recommandation::where("id_personnel", "=", $request->id_personnel)
                ->where("id_campagne", "=", $request->id_campagne)
                ->first();
            $resultObject->recommandations = $recommandations;
            $souhaits = souhait::where("id_personnel", "=", $request->id_personnel)
                ->where("id_campagne", "=", $request->id_campagne)
                ->first();
            $resultObject->souhaits = $souhaits;
            $c = commentaire::where("id_personnel", "=", $request->id_personnel)
                ->where("id_campagne", "=", $request->id_campagne)
                ->where("n1", "=", null)
                ->where("n2", "=", null)
                ->first();
            $d = commentaire::where("id_personnel", "=", $request->id_personnel)
                ->where("id_campagne", "=", $request->id_campagne)
                ->where("n1", "!=", null)
                ->first();
            $e = commentaire::where("id_personnel", "=", $request->id_personnel)
                ->where("id_campagne", "=", $request->id_campagne)
                ->where("n2", "!=", null)
                ->first();
            $tableComment = array();
            array_push($tableComment, $c);
            array_push($tableComment, $d);
            array_push($tableComment, $e);
            $resultObject->comments = $tableComment;
            return response()->json(["datas" => $resultObject], 200);
        } catch (\Exception $e) {
            return response()->json(["message" => "Error : " . $e->getMessage()], 500);
        }
    }

    public function rapportN2(Request $request)
    {
        try {
            $rec = concerne::where('id_campagne', '=', $request->id_campagne)->where('id_personnel', '=', $request->id_personnel)->first();

            $reslutObjectifs = array();
            $year = date('Y');
            $objectifs = DB::table('objectifs')
                ->where('objectifs.id_personnel', '=', $request->id_personnel)
                ->whereYear('created_at', $year)
                ->select('objectifs.*')
                ->get();
            foreach ($objectifs as $objectif) {
                $note = note::where("id_objectif", "=", $objectif->id)
                    ->where("id_personnel", "=", $request->id_personnel)
                    ->where("responsable", "=", $rec->n2)
                    ->where("id_campagne", "=", $request->id_campagne)
                    ->first();

                if ($note) {
                    $newObjectif = new stdClass;
                    $newObjectif->id = $objectif->id;
                    $newObjectif->specifique = $objectif->specifique;
                    $newObjectif->operationnel = $objectif->operationnel;
                    $newObjectif->indicateur = $objectif->indicateur;
                    $newObjectif->valeur = $objectif->valeur;
                    $newObjectif->cible = $objectif->cible;
                    $newObjectif->frequence = $objectif->frequence;
                    $newObjectif->sourceCollecte = $objectif->source_collecte;
                    $newObjectif->note = $note->valeur;

                    array_push($reslutObjectifs, $newObjectif);
                }
            }

            $living = living::where('id_campagne', '=', $request->id_campagne)->where('id_personnel', '=', $request->id_personnel)->where('responsable', '=', $rec->n2)->first();
            $learninig = learning::where('id_campagne', '=', $request->id_campagne)->where('id_personnel', '=', $request->id_personnel)->where('responsable', '=', $rec->n2)->first();
            $leadership = leadership::where('id_campagne', '=', $request->id_campagne)->where('id_personnel', '=', $request->id_personnel)->where('responsable', '=', $rec->n2)->first();
            $culture = culture::where('id_campagne', '=', $request->id_campagne)->where('id_personnel', '=', $request->id_personnel)->where('responsable', '=', $rec->n2)->first();
            $clean = clean::where('id_campagne', '=', $request->id_campagne)->where('id_personnel', '=', $request->id_personnel)->where('responsable', '=', $rec->n2)->first();
            $resultObject = new stdClass;
            $resultObject->objectifs = $reslutObjectifs;
            $resultObject->living = $living;
            $resultObject->learning = $learninig;
            $resultObject->leadership = $leadership;
            $resultObject->culture = $culture;
            $resultObject->clean = $clean;
            $questions = question::where("id_personnel", "=", $request->id_personnel)
                ->where("id_campagne", "=", $request->id_campagne)
                ->first();
            $resultObject->questions = $questions;
            $recommandations = recommandation::where("id_personnel", "=", $request->id_personnel)
                ->where("id_campagne", "=", $request->id_campagne)
                ->first();
            $resultObject->recommandations = $recommandations;
            $souhaits = souhait::where("id_personnel", "=", $request->id_personnel)
                ->where("id_campagne", "=", $request->id_campagne)
                ->first();
            $resultObject->souhaits = $souhaits;
            $c = commentaire::where("id_personnel", "=", $request->id_personnel)
                ->where("id_campagne", "=", $request->id_campagne)
                ->where("n1", "=", null)
                ->where("n2", "=", null)
                ->first();
            $d = commentaire::where("id_personnel", "=", $request->id_personnel)
                ->where("id_campagne", "=", $request->id_campagne)
                ->where("n1", "!=", null)
                ->first();
            $e = commentaire::where("id_personnel", "=", $request->id_personnel)
                ->where("id_campagne", "=", $request->id_campagne)
                ->where("n2", "!=", null)
                ->first();
            $tableComment = array();
            array_push($tableComment, $c);
            array_push($tableComment, $d);
            array_push($tableComment, $e);
            $resultObject->comments = $tableComment;
            return response()->json(["datas" => $resultObject], 200);
        } catch (\Exception $e) {
            return response()->json(["message" => "Error : " . $e->getMessage()], 500);
        }
    }


    public function saveAvisN1(Request $request)
    {
        $request->validate([
            "commentaire" => "required",
            // "souhait" => "required",
            "id_campagne" => "required",
            "id_personnel" => "required",
        ]);

        $token = true;

        if ($token) {
            try {
                // Récupérer le payload du token
                // $payload = JWTAuth::getPayload($token);

                // Récupérer la valeur de la clé 'user_id' du payload
                // $compte = compte::find($payload->get('sub'));
                $user_id = $request->user_id;

                $personnel = personnel::find($request->id_personnel);
                if ($personnel->evaluateN1 = $user_id) {
                    $commentaire = commentaire::create([
                        "commentaire" => $request->commentaire,
                        "n1" => $user_id,
                        "id_campagne" => $request->id_campagne,
                        "id_personnel" => $request->id_personnel
                    ]);
                } else {
                    $commentaire = commentaire::create([
                        "commentaire" => $request->commentaire,
                        "n2" => $user_id,
                        "id_campagne" => $request->id_campagne,
                        "id_personnel" => $request->id_personnel
                    ]);
                }

                // $souhait = souhait::create([
                //     "souhait" => $request->souhait,
                //     "responsable" => $user_id,
                //     "id_personnel" => $request->id_personnel,
                //     "id_campagne" => $request->id_campagne
                // ]);

                return response()->json(["datas" => "Evaluation terminée"], 200);
            } catch (\Exception $e) {
                return response()->json(["message" => "Error : " . $e->getMessage()], 500);
            }
        } else {
            return response()->json(["message" => "UNAUTHORIZED"], 401);
        }
    }

    public function saveR(Request $request)
    {
        $request->validate([
            "commentaire" => "required",
            "souhait" => "required",
            "id_campagne" => "required",
            "id_personnel" => "required",
        ]);

        $token = true;

        if ($token) {
            try {
                // Récupérer le payload du token
                // $payload = JWTAuth::getPayload($token);

                // Récupérer la valeur de la clé 'user_id' du payload
                // $compte = compte::find($payload->get('sub'));
                $user_id = $request->user_id;
                $personnel = personnel::find($user_id);

                commentaire::create([
                    "commentaire" => $request->commentaire,
                    "n2" => $user_id,
                    "id_campagne" => $request->id_campagne,
                    "id_personnel" => $request->id_personnel
                ]);

                recommandation::create([
                    "valeur" => $request->souhait,
                    "responsable" => $user_id,
                    "fonction" => $personnel->fonction,
                    "id_personnel" => $request->id_personnel,
                    "id_campagne" => $request->id_campagne
                ]);

                return response()->json(["datas" => "Evaluation terminée"], 200);
            } catch (\Exception $e) {
                return response()->json(["message" => "Error : " . $e->getMessage()], 500);
            }
        } else {
            return response()->json(["message" => "UNAUTHORIZED"], 401);
        }
    }


    public function recommandated(Request $request)
    {
        $request->validate([
            "recommandation" => "required",
            "id_campagne" => "required",
            "id_personnel" => "required",
        ]);

        $token = true;

        if ($token) {
            try {
                // Récupérer le payload du token
                // $payload = JWTAuth::getPayload($token);

                // Récupérer la valeur de la clé 'user_id' du payload
                // $compte = compte::find($payload->get('sub'));
                $user_id = $request->user_id;
                $personnel = personnel::find($user_id);

                recommandation::create([
                    "valeur" => $request->recommandation,
                    "responsable" => $user_id,
                    "fonction" => $personnel->fonction,
                    "id_personnel" => $request->id_personnel,
                    "id_campagne" => $request->id_campagne
                ]);

                return response()->json(["datas" => "Recommandation effectuée"], 200);
            } catch (\Exception $e) {
                return response()->json(["message" => "Error : " . $e->getMessage()], 500);
            }
        } else {
            return response()->json(["message" => "UNAUTHORIZED"], 401);
        }
    }

    public function approuved(Request $request)
    {
        $request->validate([
            // "socre" => "required",
            "id_campagne" => "nullable",
            "id_personnel" => "nullable"
        ]);
        $token = true;
        try {
            // Récupérer le payload du token
            // $payload = JWTAuth::getPayload($token);

            // Récupérer la valeur de la clé 'user_id' du payload
            // $compte = compte::find($payload->get('sub'));

            $campagne = concerne::where('id_personnel', '=', $request->id_personnel)
                ->where('id_campagne', '=', $request->id_campagne)
                ->first();
            $campagne->approuved = 'true';
            $campagne->save();
            return response()->json(["message" => $campagne], 200);
        } catch (\Exception $e) {
            return response()->json(["message" => "Error : " . $e->getMessage()], 500);
        }
    }

    public function disapprouved(Request $request)
    {
        $request->validate([
            // "socre" => "required",
            "id_campagne" => "nullable",
            "id_personnel" => "nullable"
        ]);
        $token = true;
        try {
            // Récupérer le payload du token
            // $payload = JWTAuth::getPayload($token);

            // Récupérer la valeur de la clé 'user_id' du payload
            // $compte = compte::find($payload->get('sub'));

            $campagne = concerne::where('id_personnel', '=', $request->id_personnel)
                ->where('id_campagne', '=', $request->id_campagne)
                ->first();
            $campagne->approuved = 'false';
            $campagne->save();
            return response()->json(["message" => $campagne], 200);
        } catch (\Exception $e) {
            return response()->json(["message" => "Error : " . $e->getMessage()], 500);
        }
    }

    public function report(Request $request)
    {
        $request->validate([
            // "socre" => "required",
            "id_campagne" => "nullable",
            "id_personnel" => "nullable"
        ]);

        try {
            $result = new stdClass;
            $reslutObjectifs = array();
            $year = date('Y');
            $objectifs = DB::table('objectifs')
                ->where('objectifs.id_personnel', '=', $request->id_personnel)
                ->whereYear('created_at', $year)
                ->select('objectifs.*')
                ->get();
            foreach ($objectifs as $objectif) {
                $note = note::where("id_objectif", "=", $objectif->id)
                    ->where("id_personnel", "=", $request->id_personnel)
                    ->where("id_campagne", "=", $request->id_campagne)
                    ->first();

                $newObjectif = new stdClass;
                $newObjectif->id = $objectif->id;
                $newObjectif->specifique = $objectif->specifique;
                $newObjectif->operationnel = $objectif->operationnel;
                $newObjectif->indicateur = $objectif->indicateur;
                $newObjectif->valeur = $objectif->valeur;
                $newObjectif->cible = $objectif->cible;
                $newObjectif->frequence = $objectif->frequence;
                $newObjectif->sourceCollecte = $objectif->source_collecte;
                $newObjectif->note = $note->valeur;

                array_push($reslutObjectifs, $newObjectif);
            }


            $result->objectifs = $reslutObjectifs;

            $questions = question::where("id_personnel", "=", $request->id_personnel)
                ->where("id_campagne", "=", $request->id_campagne)
                ->first();
            $result->questions = $questions;
            $recommandations = recommandation::where("id_personnel", "=", $request->id_personnel)
                ->where("id_campagne", "=", $request->id_campagne)
                ->first();
            $result->recommandations = $recommandations;
            $souhaits = souhait::where("id_personnel", "=", $request->id_personnel)
                ->where("id_campagne", "=", $request->id_campagne)
                ->where("responsable", "=", null)
                ->first();
            $result->souhaits = $souhaits;
            $qualites = qualite::where("id_personnel", "=", $request->id_personnel)
                ->where("id_campagne", "=", $request->id_campagne)
                ->where("responsable", "=", null)->first();
            $result->qualites = $qualites;
            $disciplines = discipline::where("id_personnel", "=", $request->id_personnel)
                ->where("id_campagne", "=", $request->id_campagne)
                ->where("responsable", "=", null)->first();
            $result->discipline = $disciplines;
            $c = commentaire::where("id_personnel", "=", $request->id_personnel)
                ->where("id_campagne", "=", $request->id_campagne)
                ->where("n1", "=", null)
                ->where("n2", "=", null)
                ->first();
            $d = commentaire::where("id_personnel", "=", $request->id_personnel)
                ->where("id_campagne", "=", $request->id_campagne)
                ->where("n1", "!=", null)
                ->first();
            $e = commentaire::where("id_personnel", "=", $request->id_personnel)
                ->where("id_campagne", "=", $request->id_campagne)
                ->where("n2", "!=", null)
                ->first();
            $tableComment = array();
            array_push($tableComment, $c);
            array_push($tableComment, $d);
            array_push($tableComment, $e);
            $result->comments = $tableComment;
            return response()->json(["datas" => $result], 200);
        } catch (\Exception $e) {
            return response()->json(["message" => "Error : " . $e->getMessage()], 500);
        }
    }

    public function reportE(Request $request)
    {
        $request->validate([
            // "socre" => "required",
            "id_campagne" => "nullable",
            "id_personnel" => "nullable"
        ]);

        try {

            $rec = concerne::where('id_campagne', '=', $request->id_campagne)->where('id_personnel', '=', $request->id_personnel)->first();

            $result = new stdClass;
            $reslutObjectifs = array();
            $year = date('Y');
            $objectifs = DB::table('objectifs')
                ->where('objectifs.id_personnel', '=', $request->id_personnel)
                ->whereYear('created_at', $year)
                ->select('objectifs.*')
                ->get();
            foreach ($objectifs as $objectif) {
                $note = note::where("id_objectif", "=", $objectif->id)
                    ->where("id_personnel", "=", $request->id_personnel)
                    ->where("responsable", "=", $rec->n1)
                    ->where("id_campagne", "=", $request->id_campagne)
                    ->first();

                if ($note) {
                    $newObjectif = new stdClass;
                    $newObjectif->id = $objectif->id;
                    $newObjectif->specifique = $objectif->specifique;
                    $newObjectif->operationnel = $objectif->operationnel;
                    $newObjectif->indicateur = $objectif->indicateur;
                    $newObjectif->valeur = $objectif->valeur;
                    $newObjectif->cible = $objectif->cible;
                    $newObjectif->frequence = $objectif->frequence;
                    $newObjectif->sourceCollecte = $objectif->source_collecte;
                    $newObjectif->note = $note->valeur;

                    array_push($reslutObjectifs, $newObjectif);
                }
            }


            $result->objectifs = $reslutObjectifs;

            $questions = question::where("id_personnel", "=", $request->id_personnel)
                ->where("id_campagne", "=", $request->id_campagne)
                ->first();
            $result->questions = $questions;
            $recommandations = recommandation::where("id_personnel", "=", $request->id_personnel)
                ->where("id_campagne", "=", $request->id_campagne)
                ->first();
            $result->recommandations = $recommandations;
            $souhaits = souhait::where("id_personnel", "=", $request->id_personnel)
                ->where("id_campagne", "=", $request->id_campagne)
                ->first();
            $result->souhaits = $souhaits;
            $qualites = qualite::where("id_personnel", "=", $request->id_personnel)
                ->where("id_campagne", "=", $request->id_campagne)
                ->where("responsable", "=", $rec->n1)->first();
            $result->qualites = $qualites;
            $disciplines = discipline::where("id_personnel", "=", $request->id_personnel)
                ->where("id_campagne", "=", $request->id_campagne)
                ->where("responsable", "=", $rec->n1)->first();
            $result->discipline = $disciplines;
            $c = commentaire::where("id_personnel", "=", $request->id_personnel)
                ->where("id_campagne", "=", $request->id_campagne)
                ->where("n1", "=", null)
                ->where("n2", "=", null)
                ->first();
            $d = commentaire::where("id_personnel", "=", $request->id_personnel)
                ->where("id_campagne", "=", $request->id_campagne)
                ->where("n1", "!=", null)
                ->first();
            $e = commentaire::where("id_personnel", "=", $request->id_personnel)
                ->where("id_campagne", "=", $request->id_campagne)
                ->where("n2", "!=", null)
                ->first();
            $tableComment = array();
            array_push($tableComment, $c);
            array_push($tableComment, $d);
            array_push($tableComment, $e);
            $result->comments = $tableComment;
            return response()->json(["datas" => $result], 200);
        } catch (\Exception $e) {
            return response()->json(["message" => "Error : " . $e->getMessage()], 500);
        }
    }

    public function reportEN2(Request $request)
    {
        $request->validate([
            // "socre" => "required",
            "id_campagne" => "nullable",
            "id_personnel" => "nullable"
        ]);

        try {
            $rec = concerne::where('id_campagne', '=', $request->id_campagne)->where('id_personnel', '=', $request->id_personnel)->first();

            $result = new stdClass;
            $reslutObjectifs = array();
            $year = date('Y');
            $objectifs = DB::table('objectifs')
                ->where('objectifs.id_personnel', '=', $request->id_personnel)
                ->whereYear('created_at', $year)
                ->select('objectifs.*')
                ->get();
            foreach ($objectifs as $objectif) {
                $note = note::where("id_objectif", "=", $objectif->id)
                    ->where("id_personnel", "=", $request->id_personnel)
                    ->where("responsable", "=", $rec->n2)
                    ->where("id_campagne", "=", $request->id_campagne)
                    ->first();

                if ($note) {
                    $newObjectif = new stdClass;
                    $newObjectif->id = $objectif->id;
                    $newObjectif->specifique = $objectif->specifique;
                    $newObjectif->operationnel = $objectif->operationnel;
                    $newObjectif->indicateur = $objectif->indicateur;
                    $newObjectif->valeur = $objectif->valeur;
                    $newObjectif->cible = $objectif->cible;
                    $newObjectif->frequence = $objectif->frequence;
                    $newObjectif->sourceCollecte = $objectif->source_collecte;
                    $newObjectif->note = $note->valeur;

                    array_push($reslutObjectifs, $newObjectif);
                }
            }


            $result->objectifs = $reslutObjectifs;

            $questions = question::where("id_personnel", "=", $request->id_personnel)
                ->where("id_campagne", "=", $request->id_campagne)
                ->first();
            $result->questions = $questions;
            $recommandations = recommandation::where("id_personnel", "=", $request->id_personnel)
                ->where("id_campagne", "=", $request->id_campagne)
                ->first();
            $result->recommandations = $recommandations;
            $souhaits = souhait::where("id_personnel", "=", $request->id_personnel)
                ->where("id_campagne", "=", $request->id_campagne)
                ->first();
            $result->souhaits = $souhaits;
            $qualites = qualite::where("id_personnel", "=", $request->id_personnel)
                ->where("id_campagne", "=", $request->id_campagne)
                ->where("responsable", "=", $rec->n2)->first();
            $result->qualites = $qualites;
            $disciplines = discipline::where("id_personnel", "=", $request->id_personnel)
                ->where("id_campagne", "=", $request->id_campagne)
                ->where("responsable", "=", $rec->n2)->first();
            $result->discipline = $disciplines;
            $c = commentaire::where("id_personnel", "=", $request->id_personnel)
                ->where("id_campagne", "=", $request->id_campagne)
                ->where("n1", "=", null)
                ->where("n2", "=", null)
                ->first();
            $d = commentaire::where("id_personnel", "=", $request->id_personnel)
                ->where("id_campagne", "=", $request->id_campagne)
                ->where("n1", "!=", null)
                ->first();
            $e = commentaire::where("id_personnel", "=", $request->id_personnel)
                ->where("id_campagne", "=", $request->id_campagne)
                ->where("n2", "!=", null)
                ->first();
            $tableComment = array();
            array_push($tableComment, $c);
            array_push($tableComment, $d);
            array_push($tableComment, $e);
            $result->comments = $tableComment;
            return response()->json(["datas" => $result], 200);
        } catch (\Exception $e) {
            return response()->json(["message" => "Error : " . $e->getMessage()], 500);
        }
    }


    public function getPersonnelPerDirection(Request $request)
    {
        try {

            $token = true;

            if ($token) {
                // Récupérer le payload du token
                // $payload = JWTAuth::getPayload($token);

                // Récupérer la valeur de la clé 'user_id' du payload
                // $compte = compte::find($payload->get('sub'));
                $user_id = $request->user_id;


                $year = date('Y');
                $syntheses = DB::table('concernes')
                    ->join('personnels as p1', 'concernes.id_personnel', '=', 'p1.id')
                    ->where('concernes.score_final', '!=', null)
                    ->whereYear('concernes.created_at', $year)
                    ->get();


                // $objectifs = objectif::all();
                if (count($syntheses) > 0) {
                    $result = array();
                    foreach ($syntheses as $score) {

                        $personnel = personnel::find($score->id_personnel);

                        $synthese = new stdClass;
                        $synthese->id = $score->id;
                        $synthese->nom = $personnel->nom_perso;
                        $synthese->prenom = $personnel->prenom_perso;
                        $synthese->sexe = $personnel->sexe_perso;
                        $synthese->direction = $personnel->direction;
                        $synthese->poste = $personnel->structure_perso;
                        $synthese->service = $personnel->structure_rattachee;
                        $synthese->categorie = $personnel->categorie;
                        $synthese->scoreFinal = $score->score_final;
                        $synthese->appreciation = $score->appreciation;
                        $synthese->approuved = $score->approuved;
                        $synthese->id_campagne = $score->id_campagne;

                        array_push($result, $synthese);
                    }
                    return response()->json([
                        "datas" => $result,
                        "nbreObjectif" => count($result)
                    ], 200);
                }
                return response()->json(["message" => "Data not found"], 200);
            }
            return response()->json(["message" => "Token not found"], 401);
        } catch (\Throwable $th) {
            return response()->json(["message" => "Error : " . $th->getMessage()], 500);
        }
    }

    public function getAvis(Request $request)
    {
        $avis = commentaire::where("id_personnel", "=", $request->user_id)
            ->where("n1", "=", null)
            ->where("n2", "=", null)
            ->where("id_campagne", "=", $request->id_campagne)->get();
        if ($avis) {
            return response()->json(["comments" => count($avis)], 200);
        } else {
            return response()->json(["comments" => 0], 200);
        }
    }

    public function getAvisN1(Request $request)
    {
        $avis = commentaire::where("id_personnel", "=", $request->id_personnel)
            ->where("n1", "=", $request->user_id)
            ->where("id_campagne", "=", $request->id_campagne)->get();
        if ($avis) {
            return response()->json(["comments" => count($avis)], 200);
        } else {
            return response()->json(["comments" => 0], 200);
        }
    }

    public function getRecom(Request $request)
    {
        $recommandation = recommandation::where("id_personnel", "=", $request->id_personnel)
            ->where("responsable", "=", $request->user_id)
            ->where("id_campagne", "=", $request->id_campagne)->first();
        $comment = commentaire::where("id_personnel", "=", $request->id_personnel)
            ->where("n2", "=", $request->user_id)
            ->where("id_campagne", "=", $request->id_campagne)->first();
        $result = new stdClass;
        $result->recommandation = $recommandation;
        $result->comment = $comment;
        if ($recommandation) {
            return response()->json(["datas" => $result], 200);
        } else {
            return response()->json(["datas" => "Data not found"], 200);
        }
    }

    public function getRecomPersonnel(Request $request)
    {
        $recommandation = recommandation::where("id_personnel", "=", $request->id_personnel)
            ->where("id_campagne", "=", $request->id_campagne)->get();
        if ($recommandation) {
            return response()->json(["datas" => $recommandation], 200);
        } else {
            return response()->json(["datas" => "Data not found"], 200);
        }
    }

    public function updateRecommandation(Request $request)
    {
        $recommandation = recommandation::find($request->id_recommandation);
        $recommandation->update([
            "valeur" => $request->recommandation
        ]);
        $comment = commentaire::find($request->id_comment);
        $comment->update([
            "commentaire" => $request->comment
        ]);
        return response()->json(["datas" => $recommandation], 200);
    }

    public function getRecommandation(Request $request)
    {
        try {
            $recommandations = recommandation::where("id_personnel", "=", $request->id_personnel)
                ->where("id_campagne", "=", $request->id_campagne)->get();
            return response()->json(["datas" => $recommandations], 200);
        } catch (\Exception $e) {
            return response()->json(["message" => "Error : " . $e->getMessage()], 500);
        }
    }
}
