<?php

namespace App\Http\Controllers;

use App\Models\arrondissement;
use App\Models\compte;
use App\Models\departement;
use App\Models\diplome;
use App\Models\direction;
use App\Models\nommination;
use App\Models\personnel;
use App\Models\region;
use App\Models\role;
use App\Models\situation;
use App\Models\stage;
use App\Models\structure_gene;
use App\Models\typeStructure;
use App\Models\User;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use stdClass;

use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;

class PersonnelController extends Controller
{
    // Listing de toutes les tables de la base de données
    public function getAllData()
    {
        try {

            $regions = [];

            foreach (region::all() as  $region) {
                $departs = [];
                foreach (departement::where('id_region', $region->id)->get() as $depart) {
                    $arronds = [];
                    foreach (arrondissement::where('id_depart', $depart->id)->get() as  $arrond) {
                        $arronds[] =  $arrond;
                    }
                    $depart->arrondissements = $arronds;
                    $departs[] = $depart;
                }
                $region->departements = $departs;
                $regions[] = $region;
            }

            return response()->json([
                "status" => "200",
                "regions" => $regions,
                "structuresGenerale" =>  structure_gene::all(),
                "typesService" =>   typeStructure::all(),
            ]);
        } catch (\Throwable $th) {
            abort(500, 'Could not create office or assign it to administrator');
        }
    }

    // Somme personnel actifs et retraités
    public function totalPersonnel()
    {
        $personnelsA = DB::table('personnels')->where("position_gest", "=", "en service")->get();
        $personnelsR = DB::table('personnels')->where("position_gest", "!=", "en service")->get();

        $som1 = count($personnelsA);
        $som2 = count($personnelsR);

        return response()->json([
            "status" => "200",
            "personnelActif" => $som1,
            "personnelRetraite" => $som2,
        ]);
    }

    // Repartion du personnel par région
    public function getPersonnelPerRegion()
    {
        $datas = DB::table('personnels')
            ->join('arrondissements as a', 'personnels.id_arrond_travail', '=', 'a.id')
            ->join('departements as d', 'a.id_depart', '=', 'd.id')
            ->join('regions as r', 'd.id_region', '=', 'r.id')
            ->where('personnels.position_gest', '=', 'en service')
            ->select(DB::raw('count(personnels.nom_perso) as count, r.nom'))
            ->groupBy('r.nom')
            ->get();

        $results = array();

        foreach ($datas as $data) {
            $newData = new stdClass;
            $newData->name = $data->nom;
            $newData->value = $data->count;

            array_push($results, $newData);
        }

        return response()->json([
            "status" => "200",
            "datas" => $results
        ]);
    }

    // 5 Prochains départs en retraites
    public function getProchainsRetraites()
    {
        $situations = DB::table('situations')
            ->orderBy('date_dep_retraite', 'asc')
            ->take(5)
            ->get();

        $results = array();
        foreach ($situations as $situation) {
            $personnel = personnel::find($situation->id_personnel);
            $sg = structure_gene::find($personnel->id_sg);
            $type = typeStructure::find($personnel->id_type);
            $arrond_origine = arrondissement::find($personnel->id_arrond_origine);
            $arrond_travail = arrondissement::find($personnel->id_arrond_travail);

            $newPerso = new stdClass;

            $newPerso->id = $personnel->id;
            $newPerso->matricule = $personnel->matricule_perso;
            $newPerso->nom = $personnel->nom_perso;
            $newPerso->sexe = $personnel->sexe_perso;
            $newPerso->prenom = $personnel->prenom_perso;
            $newPerso->lieuNaiss = $personnel->lieu_nais;
            $newPerso->statutMatrimonial = $personnel->statut_matrimonial;
            $newPerso->dateNaiss = $personnel->date_nais;
            $newPerso->nbreEnfant = $personnel->nbre_enfant;
            $newPerso->positionGestion = $personnel->position_gest;
            $newPerso->motifSortie = $personnel->motif_sortie;
            $newPerso->dateSortie = $personnel->date_sortie;
            $newPerso->direction = $personnel->direction;
            $newPerso->anciennete = $personnel->anciennete;
            $newPerso->categorie = $personnel->categorie;
            $newPerso->lieu_travail = $personnel->lieu_travail;
            $newPerso->date_embauche = $personnel->date_embauche;
            $newPerso->payroll = $personnel->payroll;
            $newPerso->structure = $personnel->structure_perso;
            $newPerso->structureRattachee = $personnel->structure_rattachee;
            $newPerso->strutureGenerale = $sg;
            $newPerso->typeService = $type;
            $newPerso->arrondissement = $arrond_origine;
            $newPerso->arrondissementTravail = $arrond_travail;
            $newPerso->situation = $situation;

            array_push($results, $newPerso);
        }

        return response()->json([
            'status' => '200',
            'datas' => $results
        ]);
    }

