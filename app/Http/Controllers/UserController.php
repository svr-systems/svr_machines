<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller {
  public function index(Request $req) {
    try {
      return response()->json([
        "ok" => true,
        "msg" => "Registros retornados correctamente",
        "data" => User::getItems($req)
      ], 200);
    } catch (\Throwable $err) {
      return response()->json([
        "ok" => false,
        "msg" => "Error. Contacte al equipo de desarrollo",
        "err" => "ERROR => " . $err
      ], 500);
    }
  }

  public function store(Request $req) {
    return $this->storeUpdate($req, null);
  }

  public function show($id) {
    try {
      return response()->json([
        "ok" => true,
        "msg" => "Registro retornado correctamente",
        "data" => User::getItem($id)
      ], 200);
    } catch (\Throwable $th) {
      return response()->json([
        "ok" => false,
        "msg" => "Error. Contacte al equipo de desarrollo",
        "err" => "ERROR => " . $th
      ], 500);
    }
  }

  public function update(Request $req, $id) {
    return $this->storeUpdate($req, $id);
  }

  public function destroy($id) {
    DB::beginTransaction();
    try {
      $data = User::find($id);
      $data->active = false;
      $data->save();

      DB::commit();
      return response()->json([
        "ok" => true,
        "msg" => "Registro eliminado correctamente"
      ], 200);
    } catch (\Throwable $th) {
      DB::rollback();
      return response()->json([
        "ok" => false,
        "msg" => "Error. Contacte al equipo de desarrollo",
        "err" => "ERROR => " . $th
      ], 500);
    }
  }

  public function storeUpdate($req, $id) {
    DB::beginTransaction();
    try {
      $req->merge(
        [
          "email" => GenController::filter($req->email, "l"),
        ],
      );

      $validator = $this->validateData($req, $id);

      if ($validator->fails()) {
        return response()->json([
          "ok" => false,
          "msg" => $validator->errors()->first(),
          "err" => $validator->errors()->first(),
        ], 500);
      }

      if (is_null($id)) {
        $data = new User;
        $data->password = bcrypt(trim($req->password));
        $data->created_by_id = $req->user()->id;
      } else {
        $data = User::find($id);
      }

      $data = $this->saveData($data, $req);

      DB::commit();
      return response()->json([
        "ok" => true,
        "msg" => "Registro " . (is_null($id) ? "agregado" : "editado") . " correctamente"
      ], 200);
    } catch (\Throwable $th) {
      DB::rollback();
      return response()->json([
        "ok" => false,
        "msg" => "Error. Contacte al equipo de desarrollo",
        "err" => "ERROR => " . $th
      ], 500);
    }
  }

  public function validateData($req, $id) {
    $rules = [
      "name" => "string|required|min:2|max:95",
      "email" => "string|required|min:2|max:65|unique:users" . ($id ? ",email," . $id : ""),
      "role_id" => "numeric|required",
    ];

    if (is_null($id)) {
      array_push($rules, ["password" => "string" . ($id ? "|required" : "")]);
    }

    return Validator::make(
      $req->all(),
      $rules,
      [
        "email.unique" => "El Correo elec. ya ha sido registrado",
      ]
    );
  }

  public function saveData($data, $req) {
    $data->updated_by_id = $req->user()->id;
    $data->name = GenController::filter($req->name, "U");
    $data->email = $req->email;
    $data->role_id = GenController::filter($req->role_id, "id");
    $data->save();

    return $data;
  }
}
