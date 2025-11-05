<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class LogoutController extends Controller
{
    /**
     * Handle user logout
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function logout(Request $request)
    {
        // Step 1: Clear custom session data FIRST (before invalidate)
        $request->session()->forget([
            'user_id',
            'user_name', 
            'user_email',
            'user_role',
            'user_role_name',
            'user_status'
        ]);
        
        // Step 2: Logout user from guard
        Auth::guard('web')->logout();
        
        // Step 3: Invalidate the session
        $request->session()->invalidate();
        
        // Step 4: Regenerate CSRF token for new session
        $request->session()->regenerateToken();
        
        return redirect('/login')->with('success', 'Logout berhasil');
    }
}
