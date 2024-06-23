<?php

namespace App\Http\Controllers;

use App\Models\campagne;
use App\Models\commentaire;
use App\Models\concerne;
use App\Models\personnel;
use App\Models\recommandation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use stdClass;

class CampagneController extends Controller
{
    public function getAll()
    {
        try {
            $campagnes = campagne::all();
            if (count($campagnes) > 0) {
                return response()->json([
                    'datas' => $campagnes,
                ], 200);
            }
            return response()->json(["message" => "Data not found"], 200);
        } catch (\Throwable $th) {
            return response()->json(["message" => "Error : " . $th->getMessage()], 500);
        }
    }

    public function getOne(Request $request, $id)
    {
        try {
            $campagne = campagne::find($id);
            if ($campagne) {
                return response()->json([
                    'datas' => $campagne,
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
            "titre" => "required",
            "description" => "required",
            "auto_eval" => "required",
            "eval" => "required",
            "ajustement" => "required",
            "date_debut" => "required",
            "date_fin" => "required"
        ]);

        try {
            $campagne = campagne::create([
                "titre" => $request->titre,
                "description" => $request->description,
                "auto_eval" => $request->auto_eval,
                "eval" => $request->eval,
                "ajustement" => $request->ajustement,
                "date_debut" => $request->date_debut,
                "date_fin" => $request->date_fin,
                "statut" => "En cours"
            ]);
            return response()->json(["message" => "Campagne enregistrée", "datas" => $campagne], 201);
        } catch (\Throwable $th) {
            return response()->json(["message" => "Error : " . $th->getMessage()], 500);
        }
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            "titre" => "required",
            "description" => "required",
            "auto_eval" => "required",
            "eval" => "required",
            "ajustement" => "required",
            "date_debut" => "required",
            "date_fin" => "required"
        ]);

        try {
            $campagne = campagne::find($id);
            if ($campagne) {
                if ($request->statut) {
                    $campagne->update([
                        "titre" => $request->titre,
                        "description" => $request->description,
                        "auto_eval" => $request->auto_eval,
                        "eval" => $request->eval,
                        "ajustement" => $request->ajustement,
                        "date_debut" => $request->date_debut,
                        "date_fin" => $request->date_fin,
                        "statut" => $request->statut
                    ]);
                }
                $campagne->update([
                    "titre" => $request->titre,
                    "description" => $request->description,
                    "date_debut" => $request->date_debut,
                    "date_fin" => $request->date_fin
                ]);
                return response()->json(["message" => "Campagne mise à jour", "datas" => $campagne], 200);
            }
            return response()->json(["message" => "Data not found"], 200);
        } catch (\Throwable $th) {
            return response()->json(["message" => "Error : " . $th->getMessage()], 500);
        }
    }

    public function delete($id)
    {
        try {
            $campagne = campagne::find($id);
            DB::table('concernes')->where('id_campagne', '=', $campagne->id)->delete();
            if ($campagne) {
                $campagne->delete();
                return response()->json(["message" => "Campagne supprimée"], 200);
            }
        } catch (\Throwable $th) {
            return response()->json(["message" => "Error : " . $th->getMessage()], 500);
        }
    }

    public function addEmployeToCampagne(Request $request)
    {
        $request->validate(["employes" => "required"]);

        try {
            $employes = $request->employes;
            foreach ($employes as $emp) {

                concerne::create([
                    "id_campagne" => $request->id_campagne,
                    "id_personnel" => $emp['id_personnel']
                ]);
            }
            return response()->json(["message" => "Liste des employés ajouté à la campagne d'évaluation"], 200);
        } catch (\Throwable $th) {
            return response()->json(["message" => "Error : " . $th->getMessage()], 500);
        }
    }

    public function updateEmployeToCampagne(Request $request, $id)
    {
        $request->validate(["employes" => "required"]);

        try {
            $employes = $request->employes;
            DB::table('concernes')->where('id_campagne', '=', $id)->delete();
            foreach ($employes as $emp) {

                concerne::create([
                    "id_campagne" => $emp['id_campagne'],
                    "id_personnel" => $emp['id_personnel']
                ]);
            }
            return response()->json(["message" => "Liste des employés ajouté à la campagne d'évaluation"], 200);
        } catch (\Throwable $th) {
            return response()->json(["message" => "Error : " . $th->getMessage()], 500);
        }
    }

