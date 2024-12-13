<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\MachineController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\SaleController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

//LOG
Route::group([
  "prefix" => "auth"
], function () {
  Route::post("log_in", [AuthController::class, "logIn"]);

  Route::group(
    [
      "middleware" => "auth:api"
    ],
    function () {
      Route::get("log_out", [AuthController::class, "logOut"]);
    }
  );
});

//AUTH
Route::group(["middleware" => "auth:api"], function () {
  Route::post("sales/pending", [SaleController::class, 'storeMultiple']);
  Route::apiResource("sales", SaleController::class);
  Route::apiResource("machines", MachineController::class);
  Route::apiResource("users", UserController::class);

  //CATALOGS
  Route::get("roles", [RoleController::class, "index"]);
});
