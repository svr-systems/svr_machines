<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller {
  public function logIn(Request $req) {
    try {
      if (
        !Auth::attempt([
          "email" => $req->email,
          "password" => $req->password
        ])
      ) {
        return response()->json([
          "ok" => false,
          "msg" => "Datos de acceso inválidos",
          "err" => null
        ], 200);
      }

      $token = Auth::user()->createToken("authToken")->accessToken;
      $user = User::find(Auth::id(), [
        "id",
        "name",
        "email",
        "role_id",
      ]);

      $user->role = Role::find($user->role_id, ["name"]);

      return response()->json([
        "ok" => true,
        "msg" => "Datos de acceso validos",
        "data" => [
          "auth" => true,
          "token" => $token,
          "user" => $user
        ]
      ], 200);
    } catch (\Throwable $th) {
      return response()->json([
        "ok" => false,
        "msg" => "Error. Contacte al equipo de desarrollo",
        "err" => "ERROR => " . $th
      ], 200);
    }
  }

  public function logOut(Request $req) {
    try {
      $req->user()->token()->revoke();

      return response()->json([
        "ok" => true,
        "msg" => "Sesión finalizada correctamente"
      ], 200);
    } catch (\Throwable $th) {
      return response()->json([
        "ok" => false,
        "msg" => "Error. Contacte al equipo de desarrollo",
        "err" => "ERROR => " . $th
      ], 200);
    }
  }
}
