<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\ArrondController;
use App\Http\Controllers\CampagneController;
use App\Http\Controllers\CategorieController;
use App\Http\Controllers\CorpsController;
use App\Http\Controllers\DepartController;
use App\Http\Controllers\DiplomeController;
use App\Http\Controllers\DirectionController;
use App\Http\Controllers\GradeController;
use App\Http\Controllers\NiveauHController;
use App\Http\Controllers\NomminationController;
use App\Http\Controllers\ObjectifController;
use App\Http\Controllers\PersonnelController;
use App\Http\Controllers\RangController;
use App\Http\Controllers\RegionController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\SituationController;
use App\Http\Controllers\StageController;
use App\Http\Controllers\StructGeneController;
use App\Http\Controllers\TypeStructController;
use App\Http\Controllers\VilleController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
*/


Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);

// Endpoint qui renvoie toutes les données de la base de données
Route::get("all", [PersonnelController::class, "getAllData"]);


/*
|--------------------------------------------------------------------------
| Endpoints de l'admin
|--------------------------------------------------------------------------
*/
// Routes sécurisées par le middleware
// Route::middleware(['jwt.auth'])->group(function () {

// Endpoint de création d'un nouvel utilisateur
Route::post('compte', [PersonnelController::class, 'create_user']);
Route::put('compte/{id}', [AuthController::class, 'update']);
Route::put('resetPassword/{id}', [AuthController::class, 'resetPassword']);
Route::post('changePassword', [AuthController::class, 'changePassword']);
Route::delete('compte/{id}', [AuthController::class, 'delete']);
Route::get('compte/{id}', [AuthController::class, 'getOneUser']);
Route::get('compte', [AuthController::class, 'getAllUsers']);

/*
    |--------------------------------------------------------------------------
    | Gestion du Personnel
    |--------------------------------------------------------------------------
    */
Route::get('personnel', [PersonnelController::class, 'getAllPersonnel']);
Route::get('personnel/{id}', [PersonnelController::class, 'getOnePersonnel']);
Route::post('onePersonnel', [PersonnelController::class, 'OnePersonnel']);
Route::get('profile', [PersonnelController::class, 'getProfile']);
Route::post('personnel', [PersonnelController::class, 'store']);
Route::put('personnel/{id}', [PersonnelController::class, 'update']);
Route::put('profile/{id}', [PersonnelController::class, 'updateProfile']);
Route::delete('personnel/{id}', [PersonnelController::class, 'delete']);
Route::get('somme_personnel', [PersonnelController::class, 'totalPersonnel']);
Route::get('personnel_par_region', [PersonnelController::class, 'getPersonnelPerRegion']);
Route::get('prochaine_retraite', [PersonnelController::class, 'getProchainsRetraites']);


Route::get("campagne", [CampagneController::class, "getAll"]);
Route::post("campagne", [CampagneController::class, "store"]);
Route::get("campagne/{id}", [CampagneController::class, "getOne"]);
Route::put("campagne/{id}", [CampagneController::class, "update"]);
Route::delete("campagne/{id}", [CampagneController::class, "delete"]);

Route::get("getAutoEval", [CampagneController::class, "getAutoEval"]);
Route::get("graph", [CampagneController::class, "graph"]);
Route::post("graphDirection", [CampagneController::class, "graphPerDirection"]);
Route::post("synthese", [CampagneController::class, "synthese"]);


Route::post("add-emp", [CampagneController::class, "addEmployeToCampagne"]);

/*
    |--------------------------------------------------------------------------
    | CRUD sur les Roles
    |--------------------------------------------------------------------------
    */
Route::get('role', [RoleController::class, 'getAll']);
Route::get('role/{id}', [RoleController::class, 'getOne']);
Route::post('role', [RoleController::class, 'store']);
Route::put('role/{id}', [RoleController::class, 'update']);
Route::delete('role/{id}', [RoleController::class, 'delete']);


Route::get("objectif", [ObjectifController::class, "getAll"]);
Route::get("objectif/{id}", [ObjectifController::class, "getOne"]);
Route::get("emp-campagne", [ObjectifController::class, "getCampagne"]);
Route::post("emp-campagne", [ObjectifController::class, "getCampagneEvaluated"]);
Route::post("objectif", [ObjectifController::class, "store"]);
Route::put("objectif/{id}", [ObjectifController::class, "update"]);
Route::put("soumettre/{id}", [ObjectifController::class, "updateAll"]);
Route::put("update/{id}", [ObjectifController::class, "updateEmployeToCampagne"]);
Route::delete("objectif/{id}", [ObjectifController::class, "delete"]);