    public function personnelParCampagne(Request $request, $id_campagne)
    {
        try {
            $datas = DB::table('personnels')
                ->join('concernes as c', 'personnels.id', '=', 'c.id_personnel')
                ->where('c.id_campagne', '=', $id_campagne)
                ->get();

            return response()->json(["datas" => $datas], 200);
        } catch (\Throwable $th) {
            return response()->json(["message" => "Error : " . $th->getMessage()], 500);
        }
    }

    public function reporting()
    {
        try {
            $currentYear = date('Y');

            $result = array();

            $campagnes = DB::table('campagnes')
                ->join('concernes', 'campagnes.id', '=', 'concernes.id_campagne')
                ->join('personnels', 'concernes.id_personnel', '=', 'personnels.id')
                ->where('concernes.score_final', '!=', null)
                ->where('concernes.approuved', '=', 'true')
                ->whereYear('concernes.created_at', $currentYear)
                ->select(
                    DB::raw("YEAR(campagnes.created_at) as annee"),
                    'personnels.id as id_personnel',
                    DB::raw("AVG(concernes.score_final) as moyenne"),
                )
                ->groupBy('annee', 'personnels.id')
                ->get();


            foreach ($campagnes as $cam) {
                $object = new stdClass;
                $personnel = personnel::find($cam->id_personnel);

                $dernierCampagne = DB::table('campagnes')
                    ->select('campagnes.id')
                    ->join('concernes', 'campagnes.id', '=', 'concernes.id_campagne')
                    ->where('concernes.id_personnel', $personnel->id)
                    ->latest('campagnes.created_at')
                    ->first();

                $scores = DB::table('concernes')
                    ->join('campagnes', 'concernes.id_campagne', '=', 'campagnes.id')
                    ->where('concernes.id_personnel', '=', $personnel->id)
                    ->whereNotNull('concernes.score_final')
                    ->whereYear('concernes.created_at', $currentYear - 1)
                    ->select(
                        DB::raw('YEAR(campagnes.created_at) as annee'),
                        DB::raw('AVG(concernes.score_final) as moyenne')
                    )
                    ->groupBy('annee')
                    ->first();

                $scores2 = DB::table('concernes')
                    ->join('campagnes', 'concernes.id_campagne', '=', 'campagnes.id')
                    ->where('concernes.id_personnel', '=', $personnel->id)
                    ->whereNotNull('concernes.score_final')
                    ->whereYear('concernes.created_at', $currentYear - 2)
                    ->select(
                        DB::raw('YEAR(campagnes.created_at) as annee'),
                        DB::raw('AVG(concernes.score_final) as moyenne')
                    )
                    ->groupBy('annee')
                    ->first();
                    $object->id_campagne = $dernierCampagne->id;
                    $object->id_personnel = $personnel->id;
                    $object->nom = $personnel->nom_perso;
                    $object->id = $personnel->id;
                    $object->prenom = $personnel->prenom_perso;
                    $object->dateNaiss = $personnel->date_nais;
                    $object->categorie = $personnel->categorie;
                    $object->fonction = $personnel->structure_rattachee;
                    $object->dateEmb = $personnel->date_embauche;

                    $object->score = $cam->moyenne;
                    $object->ann1 = $scores ? $scores->moyenne : 0 ;
                    $object->ann2 = $scores2 ? $scores2->moyenne : 0  ;

                    array_push($result, $object);
                }

            // return response()->json(["datas" => $scores->annee], 200);
            return response()->json(["datas" => $result], 200);
        } catch (\Throwable $th) {
            return response()->json(["message" => "Error : " . $th->getMessage()], 500);
        }
    }

