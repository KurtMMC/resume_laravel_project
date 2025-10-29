<?php

use Illuminate\Support\Facades\Route as RouteFacade;
// If you also need the route instance type:
// use Illuminate\Routing\Route as RoutingRoute;

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ResumeController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\PasswordResetController;
use App\Http\Controllers\EmailVerificationController;
use App\Http\Controllers\ProfileController;

// Redirect root to dedicated resume page
RouteFacade::get('/', function(){ return redirect('/resume'); })->middleware('nocache');
// Dedicated resume page (separate from root)
RouteFacade::get('/resume', [ResumeController::class, 'showResume'])->name('resume')->middleware('nocache');

RouteFacade::get('/login', [AuthController::class, 'showLoginForm'])->middleware('nocache');

RouteFacade::post('/login', [AuthController::class, 'login'])->name('login.post');

RouteFacade::get('/logout', function (Illuminate\Http\Request $request) {
    // Flush entire session for safety
    $request->session()->flush();
    // Invalidate and regenerate session ID to prevent fixation/back navigation issues
    $request->session()->invalidate();
    $request->session()->regenerateToken();
    return redirect('/login')->with('success', 'Logged out successfully');
})->middleware('nocache');

RouteFacade::post('/contact/send', [ContactController::class, 'send'])->name('contact.send');

RouteFacade::get('/register', [AuthController::class, 'showRegisterForm'])->middleware('nocache');
RouteFacade::post('/register', [AuthController::class, 'register']);

// Terms & Conditions
RouteFacade::get('/terms', function(){
    return view('terms');
})->name('terms');

RouteFacade::get('/privacy', function(){
    return view('privacy');
})->name('privacy');

// Email verification
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\RateLimiter;

RouteFacade::get('/email/verify/{id}/{hash}', [EmailVerificationController::class, 'verify'])
    ->name('verification.verify')
    ->middleware(['signed', 'throttle:6,1', 'nocache']);

// Interstitial notice after registration
RouteFacade::get('/email/verify/notice', function() {
    return view('auth.verify.notice');
})->name('verification.notice')->middleware('nocache');

// Resend verification link
RouteFacade::get('/email/verify/resend', [EmailVerificationController::class, 'showResendForm'])
    ->name('verification.resend.form');
RouteFacade::post('/email/verify/resend', [EmailVerificationController::class, 'resend'])
    ->name('verification.resend')
    ->middleware('throttle:3,1');

RouteFacade::get('/password/reset', [PasswordResetController::class, 'showLinkRequestForm'])->name('password.request')->middleware('nocache');
RouteFacade::post('/password/email', [PasswordResetController::class, 'sendResetLinkEmail'])->name('password.email');
RouteFacade::get('/password/reset/{token}', [PasswordResetController::class, 'showResetForm'])->name('password.reset')->middleware('nocache');
RouteFacade::post('/password/reset', [PasswordResetController::class, 'reset'])->name('password.update');

use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Models\User;
use Illuminate\Support\Str;

RouteFacade::get('/auth/google', function () {
    return Socialite::driver('google')->redirect();
});

RouteFacade::get('/auth/google/callback', function () {
    $googleUser = Socialite::driver('google')->user();

    // Find or create user
    $user = User::where('email', $googleUser->getEmail())->first();
    if (!$user) {
        $user = User::create([
            'email' => $googleUser->getEmail(),
            'name' => $googleUser->getName(),
            // Optionally set a random password or null
            'password' => Illuminate\Support\Facades\Hash::make(Str::random(16)),
        ]);
    }

    // Log the user in
    Auth::login($user);
    // Set session for compatibility with ResumeController
    session(['logged_in' => true]);

    return redirect('/resume')->with('success', 'Logged in with Google');
});

// Facebook Login removed

// Microsoft Login
RouteFacade::get('/auth/microsoft', function () {
    if (!config('services.microsoft.client_id') || !config('services.microsoft.client_secret') || !config('services.microsoft.redirect')) {
        return redirect('/login')->with('error', 'Microsoft login is not configured.');
    }
    return Socialite::driver('microsoft')->redirect();
});

RouteFacade::get('/auth/microsoft/callback', function () {
    $msUser = Socialite::driver('microsoft')->user();

    $user = User::where('email', $msUser->getEmail())->first();
    if (!$user) {
        $user = User::create([
            'email' => $msUser->getEmail(),
            'name' => $msUser->getName() ?: ($msUser->user['displayName'] ?? 'Microsoft User'),
            'password' => Illuminate\Support\Facades\Hash::make(Str::random(16)),
        ]);
    }
    Auth::login($user);
    session(['logged_in' => true]);
    return redirect('/resume')->with('success', 'Logged in with Microsoft');
});

// Dashboard (Profile editor)
RouteFacade::middleware(['nocache','logged'])->group(function(){
    RouteFacade::get('/dashboard', [ProfileController::class, 'edit'])->name('profile.edit');
    RouteFacade::post('/dashboard', [ProfileController::class, 'update'])->name('profile.update');
    // Friendly aliases for resume editing
    RouteFacade::get('/edit-resume', [ProfileController::class, 'edit'])->name('resume.edit');
    RouteFacade::post('/edit-resume', [ProfileController::class, 'update'])->name('resume.update');
});

// Public resume (no login required)
RouteFacade::get('/public/resume/{id}', [ResumeController::class, 'showPublic'])->name('public.resume');
// Pretty public slug route
RouteFacade::get('/r/{slug}', [ResumeController::class, 'showBySlug'])->name('public.resume.slug');
// Optional query-string form to resemble provided example
RouteFacade::get('/public-resume', function (Illuminate\Http\Request $request) {
    $id = (int) $request->query('id');
    if (!$id) { abort(404); }
    return app(\App\Http\Controllers\ResumeController::class)->showPublic($request, $id);
});

// Guest login: no account, redirect to a public resume view
RouteFacade::get('/guest', function (Illuminate\Http\Request $request) {
    // Mark this session as guest for UI cues on public pages
    $request->session()->put('guest', true);

    // Prefer configured user id, else pick the most recently updated profile
    $preferredId = (int) (config('app.public_resume_user_id') ?? 0);
    $profile = null;
    if ($preferredId > 0) {
        $profile = \App\Models\Profile::where('user_id', $preferredId)->first();
    }
    if (!$profile) {
        $profile = \App\Models\Profile::orderByDesc('updated_at')->first();
    }
    if (!$profile) {
        return redirect('/login')->with('error', 'No public resume is available yet.');
    }

    // Redirect to slug route if available for a clean URL, otherwise by id
    if (!empty($profile->slug)) {
        return redirect()->route('public.resume.slug', $profile->slug);
    }
    return redirect()->route('public.resume', ['id' => $profile->user_id]);
})->name('guest.login')->middleware('nocache');
