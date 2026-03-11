<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminAuthController extends Controller
{
    public function showLogin()
    {
        if (session('admin_logged_in')) {
            return redirect()->route('admin.dashboard');
        }
        return view('admin.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ]);

        // Simple credential check against config/admin.php
        $adminEmail    = config('admin.email', 'admin@store.com');
        $adminPassword = config('admin.password', 'admin123');

        if ($request->email === $adminEmail && $request->password === $adminPassword) {
            session(['admin_logged_in' => true, 'admin_email' => $request->email]);
            return redirect()->route('admin.dashboard');
        }

        return back()->withErrors(['email' => 'Invalid credentials.'])->withInput();
    }

    public function logout(Request $request)
    {
        $request->session()->forget(['admin_logged_in', 'admin_email']);
        return redirect()->route('admin.login');
    }
}