Route::get("emp-campagne", [ObjectifController::class, "getCampagne"]);
Route::get("objectif", [ObjectifController::class, "getAll"]);
Route::get("objectif/{id}", [ObjectifController::class, "getOne"]);
// Route::post("objectif", [ObjectifController::class, "store"]);
// });
Route::put("objectif/{id}", [ObjectifController::class, "update"]);
Route::put("soumettre/{id}", [ObjectifController::class, "updateAll"]);
Route::delete("objectif/{id}", [ObjectifController::class, "delete"]);
Route::post("re-evaluation", [ObjectifController::class, "re_auto_evaluate"]);
Route::post("evaluation", [ObjectifController::class, "auto_evaluate"]);
Route::post("evaluation-manager", [ObjectifController::class, "manager_evaluation"]);
Route::post("re-evaluation-manager", [ObjectifController::class, "re_manager_evaluation"]);
Route::post("changeN2", [ObjectifController::class, "changeN2"]);
Route::post("avis", [ObjectifController::class, "saveAvis"]);
Route::get("getAvis", [ObjectifController::class, "getAvis"]);
Route::get("getAvisN1", [ObjectifController::class, "getAvisN1"]);
Route::post("avis-n1", [ObjectifController::class, "saveAvisN1"]);
Route::get("evaluated-n1", [ObjectifController::class, "getEvaluatedPersonnel"]);
Route::post("evaluated-n1", [ObjectifController::class, "evaluateN1"]);
Route::post("re-evaluated-n1", [ObjectifController::class, "re_evaluateN1"]);
Route::post("getRecom", [ObjectifController::class, "getRecom"]);
Route::post("getRecomPersonnel", [ObjectifController::class, "getRecomPersonnel"]);
Route::post("updateRecommandation", [ObjectifController::class, "updateRecommandation"]);
Route::post("getRecommandation", [ObjectifController::class, "getRecommandation"]);
Route::post("evaluated-n2", [ObjectifController::class, "evaluateN2"]);
Route::post("re-evaluated-n2", [ObjectifController::class, "re_evaluateN2"]);
Route::post("approuved", [ObjectifController::class, "approuved"]);
Route::post("disapprouved", [ObjectifController::class, "disapprouved"]);
Route::post("rapport", [ObjectifController::class, "report"]);
Route::post("rapportN1", [ObjectifController::class, "reportE"]);
Route::post("rapportN2", [ObjectifController::class, "reportEN2"]);
Route::post("rapportCadre", [ObjectifController::class, "detailsCadre"]);
Route::post("rapportCN1", [ObjectifController::class, "rapportN1"]);
Route::post("rapportCN2", [ObjectifController::class, "rapportN2"]);
Route::post("saveR", [ObjectifController::class, "saveR"]);
Route::get("personnelRecommandation", [ObjectifController::class, "getPersonnelPerDirection"]);
Route::post("recommandation", [ObjectifController::class, "recommandated"]);
Route::get("personnel-campagne/{id}", [CampagneController::class, "personnelParCampagne"]);
Route::get("evaluated-n2", [ObjectifController::class, "getEvaluatedPersonnel2"]);
Route::get("reporting", [CampagneController::class, "reporting"]);
Route::get("objectif-personnel/{id}", [ObjectifController::class, "getObjectifPerPersonnel"]);
Route::post("detailsAutoCadre", [ObjectifController::class, "getAutoCadre"]);
Route::post("detailsAuto", [ObjectifController::class, "getAuto"]);

Route::post("detailsAutoCadreN1", [ObjectifController::class, "getAutoCadreN1"]);
Route::post("detailsAutoN1", [ObjectifController::class, "getAutoN1"]);



// /*
// |--------------------------------------------------------------------------
// | Endpoints du n+2
// |--------------------------------------------------------------------------
// */
// Route::middleware(['jwt.auth'])->group(function () {