    // Enrégistrement d'un personnel dans la base de données
    public function store(Request $request)
    {
        $request->validate([
            "matricule" => "required",
            "nom" => "required",
            "sexe" => "required",
            "prenom" => "required",
            "dateNaiss" => "nullable",
            "date_embauche" => "nullable",
            "structure" => "nullable",
            "structureRattachee" => "nullable",
            "structureGenerale" => "nullable",
            "anciennete" => "nullable",
            "categorie" => "nullable",
            "level" => "nullable",
            "lieu_travail" => "nullable",
        ]);

        try {

            $personnel = personnel::create([
                "matricule_perso" => $request->matricule,
                "nom_perso" => $request->nom,
                "sexe_perso" => $request->sexe,
                "prenom_perso" => $request->prenom,
                "date_nais" => $request->dateNaiss,
                "date_embauche" => $request->date_embauche,
                "structure_perso" => $request->structure,
                "lieu_travail" => $request->lieu_travail,
                "categorie" => $request->categorie,
                "level" => $request->level,
                "anciennete" => $request->anciennete,
                "structure_rattachee" => $request->structureRattachee,
                "direction" => $request->structureGenerale,
            ]);
            return response()->json([
                'status' => '201',
                'datas' => $personnel
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => '500',
                'message' => 'Une erreur est survenue'
            ]);
        }
    }

