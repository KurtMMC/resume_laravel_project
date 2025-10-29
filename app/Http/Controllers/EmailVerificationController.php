<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class EmailVerificationController extends Controller
{
    /**
     * Verify the user's email using a signed URL.
     */
    public function verify(Request $request, $id, $hash)
    {
        $user = User::find($id);
        if (!$user) {
            return redirect('/login')->with('error', 'Invalid verification link.');
        }

        // Validate email hash matches
        if (! hash_equals((string) $hash, sha1($user->email))) {
            return redirect('/login')->with('error', 'Invalid verification link.');
        }

        if ($user->email_verified_at) {
            return redirect('/login')->with('success', 'Email already verified. You can log in.');
        }

        $user->forceFill([
            'email_verified_at' => now(),
        ])->save();

        return redirect('/login')->with('success', 'Email verified successfully. You can now log in.');
    }

    public function showResendForm()
    {
        return view('auth.verify.resend');
    }

    public function resend(Request $request)
    {
        $request->validate(['email' => 'required|email']);
        $user = User::where('email', $request->input('email'))->first();
        // To avoid leaking whether the email exists, always respond success
        if ($user && is_null($user->email_verified_at)) {
            if (method_exists($user, 'sendEmailVerificationNotification')) {
                $user->sendEmailVerificationNotification();
            }
        }
        return back()->with('success', 'If that email is registered and not yet verified, a verification link has been sent.');
    }
}
