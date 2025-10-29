<?php

namespace App\Http\Controllers;

use App\Models\Profile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class ProfileController extends Controller
{
    public function edit()
    {
        $user = Auth::user();
        if (!$user) {
            $uid = session('user_id');
            if ($uid) { $user = \App\Models\User::find((int) $uid); }
        }
        if (!$user) { return redirect('/login'); }
        $profile = Profile::firstOrCreate(['user_id' => $user->id], [
            'name' => $user->name,
            'email' => $user->email,
        ]);
        if (!$profile->slug) {
            $base = Str::slug($profile->name ?: ($user->name ?: 'user'));
            $candidate = $base;
            $i = 1;
            while (Profile::where('slug', $candidate)->where('id', '!=', $profile->id)->exists()) {
                $candidate = $base.'-'.$i;
                $i++;
            }
            $profile->slug = $candidate;
            $profile->save();
        }
        return view('dashboard', ['profile' => $profile]);
    }

    public function update(Request $request)
    {
        $user = Auth::user();
        if (!$user) {
            $uid = session('user_id');
            if ($uid) { $user = \App\Models\User::find((int) $uid); }
        }
        if (!$user) { return redirect('/login'); }

        $data = $request->validate([
            'name' => ['nullable','string','max:80'],
            'title' => ['nullable','string','max:100'],
            'address' => ['nullable','string','max:120'],
            'phone' => ['nullable','string','max:24'],
            'email' => ['nullable','email','max:254'],
            'slug' => ['nullable','string','max:60','regex:/^[a-z0-9]+(?:-[a-z0-9]+)*$/'],
            'profile_picture' => ['nullable','image','max:5120'], // up to ~5MB
            // Textareas submit strings; we'll normalize below
            'experiences' => ['nullable','string'],
            'education' => ['nullable','string'],
            'skills' => ['nullable','string'],
            'socials' => ['nullable','string'],
            // New structured education fields (repeater)
            'education_items' => ['sometimes','array','max:10'],
            'education_items.*.level' => ['nullable','string','max:120'],
            'education_items.*.description' => ['nullable','string','max:300'],
            'education_items.*.address' => ['nullable','string','max:120'],
            'education_items.*.year' => ['nullable','string','max:25','regex:/^\d{4}(?:[–-](?:\d{4}|Present))?$/i'],
            // New structured experience fields (repeater)
            'experience_items' => ['sometimes','array','max:10'],
            'experience_items.*.title' => ['nullable','string','max:100'],
            'experience_items.*.company' => ['nullable','string','max:100'],
            'experience_items.*.description' => ['nullable','string','max:300'],
            'experience_items.*.address' => ['nullable','string','max:120'],
            'experience_items.*.period' => ['nullable','string','max:25','regex:/^\d{4}(?:[–-](?:\d{4}|Present))?$/i'],
            // New structured skills
            'skill_items' => ['sometimes','array','max:30'],
            'skill_items.*.name' => ['nullable','string','max:50'],
            // New structured socials
            'social_items' => ['sometimes','array','max:10'],
            'social_items.*.platform' => ['nullable','string','max:50'],
            'social_items.*.url' => ['nullable','string','max:2048','regex:/^https?:\/\//i'],
            // New structured attachments
            'attachment_items' => ['sometimes','array','max:10'],
            'attachment_items.*.label' => ['nullable','string','max:120'],
            'attachment_items.*.url' => ['nullable','string','max:2048','regex:/^https?:\/\//i'],
        ]);

        $profile = Profile::firstOrCreate(['user_id' => $user->id]);

        // Normalize inputs: split textareas into lines
        if (isset($data['experiences']) && is_string($data['experiences'])) {
            $lines = preg_split('/\r?\n/', $data['experiences']);
            $data['experiences'] = array_values(array_filter(array_map('trim', $lines), fn($v)=> $v !== ''));
        }

        // Experience: prefer structured repeater if provided; else use legacy textarea lines
        if ($request->has('experience_items')) {
            $items = (array) $request->input('experience_items');
            $out = [];
            foreach ($items as $item) {
                $title = isset($item['title']) ? trim((string)$item['title']) : '';
                $company = isset($item['company']) ? trim((string)$item['company']) : '';
                $desc = isset($item['description']) ? trim((string)$item['description']) : '';
                $addr = isset($item['address']) ? trim((string)$item['address']) : '';
                $period = isset($item['period']) ? trim((string)$item['period']) : '';
                if ($title === '' && $company === '' && $desc === '' && $addr === '' && $period === '') { continue; }
                $out[] = [
                    'title' => $title,
                    'company' => $company,
                    'details' => nl2br(e($desc)),
                    'address' => $addr,
                    'period' => $period,
                ];
            }
            $data['experiences'] = $out;
        } elseif ($request->boolean('experience_items_present')) {
            $data['experiences'] = [];
        }

        // Education: prefer structured repeater if provided; otherwise support legacy textarea
        if ($request->has('education_items')) {
            $items = (array) $request->input('education_items');
            $out = [];
            foreach ($items as $item) {
                $level = isset($item['level']) ? trim((string)$item['level']) : '';
                $desc  = isset($item['description']) ? trim((string)$item['description']) : '';
                $addr  = isset($item['address']) ? trim((string)$item['address']) : '';
                $year  = isset($item['year']) ? trim((string)$item['year']) : '';
                if ($level === '' && $desc === '' && $addr === '' && $year === '') { continue; }
                $out[] = [
                    'level' => $level,
                    'details' => nl2br(e($desc)),
                    'address' => $addr,
                    'year' => $year,
                ];
            }
            $data['education'] = $out;
        } elseif ($request->boolean('education_items_present')) {
            // Repeater is present but no items submitted → clear education
            $data['education'] = [];
        } elseif (isset($data['education']) && is_string($data['education'])) {
            // Legacy textarea: support "Level: details" per line; otherwise keep as plain string line
            $lines = preg_split('/\r?\n/', $data['education']);
            $out = [];
            foreach ($lines as $line) {
                $line = trim($line);
                if ($line === '') continue;
                if (strpos($line, ':') !== false) {
                    [$level, $details] = array_map('trim', explode(':', $line, 2));
                    $safeDetails = nl2br(e($details));
                    $out[] = ['level' => $level, 'details' => $safeDetails];
                } else {
                    $out[] = $line;
                }
            }
            $data['education'] = $out;
        }

        // skills: now plain lines (drop percentage model)
        if ($request->has('skill_items')) {
            $items = (array) $request->input('skill_items');
            $skills = [];
            foreach ($items as $it) {
                $name = isset($it['name']) ? trim((string)$it['name']) : '';
                if ($name !== '') $skills[] = $name;
            }
            $data['skills'] = $skills;
        } elseif ($request->boolean('skill_items_present')) {
            $data['skills'] = [];
        } elseif (isset($data['skills']) && is_string($data['skills'])) {
            $lines = preg_split('/\r?\n/', $data['skills']);
            $skills = array_values(array_filter(array_map('trim', $lines), fn($v) => $v !== ''));
            $data['skills'] = $skills;
        }

        // socials: key:url lines
        if ($request->has('social_items')) {
            $items = (array) $request->input('social_items');
            $socials = [];
            foreach ($items as $it) {
                $platform = isset($it['platform']) ? trim((string)$it['platform']) : '';
                $url = isset($it['url']) ? trim((string)$it['url']) : '';
                if ($platform !== '' && $url !== '') { $socials[$platform] = $url; }
            }
            $data['socials'] = $socials;
        } elseif ($request->boolean('social_items_present')) {
            $data['socials'] = [];
        } elseif (isset($data['socials']) && is_string($data['socials'])) {
            $socials = [];
            foreach (preg_split('/\r?\n/', $data['socials']) as $line) {
                if (!trim($line)) continue;
                [$k, $v] = array_pad(array_map('trim', explode(':', $line, 2)), 2, null);
                if ($k && $v) { $socials[$k] = $v; }
            }
            $data['socials'] = $socials;
        }

        // attachments: array of {label, url}
        if ($request->has('attachment_items')) {
            $items = (array) $request->input('attachment_items');
            $attachments = [];
            foreach ($items as $it) {
                $label = isset($it['label']) ? trim((string)$it['label']) : '';
                $url = isset($it['url']) ? trim((string)$it['url']) : '';
                if ($url !== '') { $attachments[] = ['label' => $label ?: 'Attachment', 'url' => $url]; }
            }
            $data['attachments'] = $attachments;
        } elseif ($request->boolean('attachment_items_present')) {
            $data['attachments'] = [];
        }

        // Handle slug uniqueness if provided/changed
        if (!empty($data['slug'])) {
            $base = Str::slug($data['slug']);
            if ($base === '') { $base = 'user-'.$user->id; }
            $candidate = $base;
            $i = 1;
            while (Profile::where('slug', $candidate)->where('id', '!=', $profile->id)->exists()) {
                $candidate = $base.'-'.$i;
                $i++;
            }
            $data['slug'] = $candidate;
        }

        // Handle profile picture upload
        if ($request->hasFile('profile_picture')) {
            $file = $request->file('profile_picture');
            $publicPath = null;
            try {
                // Try lightweight square-crop + resize/compress with GD (center crop → 512x512 JPEG)
                $raw = @file_get_contents($file->getRealPath());
                $src = $raw !== false ? @imagecreatefromstring($raw) : false;
                if ($src !== false) {
                    $w = imagesx($src); $h = imagesy($src);
                    // Center square crop from the longer dimension
                    $side = max(1, min($w, $h));
                    $srcX = (int) max(0, floor(($w - $side) / 2));
                    $srcY = (int) max(0, floor(($h - $side) / 2));
                    $target = 512; // final output dimension
                    $dst = imagecreatetruecolor($target, $target);
                    imagecopyresampled($dst, $src, 0, 0, $srcX, $srcY, $target, $target, $side, $side);
                    ob_start();
                    imagejpeg($dst, null, 85);
                    $jpeg = ob_get_clean();
                    imagedestroy($dst);
                    imagedestroy($src);
                    $filename = 'profile_' . $user->id . '_' . time() . '.jpg';
                    \Illuminate\Support\Facades\Storage::put('public/profile_pictures/' . $filename, $jpeg);
                    $publicPath = 'storage/profile_pictures/' . $filename;
                }
            } catch (\Throwable $e) {
                // Fallback to storing original if processing fails
            }
            if (!$publicPath) {
                $path = $file->store('public/profile_pictures');
                $publicPath = str_replace('public/', 'storage/', $path);
            }
            // Delete old file if exists and is under storage path
            if (!empty($profile->profile_picture) && str_starts_with($profile->profile_picture, 'storage/')) {
                $old = str_replace('storage/', 'public/', $profile->profile_picture);
                try { \Illuminate\Support\Facades\Storage::delete($old); } catch (\Throwable $e) {}
            }
            $data['profile_picture'] = $publicPath;
        }

        $profile->fill(array_merge($data, [ 'is_public' => true ]))->save();

        // Redirect after saving: prefer explicit target, else go to Resume page
        $to = trim((string) $request->input('redirect_to', ''));
        if ($to !== '') {
            return redirect()->to($to)->with('success', 'Profile updated');
        }
        return redirect()->route('resume')->with('success', 'Profile updated');
    }
}
