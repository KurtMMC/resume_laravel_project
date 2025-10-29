<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Http;

use App\Mail\ContactMail;

class ContactController extends Controller
{
    public function send(Request $request)
    {

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email',
            'message' => 'required|string',
            'phone' => 'required|regex:/^[0-9\-\+\s]{7,15}$/',
            'g-recaptcha-response' => 'required|string',
        ], [
            'g-recaptcha-response.required' => 'Please complete the CAPTCHA.',
        ]);

        // Verify Google reCAPTCHA via server-side request
        try {
            $resp = Http::asForm()->post('https://www.google.com/recaptcha/api/siteverify', [
                'secret' => config('services.recaptcha.secret_key'),
                'response' => $request->input('g-recaptcha-response'),
                'remoteip' => $request->ip(),
            ]);
        } catch (\Throwable $e) {
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'CAPTCHA verification failed. Please try again.',
                    'errors' => ['g-recaptcha-response' => ['CAPTCHA verification failed. Please try again.']]
                ], 422);
            }
            return back()->withErrors(['g-recaptcha-response' => 'CAPTCHA verification failed. Please try again.'])->withInput();
        }

        if (!$resp->ok()) {
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'CAPTCHA service unavailable. Please try again.',
                    'errors' => ['g-recaptcha-response' => ['CAPTCHA service unavailable. Please try again.']]
                ], 422);
            }
            return back()->withErrors(['g-recaptcha-response' => 'CAPTCHA service unavailable. Please try again.'])->withInput();
        }

        $ok = (bool) optional($resp->json())['success'] ?? false;
        if (!$ok) {
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'CAPTCHA was not valid. Please try again.',
                    'errors' => ['g-recaptcha-response' => ['CAPTCHA was not valid. Please try again.']]
                ], 422);
            }
            return back()->withErrors(['g-recaptcha-response' => 'CAPTCHA was not valid. Please try again.'])->withInput();
        }

        // Example: send email or save to database
        try {
            Mail::to('cantillepskurt@gmail.com')->send(new ContactMail($validated));
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Your message has been sent!']);
            }
            return back()->with('contact_success', 'Your message has been sent!');
        } catch (\Exception $e) {
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Mail sending failed.',
                    'errors' => ['mail' => ['Mail sending failed: ' . $e->getMessage()]]
                ], 500);
            }
            return back()->withErrors(['mail' => 'Mail sending failed: ' . $e->getMessage()]);
        }
    }
}
