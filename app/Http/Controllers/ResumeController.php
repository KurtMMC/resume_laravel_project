<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Profile;

class ResumeController extends Controller
{
    public function showResume(Request $request) {
        if (!$request->session()->get('logged_in')) {
            return redirect('/login')->with('error', 'Please login first!');
        }

        // Load profile for current user (prefer Auth; fallback to session user_id)
        $user = Auth::user();
        if (!$user && $request->session()->has('user_id')) {
            $user = \App\Models\User::find((int) $request->session()->get('user_id'));
        }

        $profile = $user ? Profile::firstOrCreate(['user_id' => $user->id], [
            'name' => $user->name ?? null,
            'email' => $user->email ?? null,
        ]) : null;

        // Build view model strictly from profile/user, not hard-coded content
        $data = [
            'name' => $profile?->name ?? ($user->name ?? 'Your Name'),
            'title' => $profile?->title,
            'address' => $profile?->address,
            'phone' => $profile?->phone,
            'email' => $profile?->email ?? ($user->email ?? null),
            'profile_picture' => $profile?->profile_picture,
            // Collections
            'experiences' => is_array($profile?->experiences) ? $profile->experiences : [],
            'education' => [],
            'skills' => is_array($profile?->skills) ? $profile->skills : [],
            'socials' => is_array($profile?->socials) ? $profile->socials : [],
        ];
        // Normalize education to expected structure
        if (is_array($profile?->education)) {
            foreach ($profile->education as $row) {
                if (is_array($row) && isset($row['level'], $row['details'])) { $data['education'][] = $row; }
                elseif (is_string($row)) { $data['education'][] = ['level' => 'Education', 'details' => e($row)]; }
            }
        }

        return view('resume', $data);
    }

    public function index(Request $request)
    {
        // Delegate to showResume so logic stays in one place
        return $this->showResume($request);
    }

    // Public, read-only resume by user id (no login required)
    public function showPublic(Request $request, int $id)
    {
        $userProfile = Profile::where('user_id', $id)->first();
        if (!$userProfile) {
            abort(404);
        }

        // Build from profile only
        $data = [
            'name' => $userProfile->name,
            'title' => $userProfile->title,
            'address' => $userProfile->address,
            'phone' => $userProfile->phone,
            'email' => $userProfile->email,
            'profile_picture' => $userProfile->profile_picture,
            'experiences' => is_array($userProfile->experiences) ? $userProfile->experiences : [],
            'education' => [],
            'skills' => is_array($userProfile->skills) ? $userProfile->skills : [],
            'socials' => is_array($userProfile->socials) ? $userProfile->socials : [],
            'public_view' => true,
        ];

        // Normalize education to expected structure
        if (is_array($userProfile->education)) {
            foreach ($userProfile->education as $row) {
                if (is_array($row) && isset($row['level'], $row['details'])) {
                    $data['education'][] = $row;
                } elseif (is_string($row)) {
                    $data['education'][] = ['level' => 'Education', 'details' => e($row)];
                }
            }
        }

        return view('resume', $data);
    }

    // Public resume by slug
    public function showBySlug(Request $request, string $slug)
    {
        $userProfile = Profile::where('slug', $slug)->first();
        if (!$userProfile) {
            abort(404);
        }

        $data = [
            'name' => $userProfile->name,
            'title' => $userProfile->title,
            'address' => $userProfile->address,
            'phone' => $userProfile->phone,
            'email' => $userProfile->email,
            'profile_picture' => $userProfile->profile_picture,
            'experiences' => is_array($userProfile->experiences) ? $userProfile->experiences : [],
            'education' => [],
            'skills' => is_array($userProfile->skills) ? $userProfile->skills : [],
            'socials' => is_array($userProfile->socials) ? $userProfile->socials : [],
            'public_view' => true,
        ];

        // Normalize education to expected structure
        if (is_array($userProfile->education)) {
            foreach ($userProfile->education as $row) {
                if (is_array($row) && isset($row['level'], $row['details'])) {
                    $data['education'][] = $row;
                } elseif (is_string($row)) {
                    $data['education'][] = ['level' => 'Education', 'details' => e($row)];
                }
            }
        }

        return view('resume', $data);
    }
}
