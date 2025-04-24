<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WebMasterAuthController extends Controller
{
    public function showLoginForm()
    {
        return view('pages.auth.webmaster-login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'min:6'],
        ]);

        if (Auth::attempt($request->only('email', 'password'))) {
            $user = Auth::user();
            if ($user->role === 'webmaster') {
                return redirect()->route('admin.dashboard');
            } else {
                Auth::logout();
                return redirect()->route('login.webmaster')->withErrors(['access' => 'Access denied']);
            }
        }

        return redirect()->route('login.webmaster')->withErrors(['login' => 'Invalid credentials']);
    }
}
