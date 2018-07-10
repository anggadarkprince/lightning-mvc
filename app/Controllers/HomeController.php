<?php
namespace App\Controllers;

use Core\Base\Controller;
use Core\Base\View;

class HomeController extends Controller
{
    /**
     * Home page scaffolding.
     */
    public function index()
    {
        View::render('home/index', ['author' => '@anggadarkprince']);
    }

}