    public function synthese(Request $request)
    {
        try {
            $user_id = $request->user_id;
            $year = date('Y');
            $result = new stdClass;
            $note = DB::table('concernes')
                ->join('personnels as p1', 'concernes.id_personnel', '=', 'p1.id')
                ->where('p1.id', '=', $user_id)
                // ->where('concernes.score_final', '!=', null)
                ->whereYear('concernes.created_at', $year)
                ->first();

            $recommandations = recommandation::where("id_personnel", "=", $user_id)
                ->where("id_campagne", "=", $request->id_campagne)
                ->get();
            $result->notes = $note;
            $result->recommandations = $recommandations;
            return response()->json(["datas" => $result], 200);
        } catch (\Throwable $th) {
            return response()->json(["message" => "Error : " . $th->getMessage()], 500);
        }
    }

    public function getAutoEval()
    {
        try {
            $year = date('Y');
            $note = DB::table('concernes')
                // ->join('personnels as p1', 'concernes.id_personnel', '=', 'p1.id')
                // ->where('p1.id', '=', $user_id)
                ->where('score', '!=', null)
                ->whereYear('concernes.created_at', $year)
                ->get();
            $count = count($note);
            $personnel = personnel::all();
            $countP = count($personnel);
            $noteN = DB::table('concernes')
                // ->join('personnels as p1', 'concernes.id_personnel', '=', 'p1.id')
                // ->where('p1.id', '=', $user_id)
                ->where('score_final', '!=', null)
                ->whereYear('concernes.created_at', $year)
                ->get();
            $countR = count($noteN);
            $somme = 0;
            foreach ($noteN as $note) {
                $somme = $somme + $note->score_final;
            }
            $moyenne = $somme / $countR;
            $result = new stdClass;

            $result->moyenne = $moyenne;
            $result->autoEval = $count;
            $result->eval = $countR;
            $result->totalPersonne = $countP;

            return response()->json(["datas" => $result], 200);
        } catch (\Throwable $th) {
            return response()->json(["message" => "Error : " . $th->getMessage()], 500);
        }
    }

    public function graph(Request $request)
    {
        try {
            $year = date('Y');
            $results = DB::table('personnels')
                ->join('concernes', 'personnels.id', '=', 'concernes.id_personnel')
                ->join('campagnes', 'concernes.id_campagne', '=', 'campagnes.id')
                ->join('directions', 'directions.id', '=', 'personnels.direction')
                ->where('concernes.score_final', '!=', null)
                ->whereYear('concernes.created_at', $year)
                ->select(DB::raw('avg(concernes.score_final) as moyenne'), DB::raw('directions.nom as direction'))
                ->groupBy('directions.nom')
                ->get()
                ->map(function ($item) {
                    return [
                        'name' => $item->direction,
                        'value' => $item->moyenne,
                        'extra' => [
                            'code' => substr($item->direction, 0, 2)
                        ]
                    ];
                });
            return response()->json(["datas" => $results], 200);
        } catch (\Throwable $th) {
            return response()->json(["message" => "Error : " . $th->getMessage()], 500);
        }
    }

    public function graphPerDirection(Request $request)
    {
        try {
            $year = date('Y');
            $results = DB::table('personnels')
                ->join('concernes', 'personnels.id', '=', 'concernes.id_personnel')
                ->join('campagnes', 'concernes.id_campagne', '=', 'campagnes.id')
                ->join('directions', 'directions.id', '=', 'personnels.direction')
                ->where('personnels.direction', '=', $request->direction)
                ->where('concernes.score_final', '!=', null)
                ->whereYear('concernes.created_at', $year)
                ->select(DB::raw('avg(concernes.score_final) as moyenne'), DB::raw('directions.nom as direction'))
                ->groupBy('directions.nom')
                ->get()
                ->map(function ($item) {
                    return [
                        'name' => $item->direction,
                        'value' => $item->moyenne,
                        'extra' => [
                            'code' => substr($item->direction, 0, 2)
                        ]
                    ];
                });
            return response()->json(["datas" => $results], 200);
        } catch (\Throwable $th) {
            return response()->json(["message" => "Error : " . $th->getMessage()], 500);
        }
    }
}