//     Route::get("emp-campagne", [ObjectifController::class, "getCampagne"]);
//     Route::get("objectif", [ObjectifController::class, "getAll"]);
//     Route::get("objectif/{id}", [ObjectifController::class, "getOne"]);
//     Route::post("objectif", [ObjectifController::class, "store"]);
//     Route::put("objectif/{id}", [ObjectifController::class, "update"]);
//     Route::put("soumettre/{id}", [ObjectifController::class, "updateAll"]);
//     Route::delete("objectif/{id}", [ObjectifController::class, "delete"]);
//     Route::post("evaluation", [ObjectifController::class, "auto_evaluate"]);
//     Route::post("evaluation-manager", [ObjectifController::class, "manager_evaluation"]);
//     Route::post("avis", [ObjectifController::class, "saveAvis"]);
//     Route::post("avis-n1", [ObjectifController::class, "saveAvisN1"]);
//     Route::get("evaluated-n1", [ObjectifController::class, "getEvaluatedPersonnel"]);
//     // Route::get("evaluated-n2", [ObjectifController::class, "getEvaluatedPersonnel2"]);
//     Route::post("evaluated-n1", [ObjectifController::class, "evaluateN1"]);
//     Route::post("approuved", [ObjectifController::class, "approuved"]);
//     Route::post("rapport", [ObjectifController::class, "report"]);
//     Route::post("saveR", [ObjectifController::class, "saveR"]);
//     Route::get("personnelRecommandation", [ObjectifController::class, "getPersonnelPerDirection"]);
//     Route::post("recommandation", [ObjectifController::class, "recommandated"]);
//     Route::get("objectif-personnel/{id}", [ObjectifController::class, "getObjectifPerPersonnel"]);
// });

// /*
// |--------------------------------------------------------------------------
// | Endpoints du n+1
// |--------------------------------------------------------------------------
// */

// Route::middleware(['jwt.auth', 'role:n1'])->group(function () {

//     Route::get("emp-campagne", [ObjectifController::class, "getCampagne"]);
//     Route::get("objectif", [ObjectifController::class, "getAll"]);
//     Route::get("objectif/{id}", [ObjectifController::class, "getOne"]);
//     Route::post("objectif", [ObjectifController::class, "store"]);
//     Route::put("objectif/{id}", [ObjectifController::class, "update"]);
//     Route::put("soumettre/{id}", [ObjectifController::class, "updateAll"]);
//     Route::delete("objectif/{id}", [ObjectifController::class, "delete"]);
//     Route::post("evaluation", [ObjectifController::class, "auto_evaluate"]);
//     Route::post("evaluation-manager", [ObjectifController::class, "manager_evaluation"]);
//     Route::post("avis", [ObjectifController::class, "saveAvis"]);
//     Route::post("avis-n1", [ObjectifController::class, "saveAvisN1"]);
//     Route::get("evaluated-n1", [ObjectifController::class, "getEvaluatedPersonnel"]);
//     Route::get("evaluated-n2", [ObjectifController::class, "getEvaluatedPersonnel2"]);
//     Route::post("evaluated-n1", [ObjectifController::class, "evaluateN1"]);
//     Route::post("approuved", [ObjectifController::class, "approuved"]);
//     Route::post("rapport", [ObjectifController::class, "report"]);
//     Route::post("saveR", [ObjectifController::class, "saveR"]);
//     Route::get("personnelRecommandation", [ObjectifController::class, "getPersonnelPerDirection"]);
//     Route::post("recommandation", [ObjectifController::class, "recommandated"]);
// });



// /*
// |--------------------------------------------------------------------------
// | Endpoints de l'employé lamda
// |--------------------------------------------------------------------------
// */

// Route::middleware(['jwt.auth', 'role:employe'])->group(function () {

//     Route::get("emp-campagne", [ObjectifController::class, "getCampagne"]);
//     Route::get("objectif", [ObjectifController::class, "getAll"]);
//     Route::get("objectif/{id}", [ObjectifController::class, "getOne"]);
//     Route::post("objectif", [ObjectifController::class, "store"]);
//     Route::put("objectif/{id}", [ObjectifController::class, "update"]);
//     Route::put("soumettre/{id}", [ObjectifController::class, "updateAll"]);
//     Route::delete("objectif/{id}", [ObjectifController::class, "delete"]);
//     Route::post("evaluation", [ObjectifController::class, "auto_evaluate"]);
//     Route::post("evaluation-manager", [ObjectifController::class, "manager_evaluation"]);
//     Route::post("avis", [ObjectifController::class, "saveAvis"]);
//     Route::post("avis-n1", [ObjectifController::class, "saveAvisN1"]);
//     Route::get("evaluated-n1", [ObjectifController::class, "getEvaluatedPersonnel"]);
//     Route::get("evaluated-n2", [ObjectifController::class, "getEvaluatedPersonnel2"]);
//     Route::post("evaluated-n1", [ObjectifController::class, "evaluateN1"]);
//     Route::post("approuved", [ObjectifController::class, "approuved"]);
//     Route::post("rapport", [ObjectifController::class, "report"]);
//     Route::post("saveR", [ObjectifController::class, "saveR"]);
//     Route::get("personnelRecommandation", [ObjectifController::class, "getPersonnelPerDirection"]);
//     Route::post("recommandation", [ObjectifController::class, "recommandated"]);
//     Route::get("objectif-personnel/{id}", [ObjectifController::class, "getObjectifPerPersonnel"]);
// });