    // Listing de tout le personnel existant dans la base de données
    public function getAllPersonnel()
    {
        try {
            $personnels = personnel::orderBy('nom_perso')->get();
            $result = array();
            if (count($personnels) > 0) {
                foreach ($personnels as $personnel) {

                    $diplome = diplome::find($personnel->id_diplome);
                    $sg = structure_gene::find($personnel->id_sg);
                    $type = typeStructure::find($personnel->id_type);
                    $arrond_origine = arrondissement::find($personnel->id_arrond_origine);
                    $direction = direction::find($personnel->direction);
                    $arrond_travail = arrondissement::find($personnel->id_arrond_travail);

                    $dateEmbauche = new DateTime($personnel->date_embauche) ;
                    $date = new DateTime() ;

                    $interval = $date->diff($dateEmbauche) ;

                    // $situation = DB::table('situations');

                    $newPerso = new stdClass;

                    $newPerso->id = $personnel->id;
                    $newPerso->matricule = $personnel->matricule_perso;
                    $newPerso->nom = $personnel->nom_perso;
                    $newPerso->sexe = $personnel->sexe_perso;
                    $newPerso->prenom = $personnel->prenom_perso;
                    $newPerso->lieuNaiss = $personnel->lieu_nais;
                    $newPerso->statutMatrimonial = $personnel->statut_matrimonial;
                    $newPerso->dateNaiss = $personnel->date_nais;
                    $newPerso->nbreEnfant = $personnel->nbre_enfant;
                    $newPerso->positionGestion = $personnel->position_gest;
                    $newPerso->motifSortie = $personnel->motif_sortie;
                    $newPerso->dateSortie = $personnel->date_sortie;
                    $newPerso->structure = $personnel->structure_perso;
                    $newPerso->fonction = $personnel->fonction;
                    $newPerso->direction = $direction->nom;
                    $newPerso->anciennete = $interval->m;
                    $newPerso->categorie = $personnel->categorie;
                    $newPerso->level = $personnel->level;
                    $newPerso->lieu_travail = $personnel->lieu_travail;
                    $newPerso->date_embauche = $personnel->date_embauche;
                    $newPerso->payroll = $personnel->payroll;
                    $newPerso->structureRattachee = $personnel->structure_rattachee;
                    $newPerso->strutureGenerale = $sg;
                    $newPerso->typeService = $type;
                    $newPerso->arrondissement = $arrond_origine;
                    $newPerso->arrondissementTravail = $arrond_travail;

                    array_push($result, $newPerso);
                }
                return response()->json([
                    'status' => '200',
                    'datas' => $result
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
                'message' => 'Une erreur est survenue'
            ]);
        }
    }

    // Afficher un personnel
    public function getOnePersonnel($id)
    {
        $personnel = personnel::find($id);
        if (!$personnel) {
            return response()->json([
                'status' => '200',
                'message' => 'Aucune ressource disponible'
            ]);
        } else {
            // $diplome = diplome::find($personnel->id_diplome);
            $sg = structure_gene::find($personnel->id_sg);
            $type = typeStructure::find($personnel->id_type);
            $arrond_origine = arrondissement::find($personnel->id_arrond_origine);
            $arrond_travail = arrondissement::find($personnel->id_arrond_travail);

            $newPerso = new stdClass;

            $newPerso->matricule = $personnel->matricule_perso;
            $newPerso->id = $personnel->id;
            $newPerso->nom = $personnel->nom_perso;
            $newPerso->sexe = $personnel->sexe_perso;
            $newPerso->prenom = $personnel->prenom_perso;
            $newPerso->lieuNaiss = $personnel->lieu_nais;
            $newPerso->statutMatrimonial = $personnel->statut_matrimonial;
            $newPerso->dateNaiss = $personnel->date_nais;
            $newPerso->nbreEnfant = $personnel->nbre_enfant;
            $newPerso->positionGestion = $personnel->position_gest;
            $newPerso->motifSortie = $personnel->motif_sortie;
            $newPerso->dateSortie = $personnel->date_sortie;
            $newPerso->structure = $personnel->structure_perso;
            $newPerso->direction = $personnel->direction;
            $newPerso->anciennete = $personnel->anciennete;
            $newPerso->categorie = $personnel->categorie;
            $newPerso->lieu_travail = $personnel->lieu_travail;
            $newPerso->date_embauche = $personnel->date_embauche;
            $newPerso->payroll = $personnel->payroll;
            $newPerso->structureRattachee = $personnel->structure_rattachee;
            $newPerso->strutureGenerale = $sg;
            $newPerso->typeService = $type;
            $newPerso->arrondissement = $arrond_origine;
            $newPerso->arrondissementTravail = $arrond_travail;

            return response()->json([
                'status' => '200',
                'datas' => $newPerso
            ]);
        }
    }

    public function OnePersonnel(Request $request)
    {
        $personnel = personnel::find($request->id_personnel);
        if (!$personnel) {
            return response()->json([
                'status' => '200',
                'message' => 'Aucune ressource disponible'
            ]);
        } else {
            // $diplome = diplome::find($personnel->id_diplome);
            $sg = structure_gene::find($personnel->id_sg);
            $type = typeStructure::find($personnel->id_type);
            $arrond_origine = arrondissement::find($personnel->id_arrond_origine);
            $arrond_travail = arrondissement::find($personnel->id_arrond_travail);

            $newPerso = new stdClass;

            $newPerso->matricule = $personnel->matricule_perso;
            $newPerso->id = $personnel->id;
            $newPerso->nom = $personnel->nom_perso;
            $newPerso->sexe = $personnel->sexe_perso;
            $newPerso->prenom = $personnel->prenom_perso;
            $newPerso->lieuNaiss = $personnel->lieu_nais;
            $newPerso->statutMatrimonial = $personnel->statut_matrimonial;
            $newPerso->dateNaiss = $personnel->date_nais;
            $newPerso->nbreEnfant = $personnel->nbre_enfant;
            $newPerso->positionGestion = $personnel->position_gest;
            $newPerso->motifSortie = $personnel->motif_sortie;
            $newPerso->dateSortie = $personnel->date_sortie;
            $newPerso->structure = $personnel->structure_perso;
            $newPerso->direction = $personnel->direction;
            $newPerso->anciennete = $personnel->anciennete;
            $newPerso->categorie = $personnel->categorie;
            $newPerso->lieu_travail = $personnel->lieu_travail;
            $newPerso->date_embauche = $personnel->date_embauche;
            $newPerso->payroll = $personnel->payroll;
            $newPerso->structureRattachee = $personnel->structure_rattachee;
            $newPerso->strutureGenerale = $sg;
            $newPerso->typeService = $type;
            $newPerso->arrondissement = $arrond_origine;
            $newPerso->arrondissementTravail = $arrond_travail;

            return response()->json([
                'status' => '200',
                'datas' => $newPerso
            ]);
        }
    }

    public function getProfile(Request $request)
    {
        $personnel = personnel::find($request->user_id);
        if (!$personnel) {
            return response()->json([
                'status' => '200',
                'message' => 'Aucune ressource disponible'
            ]);
        } else {
            // $diplome = diplome::find($personnel->id_diplome);
            $sg = structure_gene::find($personnel->id_sg);
            $type = typeStructure::find($personnel->id_type);
            $arrond_origine = arrondissement::find($personnel->id_arrond_origine);
            $arrond_travail = arrondissement::find($personnel->id_arrond_travail);

            $newPerso = new stdClass;

            $newPerso->id = $personnel->id;
            $newPerso->matricule = $personnel->matricule_perso;
            $newPerso->nom = $personnel->nom_perso;
            $newPerso->sexe = $personnel->sexe_perso;
            $newPerso->prenom = $personnel->prenom_perso;
            $newPerso->lieuNaiss = $personnel->lieu_nais;
            $newPerso->statutMatrimonial = $personnel->statut_matrimonial;
            $newPerso->dateNaiss = $personnel->date_nais;
            $newPerso->nbreEnfant = $personnel->nbre_enfant;
            $newPerso->positionGestion = $personnel->position_gest;
            $newPerso->motifSortie = $personnel->motif_sortie;
            $newPerso->dateSortie = $personnel->date_sortie;
            $newPerso->structure = $personnel->structure_perso;
            $newPerso->direction = $personnel->direction;
            // $newPerso->direction = $direction->nom;
            $newPerso->anciennete = $personnel->anciennete;
            $newPerso->categorie = $personnel->categorie;
            $newPerso->level = $personnel->level;
            $newPerso->lieu_travail = $personnel->lieu_travail;
            $newPerso->date_embauche = $personnel->date_embauche;
            $newPerso->payroll = $personnel->payroll;
            $newPerso->structureRattachee = $personnel->structure_rattachee;
            $newPerso->strutureGenerale = $sg;
            $newPerso->typeService = $type;
            $newPerso->arrondissement = $arrond_origine;
            $newPerso->arrondissementTravail = $arrond_travail;

            return response()->json([
                'status' => '200',
                'datas' => $newPerso
            ]);
        }
    }

    // Mise à jour
    public function update(Request $request, $id)
    {
        $request->validate([
            "matricule" => "required",
            "nom" => "required",
            "sexe" => "required",
            "prenom" => "required",
            "dateNaiss" => "nullable",
            "date_embauche" => "nullable",
            "structure" => "nullable",
            "structureRattachee" => "nullable",
            "structureGenerale" => "nullable",
            "anciennete" => "nullable",
            "categorie" => "nullable",
            "level" => "nullable",
            "lieu_travail" => "nullable",
        ]);

        try {
            $personnel = personnel::find($id);

            if ($personnel) {
                $personnel->matricule_perso = $request->matricule;
                $personnel->nom_perso = $request->nom;
                $personnel->prenom_perso = $request->prenom;
                $personnel->sexe_perso = $request->sexe;
                $personnel->date_nais = $request->dateNaiss;
                $personnel->date_embauche = $request->date_embauche;
                $personnel->structure_perso = $request->structure;
                $personnel->lieu_travail = $request->lieu_travail;
                $personnel->categorie = $request->categorie;
                $personnel->level = $request->level;
                $personnel->anciennete = $request->anciennete;
                $personnel->structure_rattachee = $request->structureRattachee;
                $personnel->direction = $request->structureGenerale;
                $personnel->save();

                return response()->json([
                    'status' => '200',
                    "message" => "Mise à jour avec succès",
                    'personnel' => $personnel
                ]);
            } else {
                return response()->json([
                    'status' => '200',
                    'message' => 'Personnel non existant'
                ]);
            }
        } catch (\Throwable $th) {
            return response()->json([
                'status' => '500',
                'message' => 'Une erreur est survenue'
            ]);
        }
    }

    public function updateProfile(Request $request, $id)
    {
        // $request->validate([
        //     "matricule" => "required",
        //     "nom" => "required",
        //     "sexe" => "required",
        //     "prenom" => "required",
        //     "dateNaiss" => "nullable",
        // ]);

        try {
            $personnel = personnel::find($id);

            if ($personnel) {
                $personnel->nom_perso = $request->nom;
                $personnel->prenom_perso = $request->prenom;
                $personnel->sexe_perso = $request->sexe;
                $personnel->date_nais = $request->dateNaiss;
                $personnel->save();

                return response()->json([
                    'status' => '200',
                    "message" => "Mise à jour avec succès",
                    'personnel' => $personnel
                ]);
            } else {
                return response()->json([
                    'status' => '200',
                    'message' => 'Personnel non existant'
                ]);
            }
        } catch (\Throwable $th) {
            return response()->json([
                'status' => '500',
                'message' => 'Une erreur est survenue'
            ]);
        }
    }

    public function delete($id)
    {
        try {
            $personnel = personnel::find($id);

            if ($personnel) {

                $message = "Personnel " . $personnel->nom_perso . " " . $personnel->prenom_perso . " supprimé avec success";
                $personnel->delete();

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
                'message' => 'Une erreur est survenue'
            ]);
        }
    }

