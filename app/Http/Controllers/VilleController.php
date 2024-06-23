<?php

namespace App\Http\Controllers;

use App\Models\personnel;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use App\Models\ville;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;

class VilleController extends Controller
{
    public function getAllVille()
    {
        try {
            $villes = ville::all();
            if (count($villes) > 0) {
                return response()->json([
                    "status" => "200",
                    "datas" => $villes
                ], 200);
            } else {
                return response()->json([
                    "status" => "200",
                    "message" => "Aucune ville correspondante"
                ], 200);
            }
        } catch (\Throwable $th) {
            return response()->json([
                "status" => "500",
                "message" => "Erreur interne du serveur"
            ], 500);
        }
    }

    public function getOneVille(Request $request, $id)
    {
        try {
            $ville = ville::find($id);
            if ($ville) {
                return response()->json([
                    "status" => "200",
                    "datas" => $ville
                ], 200);
            } else {
                return response()->json([
                    "status" => "200",
                    "message" => "Aucune ville correspondante"
                ], 200);
            }
        } catch (\Throwable $th) {
            return response()->json([
                "status" => "500",
                "message" => "Erreur interne du serveur"
            ], 500);
        }
    }

    public function store(Request $request)
    {
        $request->validate(["nom" => "required"]);
        try {
            $ville = ville::create(["nom" => $request->nom]);
            return response()->json([
                "status" => "201",
                "datas" => $ville
            ], 201);
        } catch (\Throwable $th) {
            return response()->json([
                "status" => "500",
                "message" => "Erreur interne du serveur"
            ], 500);
        }
    }

    public function update(Request $request, $id)
    {
        $request->validate(["nom" => "required"]);
        try {
            $ville = ville::find($id);
            if ($ville) {
                $ville->update(["nom" => $request->nom]);
                return response()->json([
                    "status" => "200",
                    "datas" => $ville
                ], 200);
            } else {
                return response()->json([
                    "status" => "200",
                    "message" => "Aucune ville correspondante"
                ], 200);
            }
        } catch (\Throwable $th) {
            return response()->json([
                "status" => "500",
                "message" => "Erreur interne du serveur"
            ], 500);
        }
    }

    public function delete($id)
    {
        try {
            $ville = ville::find($id);
            if ($ville) {
                $ville->delete();
                return response()->json([
                    "status" => "200",
                    "message" => "Ville supprimée avec succès"
                ], 200);
            } else {
                return response()->json([
                    "status" => "200",
                    "message" => "Aucune ville correspondante"
                ], 200);
            }
        } catch (\Throwable $th) {
            return response()->json([
                "status" => "500",
                "message" => "Erreur interne du serveur"
            ], 500);
        }
    }


    // public function importExcel(Request $request)
    // {
    //     // Récupérer le fichier Excel à partir du formulaire
    //     $file = $request->file('file');

    //     // Vérifier que le fichier a été soumis
    //     if (!$file) {
    //         return redirect()->back()->withErrors('Please select a file to upload');
    //     }

    //     // Ouvrir le fichier Excel
    //     $spreadsheet = IOFactory::load($file);
    //     $worksheet = $spreadsheet->getActiveSheet();

    //     // Parcourir chaque ligne de la feuille de calcul et insérer les données dans la base de données
    //     foreach ($worksheet->getRowIterator() as $row) {
    //         $rowData = $row->toArray();

    //         // Ignorer les en-têtes de colonnes
    //         if ($row->getRowIndex() == 1) {
    //             continue;
    //         }

    //         // Créer un nouvel objet Personnel à partir des données de la ligne
    //         $personnel = new Personnel([
    //             'matricule_perso' => $rowData[0],
    //             'nom_perso' => $rowData[1],
    //             'prenom_perso' => $rowData[2],
    //             'payroll_area' => $rowData[3],
    //             'seniority_in_month' => $rowData[4],
    //             'gender' => $rowData[5],
    //             'entry_date' => $rowData[6],
    //             'position' => $rowData[7],
    //             'date_of_birth' => $rowData[8],
    //             'cost_centre_desc' => $rowData[9],
    //             'personnel_area' => $rowData[10],
    //             'personnel_subarea' => $rowData[11],
    //             'group' => $rowData[12],
    //             'level' => $rowData[13],
    //         ]);

    //         // Enregistrer l'objet Personnel dans la base de données
    //         $personnel->save();
    //     }

    //     // Rediriger l'utilisateur vers la page précédente avec un message de succès
    //     return redirect()->back()->with('success', 'File uploaded successfully');
    // }


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

            try {
                for ($i = 1; $i < count($data); $i++) {

                    $dateValue = $data[$i][9]; // Récupérer la valeur de la cellule
                    $dateEmb = $data[$i][7]; // Récupérer la valeur de la cellule
                    if (($dateValue && is_numeric($dateValue)) && ($dateEmb && is_numeric($dateEmb))) {
                        $date = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($dateValue);
                        $date =  $date->format('Y-m-d');

                        $dateE = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($dateEmb);
                        $dateE =  $dateE->format('Y-m-d');
                    } else {
                        $date = null;
                        // $model->date_nais = null;
                    }

                    $personnel = personnel::create([
                        "matricule_perso" => $data[$i][1] ?? null,
                        "nom_perso" => $data[$i][2] ?? null,
                        "prenom_perso" => $data[$i][3] ?? null,
                        "payroll" => $data[$i][4] ?? null,
                        "anciennete" => $data[$i][5] ?? null,
                        "sexe_perso" => $data[$i][6] ?? null,
                        "date_embauche" => $dateE ?? null,
                        "structure_perso" => $data[$i][8] ?? null,
                        "date_nais" => $date,
                        "structure_rattachee" => $data[$i][10] ?? null,
                        "lieu_travail" => $data[$i][11] ?? null,
                        "direction" => $data[$i][12] ?? null,
                        "categorie" => $data[$i][13] ?? null,
                        "level" => $data[$i][14] ?? null,
                    ]);
                }

                return response()->json([
                    'message' => 'Fichier Excel importé avec succès.',
                    "succes" => "Données enregistrées dans la table Personnel"
                ], 201);
            } catch (\Throwable $th) {
                return response()->json(["message" => "Error : " . $th->getMessage()], 500);
            }
        } else {
            return response()->json(['error' => 'Le fichier est manquant ou invalide.'], 400);
        }
    }
}