/*
|--------------------------------------------------------------------------
| Gestion des Villes
|--------------------------------------------------------------------------
*/
Route::get("ville", [VilleController::class, "getAllVille"]);
Route::get("ville/{id}", [VilleController::class, "getOneVille"]);
Route::post("ville", [VilleController::class, "store"]);
Route::put("ville/{id}", [VilleController::class, "update"]);
Route::delete("ville/{id}", [VilleController::class, "delete"]);

Route::post("loadfile", [VilleController::class, "loadFile"]);
/*
|--------------------------------------------------------------------------
| Gestion des Régions
|--------------------------------------------------------------------------
*/
Route::get("region", [RegionController::class, "getAllRegion"]);
Route::get("region/{id}", [RegionController::class, "getOneRegion"]);
Route::post("region", [RegionController::class, "store"]);
Route::put("region/{id}", [RegionController::class, "update"]);
Route::delete("region/{id}", [RegionController::class, "delete"]);

/*
|--------------------------------------------------------------------------
| Gestion des Départements
|--------------------------------------------------------------------------
*/
Route::get("departement", [DepartController::class, "getAllDepart"]);
Route::post("departement", [DepartController::class, "store"]);
Route::get("departement/{id}", [DepartController::class, "getOneDepart"]);
Route::put("departement/{id}", [DepartController::class, "update"]);
Route::delete("departement/{id}", [DepartController::class, "delete"]);

/*
|--------------------------------------------------------------------------
| Gestion des Arrondissements
|--------------------------------------------------------------------------
*/
Route::get("arrondissement", [ArrondController::class, "getAllArrond"]);
Route::post("arrondissement", [ArrondController::class, "store"]);
Route::get("arrondissement/{id}", [ArrondController::class, "getOneArrond"]);
Route::put("arrondissement/{id}", [ArrondController::class, "update"]);
Route::delete("arrondissement/{id}", [ArrondController::class, "delete"]);

/*
|--------------------------------------------------------------------------
| Gestion des Types de structures
|--------------------------------------------------------------------------
*/
Route::get("typeStructure", [TypeStructController::class, "getAll"]);
Route::post("typeStructure", [TypeStructController::class, "store"]);
Route::get("typeStructure/{id}", [TypeStructController::class, "getOne"]);
Route::put("typeStructure/{id}", [TypeStructController::class, "update"]);
Route::delete("typeStructure/{id}", [TypeStructController::class, "delete"]);
Route::get("somme_type", [TypeStructController::class, "sommeTypeStructure"]);

/*
|--------------------------------------------------------------------------
| Gestion des Structures générales
|--------------------------------------------------------------------------
*/
Route::get("structure", [StructGeneController::class, "getAll"]);
Route::post("structure", [StructGeneController::class, "store"]);
Route::get("structure/{id}", [StructGeneController::class, "getOne"]);
Route::put("structure/{id}", [StructGeneController::class, "update"]);
Route::delete("structure/{id}", [StructGeneController::class, "delete"]);

/*
|--------------------------------------------------------------------------
| Gestion des Stages et/ou séminaires
|--------------------------------------------------------------------------
*/
Route::get("stage", [StageController::class, "getAllStage"]);
Route::post("stage", [StageController::class, "store"]);
Route::get("stage/{id}", [StageController::class, "getOneStage"]);
Route::put("stage/{id}", [StageController::class, "update"]);
Route::delete("stage/{id}", [StageController::class, "delete"]);

/*
|--------------------------------------------------------------------------
| Gestion des Catégories
|--------------------------------------------------------------------------
*/
Route::get("categorie", [CategorieController::class, "getAll"]);
Route::post("categorie", [CategorieController::class, "store"]);
Route::get("categorie/{id}", [CategorieController::class, "getOne"]);
Route::put("categorie/{id}", [CategorieController::class, "update"]);
Route::delete("categorie/{id}", [CategorieController::class, "delete"]);

