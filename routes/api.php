<?php

use App\Controllers\GuestController;
use Core\Route;

$r = new Route();

Route::get   ("/api/user", [GuestController::class, "get"   ]);
Route::post  ("/api/user", [GuestController::class, "create"]);
Route::patch ("/api/user", [GuestController::class, "update"]);
Route::delete("/api/user", [GuestController::class, "delete"]);
