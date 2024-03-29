<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class LogoutController extends Controller
{
    public function logout(){
        auth()->logout();
        return redirect()->route('login')->with('success', 'You have been successfully logged out.');
    }
}
