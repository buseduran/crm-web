<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ForgotPasswordController extends Controller
{
    /**
     * Display the password reset request form.
     */
    public function showLinkRequestForm(): View
    {
        return view('auth.forgot-password');
    }

    /**
     * Handle the password reset request (UI only, no email sending).
     */
    public function sendResetLinkEmail(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email'],
        ]);

        // This is UI only - no actual email sending
        // In a real implementation, this would send a password reset email
        return back()->with('status', 'Şifre sıfırlama bağlantısı gönderildi. (Bu sadece arayüz örneğidir)');
    }
}

