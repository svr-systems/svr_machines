<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class SaleController extends Controller {
  public function index(Request $req) {
    try {
      return response()->json([
        "ok" => true,
        "msg" => "Registros retornados correctamente",
        "data" => Sale::getItems($req)
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
        "data" => Sale::getItem($id)
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
      $data = Sale::find($id);
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
      $validator = $this->validateData($req, $id);

      if ($validator->fails()) {
        return response()->json([
          "ok" => false,
          "msg" => $validator->errors()->first(),
          "err" => $validator->errors()->first(),
        ], 500);
      }

      if (is_null($id)) {
        $data = new Sale;
        $data->created_by_id = $req->user()->id;
      } else {
        $data = Sale::find($id);
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
      "machine_id" => "numeric|required",
    ];

    return Validator::make(
      $req->all(),
      $rules,
      []
    );
  }

  public function saveData($data, $req) {
    $data->updated_by_id = $req->user()->id;
    $data->machine_date = GenController::filter($req->machine_date, "d");
    $data->pump = GenController::filter($req->pump, "i");
    $data->quantity = GenController::filter($req->quantity, "f");
    $data->amount = GenController::filter($req->amount, "f");
    $data->machine_id = GenController::filter($req->machine_id, "id");
    $data->save();

    return $data;
  }
}