Route::get("direction", [DirectionController::class, "getAll"]);
Route::post("direction", [DirectionController::class, "store"]);
Route::get("direction/{id}", [DirectionController::class, "getOne"]);
Route::put("direction/{id}", [DirectionController::class, "update"]);
Route::delete("direction/{id}", [DirectionController::class, "delete"]);

/*
|--------------------------------------------------------------------------
| Gestion des Corps
|--------------------------------------------------------------------------
*/
Route::get("corps", [CorpsController::class, "getAll"]);
Route::post("corps", [CorpsController::class, "store"]);
Route::get("corps/{id}", [CorpsController::class, "getOne"]);
Route::put("corps/{id}", [CorpsController::class, "update"]);
Route::delete("corps/{id}", [CorpsController::class, "delete"]);

/*
|--------------------------------------------------------------------------
| Gestion des Grades
|--------------------------------------------------------------------------
*/
Route::get("grade", [GradeController::class, "getAll"]);
Route::post("grade", [GradeController::class, "store"]);
Route::get("grade/{id}", [GradeController::class, "getOne"]);
Route::put("grade/{id}", [GradeController::class, "update"]);
Route::delete("grade/{id}", [GradeController::class, "delete"]);

/*
|--------------------------------------------------------------------------
| Gestion des Nieaux hierarchiques
|--------------------------------------------------------------------------
*/
Route::get("niveau-hierarchique", [NiveauHController::class, "getAll"]);
Route::post("niveau-hierarchique", [NiveauHController::class, "store"]);
Route::get("niveau-hierarchique/{id}", [NiveauHController::class, "getOne"]);
Route::put("niveau-hierarchique/{id}", [NiveauHController::class, "update"]);
Route::delete("niveau-hierarchique/{id}", [NiveauHController::class, "delete"]);

/*
|--------------------------------------------------------------------------
| Gestion des Rangs
|--------------------------------------------------------------------------
*/
Route::get("rang", [RangController::class, "getAll"]);
Route::post("rang", [RangController::class, "store"]);
Route::get("rang/{id}", [RangController::class, "getOne"]);
Route::put("rang/{id}", [RangController::class, "update"]);
Route::delete("rang/{id}", [RangController::class, "delete"]);


/*
|--------------------------------------------------------------------------
| Gestion des Nomminations
|--------------------------------------------------------------------------
*/
Route::get('nommination', [NomminationController::class, 'getAllNommination']);
Route::get('nommination/{id}', [NomminationController::class, 'getOneNommination']);
Route::post('nommination', [NomminationController::class, 'store']);
Route::put('nommination/{id}', [NomminationController::class, 'update']);
Route::delete('nommination/{id}', [NomminationController::class, 'delete']);

/*
|--------------------------------------------------------------------------
| Gestion des Diplomes
|--------------------------------------------------------------------------
*/
Route::get("diplome", [DiplomeController::class, "getAllDiplome"]);
Route::post("diplome", [DiplomeController::class, "store"]);
Route::get("diplome/{id}", [DiplomeController::class, "getOneDiplome"]);
Route::put("diplome/{id}", [DiplomeController::class, "update"]);
Route::delete("diplome/{id}", [DiplomeController::class, "delete"]);

/*
|--------------------------------------------------------------------------
| Gestion des Situation du personnel
|--------------------------------------------------------------------------
*/
Route::get("situation", [SituationController::class, "getAllSituation"]);
Route::post("situation", [SituationController::class, "store"]);
Route::get("situation/{id}", [SituationController::class, "getOneSituation"]);
Route::put("situation/{id}", [SituationController::class, "update"]);
Route::delete("situation/{id}", [SituationController::class, "delete"]);

/*
|--------------------------------------------------------------------------
| Gestion des Campagne d'évaluation
|--------------------------------------------------------------------------
*/
Route::get("campagne", [CampagneController::class, "getAll"]);
Route::post("campagne", [CampagneController::class, "store"]);
Route::get("campagne/{id}", [CampagneController::class, "getOne"]);
Route::put("campagne/{id}", [CampagneController::class, "update"]);
Route::delete("campagne/{id}", [CampagneController::class, "delete"]);


Route::post("add-emp", [CampagneController::class, "addEmployeToCampagne"]);
