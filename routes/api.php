<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\RoleController;
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
  //CATALOGS
  Route::get("roles", [RoleController::class, "index"]);
});
