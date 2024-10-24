<?php

use App\Http\Controllers\AuthController;
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