    public function create_user(Request $request)
    {
        $request->validate([
            "name" => "required",
            "email" => "required|email",
            "password" => "required",
            "role" => "required"
        ]);

        if ($user = DB::table('users')->where('email', '=', $request->email)->first()) {
            return response()->json([
                'message' => 'Cette adresse mail est déjà utilisé '
            ], 200);
        } else {
            $pwd = Hash::make($request->password);

            $user = compte::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => $pwd,
                'id_personnel' => $request->personnel
            ]);
            $role = role::find($request->role);
            $user->roles()->attach($role);


            $token = Auth::login($user);

            $personnel = personnel::find($user->id_personnel);
            $role = role::find($user->roles->first()->id);

            $result = new stdClass;
            $result->compte = $user;
            $result->personnel = $personnel;
            $result->role = $role;

            return response()->json([
                'message' => 'Enrégistrement réussie',
                'user' => $result,
                'token' => $token
            ], 201);
        }
    }

    // Charger la base de données à partir d'un fichier excel
    public function loadFile(Request $request)
    {
        // Vérifier si le fichier a été téléchargé
        if ($request->hasFile('file') && $request->file('file')->isValid()) {

            // Vérifier l'extension du fichier
            $extension = $request->file('file')->getClientOriginalExtension();
            if ($extension != 'xls' && $extension != 'xlsx') {
                return response()->json(['error' => 'Le fichier doit être au format Excel.'], 400);
            }

            // Récupérer le nombre de colonnes et le nom du fichier
            $file = $request->file('file')->getClientOriginalName();
            $filename = pathinfo($file, PATHINFO_FILENAME);
            $reader = IOFactory::createReader('Xlsx');
            $spreadsheet = $reader->load($request->file('file'));
            $worksheet = $spreadsheet->getActiveSheet();
            $numColumns = $worksheet->getHighestColumn();
            $numColumns = Coordinate::columnIndexFromString($numColumns);

            // Charger les données dans un tableau
            $data = [];
            foreach ($worksheet->getRowIterator() as $row) {
                $rowData = [];
                foreach ($row->getCellIterator() as $cell) {
                    // Récupérer la valeur de la cellule
                    $value = $cell->getValue();

                    // Vérifier le type de données de la cellule
                    if ($cell->getDataType() == \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_NUMERIC) {
                        $value = $cell->getCalculatedValue();
                    } elseif ($cell->getDataType() == \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_FORMULA) {
                        $value = $cell->getCalculatedValue();
                    } elseif ($cell->getDataType() == \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_BOOL) {
                        $value = $cell->getCalculatedValue();
                    } elseif ($cell->getDataType() == \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_NULL) {
                        $value = null;
                    } else {
                        $value = $cell->getValue();
                    }

                    $rowData[] = $value;
                }
                $data[] = $rowData;
            }

            switch ($filename) {
                    // Cas où il s'agit d'un tableur de régions
                case 'regions':
                    if ($numColumns = '2') {
                        for ($i = 1; $i < count($data); $i++) {
                            region::create([
                                "nom" => $data[$i][0],
                                "chef_lieu" => $data[$i][1]
                            ]);
                        }
                        return response()->json([
                            'message' => 'Fichier Excel importé avec succès.',
                            "succes" => "Données enregistrées dans la table Régions"
                        ], 201);
                    } else {
                        return response()->json([
                            'message' => 'Fichier Excel importé avec succès.',
                            "error" => "Le nombre de colone du fichier ne respecte la structure de la table Régions"
                        ], 400);
                    }
                    break;

                    // Cas où il s'agit d'un tableur de département
                case 'departements':
                    if ($numColumns = '3') {
                        for ($i = 1; $i < count($data); $i++) {
                            $nom_reg = $data[$i][2];
                            $region = DB::table('regions')->where('nom', '=', $nom_reg)->first();

                            if ($region) {
                                try {
                                    departement::create([
                                        "nom" => $data[$i][0],
                                        "chef_lieu" => $data[$i][1],
                                        "id_region" => $region->id
                                    ]);
                                } catch (\Throwable $th) {
                                    return response()->json([
                                        "status" => "500",
                                        "message" => $th
                                    ]);
                                }
                            } else {
                                return response()->json([
                                    "status" => "200",
                                    "message" => "La région du département " . $data[$i][0] . " est incorrecte ou n'existe pas."
                                ]);
                            }
                        }
                        return response()->json([
                            'message' => 'Fichier Excel importé avec succès.',
                            "succes" => "Données enregistrées dans la table Départements"
                        ], 201);
                    } else {
                        return response()->json([
                            'message' => 'Fichier Excel importé avec succès.',
                            "error" => "Le nombre de colone du fichier ne respecte la structure de la table Départements"
                        ], 400);
                    }
                    break;

                case 'arrondissements':
                    if ($numColumns = '2') {
                        for ($i = 1; $i < count($data); $i++) {
                            $nom_depart = $data[$i][1];
                            $depart = DB::table('departements')->where('nom', '=', $nom_depart)->first();

                            if ($depart) {
                                try {
                                    arrondissement::create([
                                        "nom" => $data[$i][0],
                                        "id_depart" => $depart->id
                                    ]);
                                } catch (\Throwable $th) {
                                    return response()->json([
                                        "status" => "500",
                                        "message" => $th
                                    ]);
                                }
                            } else {
                                return response()->json([
                                    "status" => "200",
                                    "message" => "Le département de l'arrondissement " . $data[$i][0] . " est incorrecte ou n'existe pas."
                                ]);
                            }
                        }
                        return response()->json([
                            'message' => 'Fichier Excel importé avec succès.',
                            "succes" => "Données enregistrées dans la table Arrondissements"
                        ], 201);
                    } else {
                        return response()->json([
                            'message' => 'Fichier Excel importé avec succès.',
                            "error" => "Le nombre de colone du fichier ne respecte la structure de la table Arrondissement"
                        ], 400);
                    }
                    break;

                case 'type_structure':
                    if ($numColumns = '1') {
                        for ($i = 1; $i < count($data); $i++) {
                            typeStructure::create(["nom" => $data[$i][0]]);
                        }
                        return response()->json([
                            'message' => 'Fichier Excel importé avec succès.',
                            "succes" => "Données enregistrées dans la table Type de structure"
                        ], 201);
                    } else {
                        return response()->json([
                            'message' => 'Fichier Excel importé avec succès.',
                            "error" => "Le nombre de colone du fichier ne respecte la structure de la table Type de structure"
                        ], 400);
                    }

                    break;

                case 'structure_generale':
                    if ($numColumns = '1') {
                        for ($i = 1; $i < count($data); $i++) {
                            structure_gene::create(["nom" => $data[$i][0]]);
                        }
                        return response()->json([
                            'message' => 'Fichier Excel importé avec succès.',
                            "succes" => "Données enregistrées dans la table Structure générale"
                        ], 201);
                    } else {
                        return response()->json([
                            'message' => 'Fichier Excel importé avec succès.',
                            "error" => "Le nombre de colone du fichier ne respecte la structure de la table Structure générale"
                        ], 400);
                    }
                    break;

                case 'stage_seminaire':
                    if ($numColumns = '5') {
                        for ($i = 1; $i < count($data); $i++) {
                            stage::create([
                                "theme_stage" => $data[$i][0],
                                "domaine" => $data[$i][1],
                                "institut" => $data[$i][2],
                                "nbre_jour" => $data[$i][3],
                                "localisation" => $data[$i][4]
                            ]);
                        }
                        return response()->json([
                            'message' => 'Fichier Excel importé avec succès.',
                            "succes" => "Données enregistrées dans la table Stage/Séminaire"
                        ], 201);
                    } else {
                        return response()->json([
                            'message' => 'Fichier Excel importé avec succès.',
                            "error" => "Le nombre de colone du fichier ne respecte la structure de la table Structure générale"
                        ], 400);
                    }

                    break;

                case 'situations':
                    if ($numColumns = '12') {
                        for ($i = 1; $i < count($data); $i++) {
                            $them_stage_seminaire = $data[$i][11];
                            $stage = DB::table('stages')->where('theme_stage', '=', $them_stage_seminaire)->first();
                            if ($stage) {
                                situation::create([
                                    "date_recrutement" => $data[$i][0],
                                    "nature_acte" => $data[$i][1],
                                    "statut_acte" => $data[$i][2],
                                    "id_corps" => $data[$i][3],
                                    "id_grade" => $data[$i][4],
                                    "id_categorie" => $data[$i][5],
                                    "nommination" => $data[$i][6],
                                    "age_dep_retraite" => $data[$i][7],
                                    "date_dep_retraite" => $data[$i][8],
                                    "poste_actuel" => $data[$i][9],
                                    "niv_instruction" => $data[$i][10],
                                    "id_stage_seminaire" => $stage->id
                                ]);
                            } else {
                                situation::create([
                                    "date_recrutement" => $data[$i][0],
                                    "nature_acte" => $data[$i][1],
                                    "statut_acte" => $data[$i][2],
                                    "id_corps" => $data[$i][3],
                                    "id_grade" => $data[$i][4],
                                    "id_categorie" => $data[$i][5],
                                    "nommination" => $data[$i][6],
                                    "age_dep_retraite" => $data[$i][7],
                                    "date_dep_retraite" => $data[$i][8],
                                    "poste_actuel" => $data[$i][9],
                                    "niv_instruction" => $data[$i][10],
                                    "id_stage_seminaire" => null
                                ]);
                            }
                        }
                        return response()->json([
                            'message' => 'Fichier Excel importé avec succès.',
                            "succes" => "Données enregistrées dans la table Situation"
                        ], 201);
                    } else {
                        return response()->json([
                            'message' => 'Fichier Excel importé avec succès.',
                            "error" => "Le nombre de colone du fichier ne respecte la structure de la table Situation"
                        ], 400);
                    }
                    break;

                case 'diplome':
                    if ($numColumns = '9') {
                        for ($i = 1; $i < count($data); $i++) {
                            diplome::create([
                                "libelle" => $data[$i][0],
                                "date_optention" => $data[$i][1],
                                "domaine" => $data[$i][2],
                                "etablissement" => $data[$i][3],
                                "option" => $data[$i][4],
                                "ville" => $data[$i][5],
                                "pays" => $data[$i][6],
                                "statut" => $data[$i][7],
                                "id_personnel" => $data[$i][8]
                            ]);
                        }
                        return response()->json([
                            'message' => 'Fichier Excel importé avec succès.',
                            "succes" => "Données enregistrées dans la table Diplome"
                        ], 201);
                    } else {
                        return response()->json([
                            'message' => 'Fichier Excel importé avec succès.',
                            "error" => "Le nombre de colone du fichier ne respecte la structure de la table Situation"
                        ], 400);
                    }
                    break;

                case 'pesonnels':
                    if ($numColumns = '19') {
                        for ($i = 1; $i < count($data); $i++) {
                            $nom_sg = $data[$i][13];
                            try {

                                $sg = DB::table('struct_genes')->where('nom_structure', '=', $nom_sg)->first();
                                if ($sg) {
                                    $id_sg = $sg->id;
                                    $diplome = DB::table('diplomes')->where('libelle', '=', $data[$i][14])->first();
                                    if ($diplome) {
                                        $id_diplome = $diplome->id;
                                        if ($type = DB::table('type_structures')->where('nom_type', '=', $data[$i][15])->first()) {
                                            $id_type = $type->id;
                                            if ($arrond_origine = DB::table('arrondissements')->where('nom_arrond', '=', $data[$i][16])->first()) {
                                                $id_arrond_origine = $arrond_origine->id;
                                                if ($arrond_travail = DB::table('arrondissements')->where('nom_arrond', '=', $data[$i][17])->first()) {
                                                    $id_arrond_travail = $arrond_travail->id;
                                                    if ($situation = DB::table('situations')->where('date_recrutement', '=', $data[$i][18])->first()) {
                                                        $id_situation = $situation->id;
                                                        $personnel = personnel::create([
                                                            "matricule_perso" => $data[$i][0],
                                                            "nom_perso" => $data[$i][1],
                                                            "sexe_perso" => $data[$i][2],
                                                            "prenom_perso" => $data[$i][3],
                                                            "lieu_nais" => $data[$i][4],
                                                            "statut_matrimonial" => $data[$i][5],
                                                            "date_nais" => $data[$i][6],
                                                            "nbre_enfant" => $data[$i][7],
                                                            "position_gest" => $data[$i][8],
                                                            "motif_sortie" => $data[$i][9],
                                                            "date_sortie" => $data[$i][10],
                                                            "structure_perso" => $data[$i][11],
                                                            "structure_rattachee" => $data[$i][12],
                                                            "id_sg" => $id_sg,
                                                            "id_diplome" => $id_diplome,
                                                            "id_type" => $id_type,
                                                            "id_arrond_origine" => $id_arrond_origine,
                                                            "id_arrond_travail" => $id_arrond_travail,
                                                            "id_situation" => $id_situation
                                                        ]);
                                                    } else {
                                                        return response()->json([
                                                            'status' => '200',
                                                            'message' => "Situation non existante pour le personnel " . $data[$i][0]
                                                        ]);
                                                    }
                                                } else {
                                                    return response()->json([
                                                        'status' => '200',
                                                        'message' => "Arrondissement du travail non existant pour le personnel " . $data[$i][0]
                                                    ]);
                                                }
                                            } else {
                                                return response()->json([
                                                    'status' => '200',
                                                    'message' => "Arrondissement d'origine non existant pour le personnel " . $data[$i][0]
                                                ]);
                                            }
                                        } else {
                                            return response()->json([
                                                'status' => '200',
                                                'message' => "Type de structure non existant pour le personnel " . $data[$i][0]
                                            ]);
                                        }
                                    } else {
                                        return response()->json([
                                            'status' => '200',
                                            'message' => "Diplome non existant pour le personnel " . $data[$i][0]
                                        ]);
                                    }
                                } else {
                                    return response()->json([
                                        'status' => '200',
                                        'message' => "Structure génerale non existante pour le personnel " . $data[$i][0]
                                    ]);
                                }
                            } catch (\Throwable $th) {
                                return response()->json([
                                    'status' => '500',
                                    'message' => 'Une erreur est survenue'
                                ]);
                            }
                        }
                        return response()->json([
                            'message' => 'Fichier Excel importé avec succès.',
                            "succes" => "Données enregistrées dans la table Personnel"
                        ], 201);
                    } else {
                        return response()->json([
                            'message' => 'Fichier Excel importé avec succès.',
                            "error" => "Le nombre de colone du fichier ne respecte la structure de la table Personnel"
                        ], 400);
                    }
                    break;

                case 'nominations':
                    if ($numColumns = '6') {
                        for ($i = 1; $i < count($data); $i++) {
                            if ($personnel = DB::table('personnels')->where('matricule_perso', '=', $data[$i][5])->first()) {
                                $nommination = nommination::create([
                                    "id_rang" => $data[$i][0],
                                    "id_nh" => $data[$i][1],
                                    "fonction" => $data[$i][2],
                                    "ref_acte" => $data[$i][3],
                                    "date_nommination" => $data[$i][4],
                                    "id_personnel" => $personnel->id
                                ]);
                            } else {
                                return response()->json([
                                    "status" => "200",
                                    "situation" => "Personnel non existant"
                                ]);
                            }
                        }
                        return response()->json([
                            'message' => 'Fichier Excel importé avec succès.',
                            "succes" => "Données enregistrées dans la table Nomination"
                        ], 201);
                    } else {
                        return response()->json([
                            'message' => 'Fichier Excel importé avec succès.',
                            "error" => "Le nombre de colone du fichier ne respecte la structure de la table Nomination"
                        ], 400);
                    }
                    break;
            }

            // ...


        } else {
            return response()->json(['error' => 'Le fichier est manquant ou invalide.'], 400);
        }
    }
}
