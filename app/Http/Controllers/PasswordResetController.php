<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\Rules\Password as PasswordRule;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Models\User;

class PasswordResetController extends Controller
{
    public function showLinkRequestForm() {
        return view('auth.passwords.email');
    }

    public function sendResetLinkEmail(Request $request) {
        $request->validate(['email' => 'required|email']);
        $status = Password::sendResetLink(
            $request->only('email')
        );
        return $status === Password::RESET_LINK_SENT
            ? back()->with('status', __($status))
            : back()->withErrors(['email' => __($status)]);
    }

    public function showResetForm($token) {
        return view('auth.passwords.reset', ['token' => $token]);
    }

    public function reset(Request $request) {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            // Strong password: min 8, mixed case, number, symbol; plus confirmation
            'password' => [
                'required',
                'confirmed',
                PasswordRule::min(8)->mixedCase()->numbers()->symbols(),
            ],
        ], [
            'password.required' => 'Please enter a password.',
            'password.min' => 'Password must be at least 8 characters.',
            'password.confirmed' => 'Passwords do not match.',
        ]);
        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) {
                $user->password = Hash::make($password);
                $user->setRememberToken(Str::random(60));
                $user->save();
            }
        );
        return $status === Password::PASSWORD_RESET
            ? redirect('/login')->with('success', 'Password reset successful!')
            : back()->withErrors(['email' => [__($status)]]);
    }
}
