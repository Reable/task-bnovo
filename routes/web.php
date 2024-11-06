<?php

use App\Controllers\MainController;
use Core\Route;

Route::get('/', [MainController::class, 'index']);
