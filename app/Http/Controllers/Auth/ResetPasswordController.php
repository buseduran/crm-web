<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\View\View;

class ResetPasswordController extends Controller
{
    /**
     * Display the password reset form.
     */
    public function showResetForm(Request $request, string $token = null): View
    {
        return view('auth.reset-password', [
            'token' => $token ?? 'demo-token',
            'email' => $request->email ?? '',
        ]);
    }

    /**
     * Handle the password reset (UI only, no actual reset).
     */
    public function reset(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => ['required', 'min:8', 'confirmed'],
        ]);

        // This is UI only - no actual password reset
        // In a real implementation, this would reset the password
        return redirect()->route('login')->with('status', 'Şifreniz başarıyla sıfırlandı. (Bu sadece arayüz örneğidir)');
    }
}

