<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AdministratorAuthController extends Controller
{
    public function login(Request $request)
    {
        // To be implemented: validation + login logic
        return "Login submitted for Administrator: " . $request->email;
    }
}
