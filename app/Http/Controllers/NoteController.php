<?php

namespace App\Http\Controllers;

use App\Models\note;
use App\Models\objectif;
use Illuminate\Http\Request;
use stdClass;

class NoteController extends Controller
{
    public function getAll()
    {
        try {
            $notes = note::all();
            if (count($notes) > 0) {
                $result = array();
                foreach ($notes as $note) {
                    $objectif = objectif::find($note->id_objectif);
                    $newNote = new stdClass;
                    $newNote->id = $note->id;
                    $newNote->valeur = $note->valeur;
                    $newNote->objectif = $objectif;

                    array_push($result, $newNote);
                }
                return response()->json([
                    'datas' => $result
                ], 200);
            }
            return response()->json([
                'message' => 'No Data Found'
            ], 200);
        } catch (\Throwable $th) {
            return response()->json(["message" => "Error : " . $th->getMessage()], 500);
        }
    }

    public function getOne($id)
    {
        try {
            $note = note::find($id);
            if ($note) {
                $objectif = objectif::find($note->id_objectif);
                $newNote = new stdClass;
                $newNote->id = $note->id;
                $newNote->valeur = $note->valeur;
                $newNote->objectif = $objectif;
                return response()->json([
                    'datas' => $newNote
                ], 200);
            }
            return response()->json([
                'message' => 'No Data Found'
            ], 200);
        } catch (\Throwable $th) {
            return response()->json(["message" => "Error : " . $th->getMessage()], 500);
        }
    }

    public function store(Request $request)
    {
        $request->validate([
            "valeur" => "required",
            "objectif" => "required",
            "personnel" => "nullable"
        ]);

        try {
            $note = note::create([
                "valeur" => $request->valeur,
                "id_objectif" => $request->objectif,
                "id_personnel" => $request->personnel
            ]);
            return response()->json([
                'datas' => $note
            ], 201);
        } catch (\Throwable $th) {
            return response()->json(["message" => "Error : " . $th->getMessage()], 500);
        }
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            "valeur" => "required",
            "objectif" => "required",
            "personnel" => "nullable"
        ]);

        try {
            $note = note::find($id) ;
            if($note) {
                $note->update([
                    "valeur" => $request->valeur,
                    "id_objectif" => $request->objectif,
                    "id_personnel" => $request->personnel
                ]);
                return response()->json([
                    'message' => "Note mise à jour",
                    'datas' => $note,
                ], 200);
            }
            return response()->json(["message" => "Note introuvable"], 200);
        } catch (\Throwable $th) {
            return response()->json(["message" => "Error : " . $th->getMessage()], 500);
        }
    }

    public function delete($id)
    {
        try {
            $note = note::find($id);
            if ($note) {
                $note->delete();
            }
            return response()->json([
                'message' => 'Data Deleted Successfully'
            ], 200);
        } catch (\Throwable $th) {
            return response()->json(["message" => "Error : " . $th->getMessage()], 500);
        }
    }
}
