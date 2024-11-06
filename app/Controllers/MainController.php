<?php 

namespace App\Controllers;

use Core\DB;

class MainController extends Controller
{
    public function index(): void
    {
        view('pages/index');
    }
}