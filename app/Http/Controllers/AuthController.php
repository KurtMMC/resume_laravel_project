<?php
namespace App\Http\Controllers;

    use Illuminate\Http\Request;
    use Illuminate\Validation\Rules\Password as PasswordRule;
    use Illuminate\Support\Facades\Http;

    class AuthController extends Controller
    {
        public function showLoginForm() {
            return view('login');
        }

        public function login(Request $request) {
            // Basic validation first
            $request->validate([
                'email' => 'required|email',
                'password' => 'required|string',
                'g-recaptcha-response' => 'required|string',
            ], [
                'g-recaptcha-response.required' => 'Please complete the CAPTCHA.',
            ]);

            // Verify reCAPTCHA with Google
            try {
                $response = Http::asForm()->post('https://www.google.com/recaptcha/api/siteverify', [
                    'secret' => config('services.recaptcha.secret_key'),
                    'response' => $request->input('g-recaptcha-response'),
                    'remoteip' => $request->ip(),
                ]);
            } catch (\Throwable $e) {
                return back()->with('error', 'CAPTCHA verification failed. Please try again.');
            }

            if (!$response->ok()) {
                return back()->with('error', 'CAPTCHA service unavailable. Please try again.');
            }

            $verified = (bool) optional($response->json())['success'] ?? false;
            if (!$verified) {
                return back()->withErrors(['g-recaptcha-response' => 'CAPTCHA was not valid. Please try again.'])->withInput($request->except('password'));
            }

            $email = $request->input('email');
            $password = $request->input('password');

            $user = \App\Models\User::where('email', $email)->first();
            if ($user && \Illuminate\Support\Facades\Hash::check($password, $user->password)) {
                // Block login if email not verified
                if (is_null($user->email_verified_at)) {
                    // Send (or re-send) verification link
                    if (method_exists($user, 'sendEmailVerificationNotification')) {
                        $user->sendEmailVerificationNotification();
                    }
                    return back()->with('error', 'Please verify your email. We\'ve sent a new verification link.');
                }
                // Store login in session
                $request->session()->put('logged_in', true);
                $request->session()->put('user_id', $user->id);
                $request->session()->put('user_email', $user->email);
                // Use first name only for greetings
                $firstName = trim(explode(' ', trim($user->name))[0] ?? '');
                $request->session()->put('user_name', $firstName ?: $user->name);

                // Redirect to dedicated resume page
                return redirect('/resume')->with('success', 'Login Successful');
            }

            return back()->with('error', 'Invalid Email or Password');
        }

        public function showRegisterForm() {
            return view('register');
        }

        public function register(Request $request) {
            $request->validate([
                'first_name' => 'required|string|max:100',
                'middle_initial' => 'nullable|string|max:1',
                'last_name' => 'required|string|max:100',
                'email' => 'required|email|unique:users,email',
                // Strong password: min 8, mixed case, number, symbol; plus confirmation
                'password' => [
                    'required',
                    'confirmed',
                    PasswordRule::min(8)->mixedCase()->numbers()->symbols(),
                ],
                'terms' => 'accepted',
            ], [
                'first_name.required' => 'Please enter your first name.',
                'last_name.required' => 'Please enter your last name.',
                'middle_initial.max' => 'Middle initial must be one character.',
                'password.required' => 'Please enter a password.',
                'password.min' => 'Password must be at least 8 characters.',
                'password.confirmed' => 'Passwords do not match.',
                'terms.accepted' => 'You must accept the Terms & Conditions to continue.',
                // Specific messages for mixed case/numbers/symbols are provided by Laravel’s Password rule defaults.
            ]);

            $user = new \App\Models\User();
            // Compose full name as "First M. Last" if middle initial is provided
            $first = trim($request->input('first_name'));
            $mi = trim((string) $request->input('middle_initial'));
            $last = trim($request->input('last_name'));
            $fullName = $first . ' ' . ($mi !== '' ? (strtoupper($mi) . '. ') : '') . $last;
            $user->name = trim($fullName);
            $user->email = $request->input('email');
            $user->password = \Illuminate\Support\Facades\Hash::make($request->input('password'));
            // Record terms acceptance timestamp at registration time
            $user->terms_accepted_at = now();
            $user->save();

            // Send email verification link
            if (method_exists($user, 'sendEmailVerificationNotification')) {
                $user->sendEmailVerificationNotification();
            }

        // Redirect to verification notice with email prefilled for resend
        return redirect()->route('verification.notice')
            ->with('success', 'Account registered successfully. Please verify your email to log in.')
            ->with('unverified_email', $user->email);
        }
    }
