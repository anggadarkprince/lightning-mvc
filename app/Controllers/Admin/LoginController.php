<?php
namespace App\Controllers\Admin;

use Core\Base\Controller;
use Core\Base\View;

class LoginController extends Controller
{
    /**
     * Login page scaffolding.
     */
    public function index()
    {
        View::render('auth/login');
    }

}