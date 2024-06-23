<?php

namespace App\Http\Controllers;

use App\Models\role;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    public function getAll()
    {
        try {
            $roles = role::all();
            if (count($roles) > 0) {
                return response()->json([
                    "status" => "200",
                    "datas" => $roles
                ], 200);
            } else {
                return response()->json([
                    "status" => "200",
                    "message" => "Aucun role correspondant"
                ], 200);
            }
        } catch (\Throwable $th) {
            return response()->json([
                "status" => "500",
                "message" => "Erreur interne du serveur"
            ], 500);
        }
    }

    public function getOne(Request $request, $id)
    {
        try {
            $role = role::find($id);
            if ($role) {
                return response()->json([
                    "status" => "200",
                    "datas" => $role
                ], 200);
            } else {
                return response()->json([
                    "status" => "200",
                    "message" => "Aucun role correspondant"
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
            $role = role::create(["nom" => $request->nom]);
            return response()->json([
                "status" => "201",
                "datas" => $role
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
            $role = role::find($id);
            if ($role) {
                $role->update(["nom" => $request->nom]);
                return response()->json([
                    "status" => "200",
                    "datas" => $role
                ], 200);
            } else {
                return response()->json([
                    "status" => "200",
                    "message" => "Aucun role correspondant"
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
            $role = role::find($id);
            if ($role) {
                $role->delete();
                return response()->json([
                    "status" => "200",
                    "message" => "Role supprimé avec succès"
                ], 200);
            } else {
                return response()->json([
                    "status" => "200",
                    "message" => "Aucun role correspondant"
                ], 200);
            }
        } catch (\Throwable $th) {
            return response()->json([
                "status" => "500",
                "message" => "Erreur interne du serveur"
            ], 500);
        }
    }
}
