<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Resume</title>
    @vite(['resources/css/site.css'])
    @vite(['resources/js/theme-auto.js'])
</head>
<body>
<!-- Side nav mirrors resume nav -->
<div class="side-nav" aria-label="Page navigation">
    <a id="nav-back-link" href="{{ route('resume') }}">
        <svg class="icon" viewBox="0 0 24 24" aria-hidden="true"><path d="M15 18l-6-6 6-6"></path></svg>
        Back
    </a>
    <div class="side-nav-title">Menu</div>
    <a href="#about">About</a>
    <a href="#experience-education">Experience &amp; Education</a>
    <a href="#skills">Skills</a>
    <a href="#attachments">Attachments</a>
    <a href="#socials">Socials</a>
    <hr style="margin:10px 0; border:0; border-top:1px solid #e2e8f0;">
    <button type="button" class="nav-btn dark-toggle" onclick="toggleDarkMode()">
        <svg class="icon" viewBox="0 0 24 24" aria-hidden="true"><path d="M21 12.8A9 9 0 1 1 11.2 3 7 7 0 0 0 21 12.8z"></path></svg>
        Dark Mode
    </button>
    <button type="button" class="nav-btn" id="save-changes-btn" form="edit-resume-form">
        <svg class="icon" viewBox="0 0 24 24" aria-hidden="true"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"></path><path d="M17 21v-8H7v8"></path><path d="M7 3v5h8"></path></svg>
        Save Changes
    </button>
</div>

<!-- Main edit form laid out like resume -->
<form id="edit-resume-form" method="post" action="{{ route('profile.update') }}" enctype="multipart/form-data" data-resume-url="{{ route('resume') }}">
@csrf
<input type="hidden" name="redirect_to" id="redirect_to_input" value="{{ route('resume') }}" />

<header id="about" style="display:flex; gap:20px; align-items:center; justify-content:space-between; position:relative;">
    <div class="avatar-overlap" style="position:relative; text-align:center; z-index:3;">
        <div class="avatar-frame" aria-label="Profile image" style="width:180px; height:180px; border-radius:50%; background:#e2e8f0; color:#64748b; display:flex; align-items:center; justify-content:center; overflow:hidden; margin:0 auto;">
            @if(!empty($profile->profile_picture))
                <img id="profile-picture-preview" src="{{ asset($profile->profile_picture) }}" alt="Profile picture" style="width:100%; height:100%; object-fit:cover; border-radius:50%; display:block;" />
            @else
                <img id="profile-picture-preview" src="" alt="Profile picture" style="width:100%; height:100%; object-fit:cover; border-radius:50%; display:none;" />
                <svg id="profile-picture-fallback" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true" style="width:100%; height:100%; padding:16px; box-sizing:border-box;">
                    <path d="M12 12c2.761 0 5-2.239 5-5s-2.239-5-5-5-5 2.239-5 5 2.239 5 5 5zm0 2c-4.418 0-8 2.239-8 5v1h16v-1c0-2.761-3.582-5-8-5z"/>
                </svg>
            @endif
        </div>
        <div style="margin-top:25px; position:relative; z-index:2;">
            <button type="button" class="btn btn-secondary" onclick="document.getElementById('profile_picture_input').click()">Upload Photo</button>
            <input id="profile_picture_input" type="file" name="profile_picture" accept="image/*" style="display:none;" />
        </div>
    </div>
    <div class="about-info" style="flex:1 1 auto;">
        <h1>
            <input type="text" name="name" value="{{ old('name', $profile->name) }}" placeholder="Your Name" style="width:100%; max-width:420px;" maxlength="80" />
        </h1>
        <p><strong>
            <input type="text" name="title" value="{{ old('title', $profile->title) }}" placeholder="Your Title (e.g. Full-Stack Developer)" style="width:100%; max-width:170px;" maxlength="100" />
        </strong></p>
        
        <p>
            <input type="text" name="address" value="{{ old('address', $profile->address) }}" placeholder="City, Country" style="width:100%; max-width:420px;" maxlength="120" />
            <br>
            <svg class="icon" viewBox="0 0 24 24" aria-hidden="true"><path d="M22 16.9v2a2 2 0 0 1-2.2 2c-9.2-1-16-7.8-17-17A2 2 0 0 1 4.8 2h2a2 2 0 0 1 2 1.7c.1.9.3 1.8.7 2.7.2.5.1 1.1-.3 1.5L8 9.8a15 15 0 0 0 6.2 6.2l1.9-1.2c.5-.3 1-.4 1.5-.3 1 .3 1.9.6 2.8.7a2 2 0 0 1 1.6 1.6z"></path></svg>
            <input type="text" name="phone" value="{{ old('phone', $profile->phone) }}" placeholder="Phone" style="max-width:240px;" maxlength="24" />
            |
            <svg class="icon" viewBox="0 0 24 24" aria-hidden="true"><path d="M4 4h16v16H4z"></path><path d="M22 6l-10 7L2 6"/></svg>
            <input type="email" name="email" value="{{ old('email', $profile->email) }}" placeholder="Email" style="max-width:260px;" maxlength="254" />
        </p>
        
        <div style="margin-top:10px;">
            <div style="margin-top:6px; font-size:0.9rem;">
                <div>Public URL Slug</div>
                <input type="text" name="slug" value="{{ old('slug', $profile->slug) }}" placeholder="e.g. john-doe" style="max-width:320px;" maxlength="60" pattern="^[a-z0-9]+(?:-[a-z0-9]+)*$" title="Lowercase letters, numbers and hyphens only (start/end with a letter or number)." />
                <div style="margin-top:4px;">
                    <small>
                        Public link:
                        @if($profile->slug)
                            <a href="{{ route('public.resume.slug', $profile->slug) }}" target="_blank">{{ url('/r/'.$profile->slug) }}</a>
                        @else
                            Will be generated from your name
                        @endif
                    </small>
                </div>
            </div>
        </div>
    </div>
</header>

<div class="two-col" id="experience-education">
    <section id="experience">
        <h2>Experience</h2>
        <p class="section-note">Add your work experiences. Separate fields: Title/Role, Company, Period, Address, Description.</p>
    <div class="panel">
        <div id="experience-repeater" class="repeater" style="display:flex; flex-direction:column; gap:12px;">
            @php
                $expItems = [];
                if (old('experience_items')) {
                    $expItems = old('experience_items');
                } elseif (is_array($profile->experiences)) {
                    foreach ($profile->experiences as $row) {
                        if (is_array($row)) {
                            $expItems[] = [
                                'title' => $row['title'] ?? ($row['role'] ?? ''),
                                'company' => $row['company'] ?? '',
                                'description' => isset($row['details']) ? strip_tags($row['details']) : '',
                                'address' => $row['address'] ?? '',
                                'period' => $row['period'] ?? ($row['year'] ?? ''),
                            ];
                        } elseif (is_string($row)) {
                            $expItems[] = [ 'title' => $row, 'company' => '', 'description' => '', 'address' => '', 'period' => '' ];
                        }
                    }
                }
                if (empty($expItems)) { $expItems = [['title'=>'','company'=>'','description'=>'','address'=>'','period'=>'']]; }
            @endphp
            <template id="exp-row-template">
                <div class="edu-row card" draggable="true" aria-grabbed="false" role="listitem" style="padding:12px; display:flex; flex-direction:column; gap:8px;">
                    <div style="display:flex; gap:8px; flex-wrap:wrap; align-items:flex-start; width:100%;">
                        <button type="button" class="btn-icon drag-handle" title="Drag to reorder" aria-label="Drag to reorder" tabindex="0" style="align-self:center;">
                            <svg viewBox="0 0 24 24" class="icon" aria-hidden="true"><path d="M7 5h0M12 5h0M17 5h0M7 12h0M12 12h0M17 12h0M7 19h0M12 19h0M17 19h0"/></svg>
                        </button>
                        <input type="text" class="exp-title" placeholder="Title/Role (e.g. Senior Developer)" style="flex:1 1 240px; min-width:220px;" maxlength="100" />
                        <input type="text" class="exp-company" placeholder="Company (e.g. Acme Inc.)" style="flex:1 1 220px; min-width:200px;" maxlength="100" />
                        <div style="flex:0 0 260px; min-width:220px; display:flex; flex-direction:column; gap:4px;">
                            <div style="display:flex; gap:6px; align-items:center;">
                                <input type="text" class="exp-period" placeholder="Period (YYYY, YYYY–YYYY, or YYYY–Present)" style="flex:1 1 auto;" maxlength="25" />
                                <label style="display:inline-flex; align-items:center; gap:6px; white-space:nowrap;">
                                    <input type="checkbox" class="exp-current" /> Current
                                </label>
                            </div>
                            <small class="edu-year-hint" aria-live="polite">Format: 2018, 2015–2019, or 2020–Present</small>
                        </div>
                        <div class="edu-controls" aria-label="Row controls" style="display:flex; gap:6px; margin-left:auto;">
                            <button type="button" class="btn-icon exp-up" title="Move up" aria-label="Move up" tabindex="0">
                                <svg viewBox="0 0 24 24" class="icon" aria-hidden="true"><path d="M7 11l5-5 5 5"/></svg>
                            </button>
                            <button type="button" class="btn-icon exp-down" title="Move down" aria-label="Move down" tabindex="0">
                                <svg viewBox="0 0 24 24" class="icon" aria-hidden="true"><path d="M7 13l5 5 5-5"/></svg>
                            </button>
                            <button type="button" class="btn-icon danger exp-remove" title="Remove" aria-label="Remove row" tabindex="0">
                                <svg viewBox="0 0 24 24" class="icon" aria-hidden="true"><path d="M3 6h18"/><path d="M8 6v12"/><path d="M16 6v12"/><path d="M5 6l1-3h12l1 3"/></svg>
                            </button>
                        </div>
                    </div>
                    <input type="text" class="exp-address" placeholder="Address (e.g. City, Country)" maxlength="120" />
                    <input type="text" class="exp-description" placeholder="Description (responsibilities, achievements, tech stack)" maxlength="300" />
                </div>
            </template>
            <div id="exp-rows" class="rows"></div>
            <script type="application/json" id="exp-seed-data">@json($expItems)</script>
            <div>
                <button type="button" class="btn btn-secondary add-btn" id="exp-add">+ Add Experience</button>
            </div>
        </div>
        <!-- Hidden inputs container to submit structured fields -->
        <div id="exp-hidden-inputs" style="display:none;"></div>
        <input type="hidden" name="experience_items_present" value="1" />
    </div>
    </section>

    <section id="education">
        <h2>Educational Attainment</h2>
        <p class="section-note">Add your education entries. Separate fields: Level/Type, Description, Address, School Year.</p>
    <div class="panel">
        <div id="education-repeater" class="repeater" style="display:flex; flex-direction:column; gap:12px;">
            @php
                $eduItems = [];
                if (old('education_items')) {
                    $eduItems = old('education_items');
                } elseif (is_array($profile->education)) {
                    foreach ($profile->education as $row) {
                        if (is_array($row)) {
                            $eduItems[] = [
                                'level' => $row['level'] ?? '',
                                'description' => isset($row['details']) ? strip_tags($row['details']) : '',
                                'address' => $row['address'] ?? '',
                                'year' => $row['year'] ?? '',
                            ];
                        } elseif (is_string($row)) {
                            // Legacy single-line; attempt to split "Level: details"
                            if (strpos($row, ':') !== false) {
                                [$lvl, $desc] = array_map('trim', explode(':', $row, 2));
                                $eduItems[] = ['level' => $lvl, 'description' => $desc, 'address' => '', 'year' => ''];
                            } else {
                                $eduItems[] = ['level' => 'Education', 'description' => trim($row), 'address' => '', 'year' => ''];
                            }
                        }
                    }
                }
                if (empty($eduItems)) { $eduItems = [['level'=>'','description'=>'','address'=>'','year'=>'']]; }
            @endphp
            <template id="edu-row-template">
                <div class="edu-row card" draggable="true" aria-grabbed="false" role="listitem" style="padding:12px; display:flex; flex-direction:column; gap:8px;">
                    <div style="display:flex; gap:8px; flex-wrap:wrap; align-items:flex-start;">
                        <button type="button" class="btn-icon drag-handle" title="Drag to reorder" aria-label="Drag to reorder" tabindex="0" style="align-self:center;">
                            <svg viewBox="0 0 24 24" class="icon" aria-hidden="true"><path d="M7 5h0M12 5h0M17 5h0M7 12h0M12 12h0M17 12h0M7 19h0M12 19h0M17 19h0"/></svg>
                        </button>
                        <input type="text" class="edu-level" placeholder="Level/Type (e.g. BS Computer Science)" style="flex:1 1 240px; min-width:220px;" maxlength="120" />
                        <div style="flex:0 0 260px; min-width:220px; display:flex; flex-direction:column; gap:4px;">
                            <div style="display:flex; gap:6px; align-items:center;">
                                <input type="text" class="edu-year" placeholder="School Year (YYYY, YYYY–YYYY, or YYYY–Present)" maxlength="25" />
                                <label style="display:inline-flex; align-items:center; gap:6px; white-space:nowrap;">
                                    <input type="checkbox" class="edu-current" /> Current
                                </label>
                            </div>
                            <small class="edu-year-hint" aria-live="polite">Format: 2018, 2015–2019, or 2020–Present</small>
                        </div>
                        <div class="edu-controls" aria-label="Row controls" style="display:flex; gap:6px; margin-left:auto;">
                            <button type="button" class="btn-icon edu-up" title="Move up" aria-label="Move up" tabindex="0">
                                <svg viewBox="0 0 24 24" class="icon" aria-hidden="true"><path d="M7 11l5-5 5 5"/></svg>
                            </button>
                            <button type="button" class="btn-icon edu-down" title="Move down" aria-label="Move down" tabindex="0">
                                <svg viewBox="0 0 24 24" class="icon" aria-hidden="true"><path d="M7 13l5 5 5-5"/></svg>
                            </button>
                            <button type="button" class="btn-icon danger edu-remove" title="Remove" aria-label="Remove row" tabindex="0">
                                <svg viewBox="0 0 24 24" class="icon" aria-hidden="true"><path d="M3 6h18"/><path d="M8 6v12"/><path d="M16 6v12"/><path d="M5 6l1-3h12l1 3"/></svg>
                            </button>
                        </div>
                    </div>
                    <input type="text" class="edu-description" placeholder="Description (e.g. University Name, honors, etc.)" maxlength="300" />
                    <input type="text" class="edu-address" placeholder="Address (e.g. City, Country)" maxlength="120" />
                </div>
            </template>
            <div id="edu-rows" class="rows"></div>
            <script type="application/json" id="edu-seed-data">@json($eduItems)</script>
            <div>
                <button type="button" class="btn btn-secondary add-btn" id="edu-add">+ Add Education</button>
            </div>
        </div>
        <!-- Hidden inputs container to submit structured fields -->
        <div id="edu-hidden-inputs" style="display:none;"></div>
        <input type="hidden" name="education_items_present" value="1" />
    </div>
    </section>
</div>

<section id="skills" class="skills">
    <h2>Skills</h2>
    <p class="section-note">Add your skills as individual items. You can reorder them.</p>
    <div class="panel">
    <div id="skills-repeater" class="repeater" style="display:flex; flex-direction:column; gap:12px;">
        @php
            $skillItems = [];
            if (old('skill_items')) {
                $skillItems = array_values(array_map(fn($it)=> is_array($it)?($it['name'] ?? ''): (string)$it, old('skill_items')));
            } elseif (is_array($profile->skills)) {
                $isAssoc = array_keys($profile->skills) !== range(0, count($profile->skills) - 1);
                if ($isAssoc) { $skillItems = array_keys($profile->skills); }
                else { foreach ($profile->skills as $s) { if (is_string($s) && trim($s) !== '') $skillItems[] = $s; } }
            }
            if (empty($skillItems)) { $skillItems = ['']; }
        @endphp
        <template id="skill-row-template">
            <div class="edu-row card" draggable="true" aria-grabbed="false" role="listitem" style="padding:12px; display:flex; flex-wrap:wrap; gap:8px; align-items:flex-start;">
                <button type="button" class="btn-icon drag-handle" title="Drag to reorder" aria-label="Drag to reorder" tabindex="0">
                    <svg viewBox="0 0 24 24" class="icon" aria-hidden="true"><path d="M7 5h0M12 5h0M17 5h0M7 12h0M12 12h0M17 12h0M7 19h0M12 19h0M17 19h0"/></svg>
                </button>
                <div style="flex:2 1 320px; min-width:240px; display:flex; flex-direction:column; gap:4px;">
                    <input type="text" class="skill-name" placeholder="Skill (e.g. Laravel)" maxlength="50" />
                    <small class="edu-year-hint" aria-live="polite">Add one skill per row. Drag to reorder.</small>
                </div>
                <div class="edu-controls" aria-label="Row controls" style="display:flex; gap:6px; margin-left:auto;">
                    <button type="button" class="btn-icon skill-up" title="Move up" aria-label="Move up" tabindex="0">
                        <svg viewBox="0 0 24 24" class="icon" aria-hidden="true"><path d="M7 11l5-5 5 5"/></svg>
                    </button>
                    <button type="button" class="btn-icon skill-down" title="Move down" aria-label="Move down" tabindex="0">
                        <svg viewBox="0 0 24 24" class="icon" aria-hidden="true"><path d="M7 13l5 5 5-5"/></svg>
                    </button>
                    <button type="button" class="btn-icon danger skill-remove" title="Remove" aria-label="Remove row" tabindex="0">
                        <svg viewBox="0 0 24 24" class="icon" aria-hidden="true"><path d="M3 6h18"/><path d="M8 6v12"/><path d="M16 6v12"/><path d="M5 6l1-3h12l1 3"/></svg>
                    </button>
                </div>
            </div>
        </template>
        <div id="skill-rows" class="rows"></div>
        <script type="application/json" id="skill-seed-data">@json($skillItems)</script>
        <div>
            <button type="button" class="btn btn-secondary add-btn" id="skill-add" title="Add a new skill">
                <svg class="icon" viewBox="0 0 24 24" aria-hidden="true"><path d="M12 5v14M5 12h14"/></svg>
                Add Skill
            </button>
        </div>
    <div id="skill-hidden-inputs" style="display:none;"></div>
    <input type="hidden" name="skill_items_present" value="1" />
    </div>
    
</section>

<section id="attachments">
    <h2>Attachments</h2>
    <p class="section-note">Add links to files like your resume PDF, portfolio, or certificates. You can reorder them.</p>
    <div class="panel">
    <div id="attachments-repeater" class="repeater" style="display:flex; flex-direction:column; gap:12px;">
        @php
            $attachmentItems = [];
            if (old('attachment_items')) {
                $attachmentItems = array_values(array_map(fn($it)=> [ 'label' => $it['label'] ?? '', 'url' => $it['url'] ?? '' ], old('attachment_items')));
            } elseif (is_array($profile->attachments)) {
                foreach ($profile->attachments as $att) {
                    if (is_array($att)) {
                        $attachmentItems[] = ['label' => $att['label'] ?? '', 'url' => $att['url'] ?? ''];
                    } elseif (is_string($att)) {
                        $attachmentItems[] = ['label' => '', 'url' => $att];
                    }
                }
            }
            if (empty($attachmentItems)) { $attachmentItems = [['label'=>'','url'=>'']]; }
        @endphp
        <template id="attachment-row-template">
            <div class="edu-row card" draggable="true" aria-grabbed="false" role="listitem" style="padding:12px; display:flex; flex-wrap:wrap; gap:8px; align-items:flex-start;">
                <button type="button" class="btn-icon drag-handle" title="Drag to reorder" aria-label="Drag to reorder" tabindex="0">
                    <svg viewBox="0 0 24 24" class="icon" aria-hidden="true"><path d="M7 5h0M12 5h0M17 5h0M7 12h0M12 12h0M17 12h0M7 19h0M12 19h0M17 19h0"/></svg>
                </button>
                <input type="text" class="attachment-label" placeholder="Label (e.g. Resume PDF)" style="flex:1 1 220px; min-width:180px;" maxlength="120" />
                <div style="flex:2 1 320px; min-width:240px; display:flex; flex-direction:column; gap:4px;">
                    <input type="text" class="attachment-url" placeholder="URL (e.g. https://example.com/resume.pdf)" maxlength="2048" />
                    <small class="edu-year-hint" aria-live="polite">Use full link including https://</small>
                </div>
                <div class="edu-controls" aria-label="Row controls" style="display:flex; gap:6px; margin-left:auto;">
                    <button type="button" class="btn-icon attachment-up" title="Move up" aria-label="Move up" tabindex="0">
                        <svg viewBox="0 0 24 24" class="icon" aria-hidden="true"><path d="M7 11l5-5 5 5"/></svg>
                    </button>
                    <button type="button" class="btn-icon attachment-down" title="Move down" aria-label="Move down" tabindex="0">
                        <svg viewBox="0 0 24 24" class="icon" aria-hidden="true"><path d="M7 13l5 5 5-5"/></svg>
                    </button>
                    <button type="button" class="btn-icon danger attachment-remove" title="Remove" aria-label="Remove row" tabindex="0">
                        <svg viewBox="0 0 24 24" class="icon" aria-hidden="true"><path d="M3 6h18"/><path d="M8 6v12"/><path d="M16 6v12"/><path d="M5 6l1-3h12l1 3"/></svg>
                    </button>
                </div>
            </div>
        </template>
        <div id="attachment-rows" class="rows"></div>
        <script type="application/json" id="attachment-seed-data">@json($attachmentItems)</script>
        <div>
            <button type="button" class="btn btn-secondary add-btn" id="attachment-add">+ Add Attachment</button>
        </div>
    </div>
    <div id="attachment-hidden-inputs" style="display:none;"></div>
    <input type="hidden" name="attachment_items_present" value="1" />
    </div>
    
</section>

<section id="socials">
    <h2>Socials</h2>
    <p class="section-note">Add platform and full URL. You can reorder them.</p>
    <div class="panel">
    <div id="socials-repeater" class="repeater" style="display:flex; flex-direction:column; gap:12px;">
        @php
            $socialItems = [];
            if (old('social_items')) {
                $socialItems = array_values(array_map(fn($it)=> [ 'platform' => $it['platform'] ?? '', 'url' => $it['url'] ?? '' ], old('social_items')));
            } elseif (is_array($profile->socials)) {
                foreach ($profile->socials as $platform => $link) {
                    if (!is_string($platform) || !is_string($link)) continue;
                    $socialItems[] = ['platform' => $platform, 'url' => $link];
                }
            }
            if (empty($socialItems)) { $socialItems = [['platform'=>'','url'=>'']]; }
        @endphp
        <template id="social-row-template">
            <div class="edu-row card" draggable="true" aria-grabbed="false" role="listitem" style="padding:12px; display:flex; flex-wrap:wrap; gap:8px; align-items:flex-start;">
                <button type="button" class="btn-icon drag-handle" title="Drag to reorder" aria-label="Drag to reorder" tabindex="0">
                    <svg viewBox="0 0 24 24" class="icon" aria-hidden="true"><path d="M7 5h0M12 5h0M17 5h0M7 12h0M12 12h0M17 12h0M7 19h0M12 19h0M17 19h0"/></svg>
                </button>
                <input type="text" class="social-platform" placeholder="Platform (e.g. LinkedIn)" style="flex:1 1 200px; min-width:180px;" maxlength="50" />
                <div style="flex:2 1 320px; min-width:240px; display:flex; flex-direction:column; gap:4px;">
                    <input type="text" class="social-url" placeholder="URL (e.g. https://linkedin.com/in/you)" maxlength="2048" />
                    <small class="edu-year-hint" aria-live="polite">Use full link including https://</small>
                </div>
                <div class="edu-controls" aria-label="Row controls" style="display:flex; gap:6px; margin-left:auto;">
                    <button type="button" class="btn-icon social-up" title="Move up" aria-label="Move up" tabindex="0">
                        <svg viewBox="0 0 24 24" class="icon" aria-hidden="true"><path d="M7 11l5-5 5 5"/></svg>
                    </button>
                    <button type="button" class="btn-icon social-down" title="Move down" aria-label="Move down" tabindex="0">
                        <svg viewBox="0 0 24 24" class="icon" aria-hidden="true"><path d="M7 13l5 5 5-5"/></svg>
                    </button>
                    <button type="button" class="btn-icon danger social-remove" title="Remove" aria-label="Remove row" tabindex="0">
                        <svg viewBox="0 0 24 24" class="icon" aria-hidden="true"><path d="M3 6h18"/><path d="M8 6v12"/><path d="M16 6v12"/><path d="M5 6l1-3h12l1 3"/></svg>
                    </button>
                </div>
            </div>
        </template>
        <div id="social-rows" class="rows"></div>
        <script type="application/json" id="social-seed-data">@json($socialItems)</script>
        <div>
            <button type="button" class="btn btn-secondary add-btn" id="social-add">+ Add Social</button>
        </div>
    </div>
    <div id="social-hidden-inputs" style="display:none;"></div>
    <input type="hidden" name="social_items_present" value="1" />
    </div>
</section>



<div id="toast-region" aria-live="polite" aria-atomic="true" style="position: fixed; bottom: 16px; right: 16px; z-index: 1100; display: flex; flex-direction: column; align-items: flex-end; gap: 8px;"></div>
@if (session('success'))
    <script type="application/json" id="toast-success-data">@json(session('success'))</script>
    <script>
    (function(){
        const msg = JSON.parse(document.getElementById('toast-success-data')?.textContent || '""');
        const container = document.getElementById('toast-region');
        if (!container) return;
        const box = document.createElement('div');
        box.className = 'alert alert-success';
        box.style.minWidth = '260px';
        box.style.maxWidth = '420px';
        box.textContent = msg;
        container.appendChild(box);
        // Auto dismiss after ~2.6s with fade animation (matches .alert.fade-out CSS)
        setTimeout(() => {
            box.classList.add('fade-out');
            setTimeout(() => box.remove(), 650);
        }, 2600);
    })();
    </script>
@endif
@if ($errors->any())
    <script type="application/json" id="toast-errors-data">@json($errors->all())</script>
    <script>
    (function(){
        const cont = document.getElementById('toast-region');
        if (!cont) return;
        let errs = [];
        try { errs = JSON.parse(document.getElementById('toast-errors-data')?.textContent || '[]'); } catch(e) { errs = []; }
        errs.forEach((msg, i) => {
            const box = document.createElement('div');
            box.className = 'alert alert-danger';
            box.style.minWidth = '260px';
            box.style.maxWidth = '420px';
            box.textContent = msg;
            cont.appendChild(box);
            setTimeout(() => { box.classList.add('fade-out'); setTimeout(() => box.remove(), 650); }, 3500 + i*150);
        });
    })();
    </script>
@endif

</form>

<!-- Unsaved changes modal -->
<div id="unsaved-modal" class="modal-overlay" aria-hidden="true" role="dialog" aria-modal="true" style="display:none;">
    <div class="modal-backdrop" tabindex="-1"></div>
    <div class="modal-box">
        <h3 style="margin-top:0;">Unsaved changes</h3>
        <p>You have unsaved changes. Would you like to save before going back?</p>
        <div class="modal-actions">
            <button type="button" class="btn btn-secondary" id="unsaved-discard">Discard</button>
            <button type="button" class="btn btn-secondary" id="unsaved-cancel">Cancel</button>
            <button type="button" class="btn btn-primary no-pdf" id="unsaved-save">Save &amp; Go Back</button>
        </div>
    </div>
    
</div>
<script>
function toggleDarkMode() {
    const btn = document.querySelector('.dark-toggle');
    const isDark = document.body.classList.toggle('dark-mode');
    const sun = '<svg class="icon" viewBox="0 0 24 24" aria-hidden="true"><circle cx="12" cy="12" r="4"></circle><path d="M12 2v2M12 20v2M2 12h2M20 12h2M4.9 4.9l1.4 1.4M17.7 17.7l1.4 1.4M4.9 19.1l1.4-1.4M17.7 6.3l1.4-1.4"/></svg>';
    const moon = '<svg class="icon" viewBox="0 0 24 24" aria-hidden="true"><path d="M21 12.8A9 9 0 1 1 11.2 3 7 7 0 0 0 21 12.8z"></path></svg>';
    if (btn) btn.innerHTML = (isDark ? sun + ' Light Mode' : moon + ' Dark Mode');
    try { localStorage.setItem('themePreference', isDark ? 'dark' : 'light'); } catch(e) {}
}
// Initialize button label from current theme
(function(){
    const btn = document.querySelector('.dark-toggle');
    if (!btn) return;
    const isDark = document.body.classList.contains('dark-mode');
    const sun = '<svg class="icon" viewBox="0 0 24 24" aria-hidden="true"><circle cx="12" cy="12" r="4"></circle><path d="M12 2v2M12 20v2M2 12h2M20 12h2M4.9 4.9l1.4 1.4M17.7 17.7l1.4 1.4M4.9 19.1l1.4-1.4M17.7 6.3l1.4-1.4"/></svg>';
    const moon = '<svg class="icon" viewBox="0 0 24 24" aria-hidden="true"><path d="M21 12.8A9 9 0 1 1 11.2 3 7 7 0 0 0 21 12.8z"></path></svg>';
    btn.innerHTML = (isDark ? sun + ' Light Mode' : moon + ' Dark Mode');
})();

// Smooth scrolling helpers with easing for a softer feel
function easeInOutCubic(t){ return t < 0.5 ? 4 * t * t * t : 1 - Math.pow(-2 * t + 2, 3) / 2; }
function smoothScrollToY(targetY, duration = 800){
    if (window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
        window.scrollTo(0, targetY); return;
    }
    const startY = window.pageYOffset || document.documentElement.scrollTop || 0;
    const diff = targetY - startY;
    if (diff === 0 || duration <= 0) { window.scrollTo(0, targetY); return; }
    const start = performance.now();
    function step(now){
        const elapsed = now - start;
        const t = Math.min(1, elapsed / duration);
        const eased = easeInOutCubic(t);
        window.scrollTo(0, startY + diff * eased);
        if (t < 1) requestAnimationFrame(step);
    }
    requestAnimationFrame(step);
}
function smoothScrollIntoView(el, duration = 850, offset = 10){
    if (!el) return;
    const rect = el.getBoundingClientRect();
    const y = rect.top + (window.pageYOffset || document.documentElement.scrollTop || 0) - offset;
    smoothScrollToY(y, duration);
}

// Ensure main Save Changes redirects to the Resume page after saving
(function(){
    const saveBtn = document.getElementById('save-changes-btn');
    const form = document.getElementById('edit-resume-form');
    const redirectInput = document.getElementById('redirect_to_input');
    if (saveBtn && form && redirectInput) {
        saveBtn.addEventListener('click', function(){
            // Set redirect target so after saving we land on Resume page
            const resumeUrl = form.getAttribute('data-resume-url') || (document.getElementById('nav-back-link')?.getAttribute('href')) || '/resume';
            redirectInput.value = resumeUrl;
            // Flag for Resume page to show a success toast after redirect
            try { sessionStorage.setItem('profileUpdated', '1'); } catch(e) {}
            // Programmatic submit for robust cross-browser behavior
            saveBtn.disabled = true; // prevent double-clicks
            if (typeof form.requestSubmit === 'function') {
                form.requestSubmit();
            } else {
                const tmpBtn = document.createElement('button');
                tmpBtn.type = 'submit';
                tmpBtn.style.display = 'none';
                form.appendChild(tmpBtn);
                tmpBtn.click();
                form.removeChild(tmpBtn);
            }
            // Re-enable after a short delay in case client-side validation blocks submit
            setTimeout(() => { saveBtn.disabled = false; }, 1500);
        });
    }
})();

// Smooth scroll for in-page side-nav anchors only
document.addEventListener('click', function(e){
    const link = e.target.closest('.side-nav a[href^="#"]');
    if (!link) return;
    const target = document.querySelector(link.getAttribute('href'));
    if (target) {
        e.preventDefault();
        smoothScrollIntoView(target, 850, 12);
    }
});

// Side nav active section indicator (scrollspy)
(function(){
    const nav = document.querySelector('.side-nav');
    if (!nav) return;
    const links = Array.from(nav.querySelectorAll('a[href^="#"]'));
    if (!links.length) return;
    const targetMap = new Map();
    links.forEach(link => {
        const sel = link.getAttribute('href');
        if (!sel) return;
        const tgt = document.querySelector(sel);
        if (tgt) targetMap.set(tgt, link);
    });
    function setActive(activeLink){
        links.forEach(l => {
            const on = l === activeLink;
            l.classList.toggle('active', on);
            if (on) l.setAttribute('aria-current','page'); else l.removeAttribute('aria-current');
        });
    }
    if ('IntersectionObserver' in window) {
        let current = null;
        const io = new IntersectionObserver((entries) => {
            // consider entries becoming visible; choose highest intersection ratio
            const visible = entries.filter(en => en.isIntersecting);
            if (!visible.length) return;
            visible.sort((a,b) => b.intersectionRatio - a.intersectionRatio);
            const top = visible[0].target;
            const link = targetMap.get(top);
            if (link && link !== current) { current = link; setActive(link); }
        }, { root: null, rootMargin: '-30% 0px -55% 0px', threshold: [0.1, 0.25, 0.5, 0.75, 1] });
        targetMap.forEach((_, tgt) => io.observe(tgt));
        // initial state
        setActive(links[0]);
    } else {
        // Fallback: scroll listener
        function onScroll(){
            const y = window.scrollY;
            let best = null, bestTop = -Infinity;
            targetMap.forEach((link, tgt) => {
                const top = tgt.getBoundingClientRect().top + window.scrollY;
                if (top <= y + window.innerHeight * 0.35 && top > bestTop) { bestTop = top; best = link; }
            });
            if (best) setActive(best);
        }
        window.addEventListener('scroll', onScroll, { passive: true });
        onScroll();
    }
    // Immediate visual feedback on click
    links.forEach(l => l.addEventListener('click', () => setActive(l)));
})();

// Live preview profile picture on file select
(function(){
    const input = document.getElementById('profile_picture_input');
    const img = document.getElementById('profile-picture-preview');
    const fallback = document.getElementById('profile-picture-fallback');
    if (!input || !img) return;
    input.addEventListener('change', function(){
        const file = this.files && this.files[0];
        if (!file) return;
        const url = URL.createObjectURL(file);
        img.src = url;
        img.style.display = 'block';
        if (fallback) fallback.style.display = 'none';
        // Revoke later to free memory
        setTimeout(() => URL.revokeObjectURL(url), 30000);
    });
})();

// Education repeater: dynamic add/remove and hidden input syncing
(function(){
    const container = document.getElementById('education-repeater');
    const rowsWrap = document.getElementById('edu-rows');
    const hidden = document.getElementById('edu-hidden-inputs');
    const addBtn = document.getElementById('edu-add');
    const tpl = document.getElementById('edu-row-template');
    if (!container || !rowsWrap || !hidden || !addBtn || !tpl) return;

    const MAX_EDU = 10;
    function updateLimit(){
        const count = rowsWrap.querySelectorAll('.edu-row').length;
        addBtn.disabled = count >= MAX_EDU;
        addBtn.setAttribute('aria-disabled', addBtn.disabled ? 'true' : 'false');
        addBtn.title = addBtn.disabled ? `Max ${MAX_EDU} items reached` : '+ Add Education';
    }

    function createRow(data){
        const node = tpl.content.firstElementChild.cloneNode(true);
        const level = node.querySelector('.edu-level');
        const desc = node.querySelector('.edu-description');
        const addr = node.querySelector('.edu-address');
        const year = node.querySelector('.edu-year');
        const current = node.querySelector('.edu-current');
        const hint = node.querySelector('.edu-year-hint');
        if (data) {
            level.value = data.level || '';
            desc.value = data.description || '';
            addr.value = data.address || '';
            year.value = data.year || '';
        }
        node.querySelector('.edu-remove').addEventListener('click', function(){
            node.remove();
            updateLimit();
        });
        // Move up/down
        node.querySelector('.edu-up').addEventListener('click', function(){
            const prev = node.previousElementSibling;
            if (prev) rowsWrap.insertBefore(node, prev);
        });
        node.querySelector('.edu-down').addEventListener('click', function(){
            const next = node.nextElementSibling;
            if (next) rowsWrap.insertBefore(next, node);
        });
        // Year validation (YYYY, YYYY–YYYY, or YYYY–Present)
        function isValidYear(val){
            const s = (val || '').trim();
            if (!s) return true; // empty is okay
            if (/^present$/i.test(s)) return true; // allow "Present" alone
            const m = s.match(/^(\d{4})([–-]((\d{4})|Present))?$/i);
            if (!m) return false;
            const y1 = parseInt(m[1], 10);
            if (!m[3]) return y1 >= 1900 && y1 <= 2100;
            if (String(m[3]).toLowerCase() === 'present') return y1 >= 1900 && y1 <= 2100;
            const y2 = parseInt(m[3], 10);
            return y1 >= 1900 && y1 <= 2100 && y2 >= 1900 && y2 <= 2100 && y2 >= y1;
        }
        function updateYearState(){
            const ok = isValidYear(year.value);
            year.classList.toggle('invalid', !ok);
            if (hint) {
                hint.textContent = ok || !year.value ? 'Format: 2018, 2015–2019, 2020–Present, or Present' : 'Enter YYYY, YYYY–YYYY, YYYY–Present, or Present (end ≥ start)';
                hint.style.color = ok || !year.value ? '' : '#b00020';
            }
        }
        year.addEventListener('input', updateYearState);
        // Current toggle sync with year input
        function syncCurrentToYear(){
            const s = (year.value || '').trim();
            const startMatch = s.match(/^(\d{4})/);
            const startYear = startMatch ? startMatch[1] : '';
            if (current && current.checked) {
                if (startYear) year.value = `${startYear}–Present`;
                else year.value = '';
            } else {
                if (/^\d{4}([–-]Present)$/i.test(s)) { year.value = startYear; }
            }
            updateYearState();
        }
        if (current) {
            current.addEventListener('change', syncCurrentToYear);
            const initVal = (year.value||'').trim();
            if (/^[0-9]{4}([–-]Present)$/i.test(initVal) || /^Present$/i.test(initVal)) { current.checked = true; }
        }
        // Keyboard reordering on drag handle
        const handle = node.querySelector('.drag-handle');
        handle.addEventListener('keydown', (e) => {
            if (e.key === 'ArrowUp') { e.preventDefault(); const prev = node.previousElementSibling; if (prev) rowsWrap.insertBefore(node, prev); }
            if (e.key === 'ArrowDown') { e.preventDefault(); const next = node.nextElementSibling; if (next) rowsWrap.insertBefore(next, node); }
        });
        // Initialize state
        updateYearState();
        return node;
    }

    // Seed from server-provided data (rendered as JSON in a script tag)
    const seedData = (function(){
        try {
            const raw = document.getElementById('edu-seed-data');
            if (!raw) return null;
            return JSON.parse(raw.textContent || 'null');
        } catch(e){ return null; }
    })();

    // If seed data exists, render all; else add a single empty row
    if (Array.isArray(seedData) && seedData.length) {
        seedData.forEach(item => rowsWrap.appendChild(createRow(item)));
    } else {
        rowsWrap.appendChild(createRow({}));
    }

    updateLimit();
    addBtn.addEventListener('click', function(){
        if (addBtn.disabled) return;
        rowsWrap.appendChild(createRow({}));
        updateLimit();
    });

    // Before submit: validate and rebuild hidden inputs as education_items[index][field]
    const form = document.getElementById('edit-resume-form');
    form.addEventListener('submit', function(e){
        hidden.innerHTML = '';
        const rows = Array.from(rowsWrap.querySelectorAll('.edu-row'));
        // Validation: ensure no invalid year present
        let hasInvalid = false;
        rows.forEach((row, i) => {
            const level = row.querySelector('.edu-level').value.trim();
            const desc = row.querySelector('.edu-description').value.trim();
            const addr = row.querySelector('.edu-address').value.trim();
            const year = row.querySelector('.edu-year').value.trim();
            // per-row year check
            const yn = row.querySelector('.edu-year');
            const s = (year||'').trim();
            const ok = !s || /^(\d{4})([–-]((\d{4})|Present))?$/i.test(s);
            if (!ok) { yn.classList.add('invalid'); hasInvalid = true; }
            // Skip completely empty rows
            if (!level && !desc && !addr && !year) return;
            const fields = { level, description: desc, address: addr, year };
            Object.entries(fields).forEach(([k,v]) => {
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = `education_items[${i}][${k}]`;
                input.value = v;
                hidden.appendChild(input);
            });
        });
        if (hasInvalid) {
            e.preventDefault();
            // Show an inline alert near the top-right toast region
            const container = document.getElementById('toast-region');
            if (container) {
                const box = document.createElement('div');
                box.className = 'alert alert-danger';
                box.style.minWidth = '260px';
                box.style.maxWidth = '420px';
                box.textContent = 'Please fix invalid School Year formats (use YYYY, YYYY–YYYY, or YYYY–Present).';
                container.appendChild(box);
                setTimeout(() => { box.classList.add('fade-out'); setTimeout(() => box.remove(), 650); }, 3000);
            }
        }
    });
    enableDragAndDrop(rowsWrap);
})();

// Experience repeater: dynamic add/remove/reorder, validation, and hidden input syncing
(function(){
    const container = document.getElementById('experience-repeater');
    const rowsWrap = document.getElementById('exp-rows');
    const hidden = document.getElementById('exp-hidden-inputs');
    const addBtn = document.getElementById('exp-add');
    const tpl = document.getElementById('exp-row-template');
    if (!container || !rowsWrap || !hidden || !addBtn || !tpl) return;

    const MAX_EXP = 10;
    function updateLimit(){
        const count = rowsWrap.querySelectorAll('.edu-row').length;
        addBtn.disabled = count >= MAX_EXP;
        addBtn.setAttribute('aria-disabled', addBtn.disabled ? 'true' : 'false');
        addBtn.title = addBtn.disabled ? `Max ${MAX_EXP} items reached` : '+ Add Experience';
    }

    function createRow(data){
        const node = tpl.content.firstElementChild.cloneNode(true);
        const title = node.querySelector('.exp-title');
        const company = node.querySelector('.exp-company');
        const period = node.querySelector('.exp-period');
        const addr = node.querySelector('.exp-address');
        const desc = node.querySelector('.exp-description');
        const hint = node.querySelector('.edu-year-hint');
        if (data) {
            title.value = data.title || '';
            company.value = data.company || '';
            period.value = data.period || '';
            addr.value = data.address || '';
            desc.value = data.description || '';
        }
    node.querySelector('.exp-remove').addEventListener('click', function(){ node.remove(); updateLimit(); });
        node.querySelector('.exp-up').addEventListener('click', function(){ const prev = node.previousElementSibling; if (prev) rowsWrap.insertBefore(node, prev); });
        node.querySelector('.exp-down').addEventListener('click', function(){ const next = node.nextElementSibling; if (next) rowsWrap.insertBefore(next, node); });
        // Period validation (same rules as year)
        function isValidPeriod(val){
            const s = (val || '').trim();
            if (!s) return true;
            if (/^present$/i.test(s)) return true; // allow "Present" alone
            const m = s.match(/^(\d{4})([–-]((\d{4})|Present))?$/i);
            if (!m) return false;
            const y1 = parseInt(m[1], 10);
            if (!m[3]) return y1 >= 1900 && y1 <= 2100;
            if (String(m[3]).toLowerCase() === 'present') return y1 >= 1900 && y1 <= 2100;
            const y2 = parseInt(m[3], 10);
            return y1 >= 1900 && y1 <= 2100 && y2 >= 1900 && y2 <= 2100 && y2 >= y1;
        }
        function updateState(){
            const ok = isValidPeriod(period.value);
            period.classList.toggle('invalid', !ok);
            if (hint) {
                hint.textContent = ok || !period.value ? 'Format: 2018, 2015–2019, 2020–Present, or Present' : 'Enter YYYY, YYYY–YYYY, YYYY–Present, or Present';
                hint.style.color = ok || !period.value ? '' : '#b00020';
            }
        }
        period.addEventListener('input', updateState);
        // Current toggle syncs with period input
        const current = node.querySelector('.exp-current');
        function syncCurrentToPeriod(){
            const s = (period.value || '').trim();
            const startMatch = s.match(/^(\d{4})/);
            const startYear = startMatch ? startMatch[1] : '';
            if (current.checked) {
                if (startYear) period.value = `${startYear}–Present`;
                else period.value = '';
            } else {
                if (/^\d{4}([–-]Present)$/i.test(s)) { period.value = startYear; }
            }
            updateState();
        }
        current.addEventListener('change', syncCurrentToPeriod);
        // If initial period already ends with Present, check the toggle
    { const v = (period.value||'').trim(); if (/^[0-9]{4}([–-]Present)$/i.test(v) || /^Present$/i.test(v)) { current.checked = true; } }
        // Keyboard reordering on drag handle
        const handle = node.querySelector('.drag-handle');
        handle.addEventListener('keydown', (e) => {
            if (e.key === 'ArrowUp') { e.preventDefault(); const prev = node.previousElementSibling; if (prev) rowsWrap.insertBefore(node, prev); }
            if (e.key === 'ArrowDown') { e.preventDefault(); const next = node.nextElementSibling; if (next) rowsWrap.insertBefore(next, node); }
        });
        updateState();
        return node;
    }

    // Seed data
    const seedData = (function(){ try { return JSON.parse(document.getElementById('exp-seed-data')?.textContent || 'null'); } catch(e){ return null; } })();
    if (Array.isArray(seedData) && seedData.length) seedData.forEach(item => rowsWrap.appendChild(createRow(item)));
    else rowsWrap.appendChild(createRow({}));

    updateLimit();
    addBtn.addEventListener('click', function(){ if (addBtn.disabled) return; rowsWrap.appendChild(createRow({})); updateLimit(); });

    // Submit: validate and serialize
    const form = document.getElementById('edit-resume-form');
    form.addEventListener('submit', function(e){
        hidden.innerHTML = '';
        const rows = Array.from(rowsWrap.querySelectorAll('.edu-row'));
        let hasInvalid = false;
        rows.forEach((row, i) => {
            const title = row.querySelector('.exp-title').value.trim();
            const company = row.querySelector('.exp-company').value.trim();
            const period = row.querySelector('.exp-period').value.trim();
            const addr = row.querySelector('.exp-address').value.trim();
            const desc = row.querySelector('.exp-description').value.trim();
            const yn = row.querySelector('.exp-period');
            const s = (period||'').trim();
            // Accept: YYYY, YYYY–YYYY, or YYYY–Present (case-insensitive)
            const ok = !s || /^(\d{4})([–-]((\d{4})|Present))?$/i.test(s);
            if (!ok) { yn.classList.add('invalid'); hasInvalid = true; }
            if (!title && !company && !period && !addr && !desc) return;
            const fields = { title, company, period, address: addr, description: desc };
            Object.entries(fields).forEach(([k,v]) => {
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = `experience_items[${i}][${k}]`;
                input.value = v;
                hidden.appendChild(input);
            });
        });
        if (hasInvalid) {
            e.preventDefault();
            const container = document.getElementById('toast-region');
            if (container) {
                const box = document.createElement('div');
                box.className = 'alert alert-danger';
                box.style.minWidth = '260px';
                box.style.maxWidth = '420px';
                box.textContent = 'Please fix invalid Period formats (use YYYY, YYYY–YYYY, or YYYY–Present).';
                container.appendChild(box);
                setTimeout(() => { box.classList.add('fade-out'); setTimeout(() => box.remove(), 650); }, 3000);
            }
        }
    });
    // Enable drag-and-drop for this repeater
    enableDragAndDrop(rowsWrap);
})();

// Skills repeater: add/remove/reorder and hidden input syncing
(function(){
    const container = document.getElementById('skills-repeater');
    const rowsWrap = document.getElementById('skill-rows');
    const hidden = document.getElementById('skill-hidden-inputs');
    const addBtn = document.getElementById('skill-add');
    const tpl = document.getElementById('skill-row-template');
    if (!container || !rowsWrap || !hidden || !addBtn || !tpl) return;

    const MAX_SKILLS = 30;
    function updateLimit(){
        const count = rowsWrap.querySelectorAll('.edu-row').length;
        addBtn.disabled = count >= MAX_SKILLS;
        addBtn.setAttribute('aria-disabled', addBtn.disabled ? 'true' : 'false');
        addBtn.title = addBtn.disabled ? `Max ${MAX_SKILLS} items reached` : 'Add Skill';
    }

    function createRow(val){
        const node = tpl.content.firstElementChild.cloneNode(true);
        const input = node.querySelector('.skill-name');
        if (typeof val === 'string') input.value = val;
        node.querySelector('.skill-remove').addEventListener('click', function(){ node.remove(); updateLimit(); });
        node.querySelector('.skill-up').addEventListener('click', function(){ const prev = node.previousElementSibling; if (prev) rowsWrap.insertBefore(node, prev); });
        node.querySelector('.skill-down').addEventListener('click', function(){ const next = node.nextElementSibling; if (next) rowsWrap.insertBefore(next, node); });
        return node;
    }

    const seedData = (function(){ try { return JSON.parse(document.getElementById('skill-seed-data')?.textContent || 'null'); } catch(e){ return null; } })();
    if (Array.isArray(seedData) && seedData.length) seedData.forEach(s => rowsWrap.appendChild(createRow(s)));
    else rowsWrap.appendChild(createRow(''));

    updateLimit();
    addBtn.addEventListener('click', function(){ if (addBtn.disabled) return; rowsWrap.appendChild(createRow('')); updateLimit(); });

    const form = document.getElementById('edit-resume-form');
    form.addEventListener('submit', function(){
        hidden.innerHTML = '';
        const rows = Array.from(rowsWrap.querySelectorAll('.edu-row'));
        rows.forEach((row, i) => {
            const name = row.querySelector('.skill-name').value.trim();
            if (!name) return;
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = `skill_items[${i}][name]`;
            input.value = name;
            hidden.appendChild(input);
        });
    });
    enableDragAndDrop(rowsWrap);
})();

// Socials repeater: add/remove/reorder, basic URL validation, and hidden input syncing
(function(){
    const container = document.getElementById('socials-repeater');
    const rowsWrap = document.getElementById('social-rows');
    const hidden = document.getElementById('social-hidden-inputs');
    const addBtn = document.getElementById('social-add');
    const tpl = document.getElementById('social-row-template');
    if (!container || !rowsWrap || !hidden || !addBtn || !tpl) return;

    const MAX_SOCIALS = 10;
    function updateLimit(){
        const count = rowsWrap.querySelectorAll('.edu-row').length;
        addBtn.disabled = count >= MAX_SOCIALS;
        addBtn.setAttribute('aria-disabled', addBtn.disabled ? 'true' : 'false');
        addBtn.title = addBtn.disabled ? `Max ${MAX_SOCIALS} items reached` : '+ Add Social';
    }

    function createRow(data){
        const node = tpl.content.firstElementChild.cloneNode(true);
        const platform = node.querySelector('.social-platform');
        const url = node.querySelector('.social-url');
        if (data) { platform.value = data.platform || ''; url.value = data.url || ''; }
        node.querySelector('.social-remove').addEventListener('click', function(){ node.remove(); updateLimit(); });
        node.querySelector('.social-up').addEventListener('click', function(){ const prev = node.previousElementSibling; if (prev) rowsWrap.insertBefore(node, prev); });
        node.querySelector('.social-down').addEventListener('click', function(){ const next = node.nextElementSibling; if (next) rowsWrap.insertBefore(next, node); });
        // Basic URL validation
        function valid(u){ const s = (u||'').trim(); if (!s) return true; return /^https?:\/\//i.test(s); }
        function update(){ const ok = valid(url.value); url.classList.toggle('invalid', !ok); }
        url.addEventListener('input', update); update();
        return node;
    }

    const seedData = (function(){ try { return JSON.parse(document.getElementById('social-seed-data')?.textContent || 'null'); } catch(e){ return null; } })();
    if (Array.isArray(seedData) && seedData.length) seedData.forEach(item => rowsWrap.appendChild(createRow(item)));
    else rowsWrap.appendChild(createRow({}));

    updateLimit();
    addBtn.addEventListener('click', function(){ if (addBtn.disabled) return; rowsWrap.appendChild(createRow({})); updateLimit(); });

    const form = document.getElementById('edit-resume-form');
    form.addEventListener('submit', function(e){
        hidden.innerHTML = '';
        const rows = Array.from(rowsWrap.querySelectorAll('.edu-row'));
        let hasInvalid = false;
        rows.forEach((row, i) => {
            const platform = row.querySelector('.social-platform').value.trim();
            const url = row.querySelector('.social-url').value.trim();
            const urlEl = row.querySelector('.social-url');
            const ok = !url || /^https?:\/\//i.test(url);
            if (!ok) { urlEl.classList.add('invalid'); hasInvalid = true; }
            if (!platform && !url) return;
            const fields = { platform, url };
            Object.entries(fields).forEach(([k,v]) => {
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = `social_items[${i}][${k}]`;
                input.value = v;
                hidden.appendChild(input);
            });
        });
        if (hasInvalid) {
            e.preventDefault();
            const container = document.getElementById('toast-region');
            if (container) {
                const box = document.createElement('div');
                box.className = 'alert alert-danger';
                box.style.minWidth = '260px';
                box.style.maxWidth = '420px';
                box.textContent = 'Please enter full Social URLs starting with http:// or https://';
                container.appendChild(box);
                setTimeout(() => { box.classList.add('fade-out'); setTimeout(() => box.remove(), 650); }, 3000);
            }
        }
    });
    enableDragAndDrop(rowsWrap);
})();

// Attachments repeater: add/remove/reorder, URL validation, and hidden input syncing
(function(){
    const container = document.getElementById('attachments-repeater');
    const rowsWrap = document.getElementById('attachment-rows');
    const hidden = document.getElementById('attachment-hidden-inputs');
    const addBtn = document.getElementById('attachment-add');
    const tpl = document.getElementById('attachment-row-template');
    if (!container || !rowsWrap || !hidden || !addBtn || !tpl) return;

    const MAX_ATTACHMENTS = 10;
    function updateLimit(){
        const count = rowsWrap.querySelectorAll('.edu-row').length;
        addBtn.disabled = count >= MAX_ATTACHMENTS;
        addBtn.setAttribute('aria-disabled', addBtn.disabled ? 'true' : 'false');
        addBtn.title = addBtn.disabled ? `Max ${MAX_ATTACHMENTS} items reached` : '+ Add Attachment';
    }

    function createRow(data){
        const node = tpl.content.firstElementChild.cloneNode(true);
        const label = node.querySelector('.attachment-label');
        const url = node.querySelector('.attachment-url');
        if (data) { label.value = data.label || ''; url.value = data.url || ''; }
        node.querySelector('.attachment-remove').addEventListener('click', function(){ node.remove(); updateLimit(); });
        node.querySelector('.attachment-up').addEventListener('click', function(){ const prev = node.previousElementSibling; if (prev) rowsWrap.insertBefore(node, prev); });
        node.querySelector('.attachment-down').addEventListener('click', function(){ const next = node.nextElementSibling; if (next) rowsWrap.insertBefore(next, node); });
        function valid(u){ const s = (u||'').trim(); if (!s) return true; return /^https?:\/\//i.test(s); }
        function update(){ const ok = valid(url.value); url.classList.toggle('invalid', !ok); }
        url.addEventListener('input', update); update();
        return node;
    }

    const seedData = (function(){ try { return JSON.parse(document.getElementById('attachment-seed-data')?.textContent || 'null'); } catch(e){ return null; } })();
    if (Array.isArray(seedData) && seedData.length) seedData.forEach(item => rowsWrap.appendChild(createRow(item)));
    else rowsWrap.appendChild(createRow({}));

    updateLimit();
    addBtn.addEventListener('click', function(){ if (addBtn.disabled) return; rowsWrap.appendChild(createRow({})); updateLimit(); });

    const form = document.getElementById('edit-resume-form');
    form.addEventListener('submit', function(e){
        hidden.innerHTML = '';
        const rows = Array.from(rowsWrap.querySelectorAll('.edu-row'));
        let hasInvalid = false;
        rows.forEach((row, i) => {
            const label = row.querySelector('.attachment-label').value.trim();
            const url = row.querySelector('.attachment-url').value.trim();
            const urlEl = row.querySelector('.attachment-url');
            const ok = !url || /^https?:\/\//i.test(url);
            if (!ok) { urlEl.classList.add('invalid'); hasInvalid = true; }
            if (!label && !url) return;
            const fields = { label, url };
            Object.entries(fields).forEach(([k,v]) => {
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = `attachment_items[${i}][${k}]`;
                input.value = v;
                hidden.appendChild(input);
            });
        });
        if (hasInvalid) {
            e.preventDefault();
            const container = document.getElementById('toast-region');
            if (container) {
                const box = document.createElement('div');
                box.className = 'alert alert-danger';
                box.style.minWidth = '260px';
                box.style.maxWidth = '420px';
                box.textContent = 'Please enter full Attachment URLs starting with http:// or https://';
                container.appendChild(box);
                setTimeout(() => { box.classList.add('fade-out'); setTimeout(() => box.remove(), 650); }, 3000);
            }
        }
    });
    enableDragAndDrop(rowsWrap);
})();

// Generic drag-and-drop for .edu-row within a container
function enableDragAndDrop(container){
    if (!container) return;
    let dragEl = null;
    container.addEventListener('dragstart', (e) => {
        const row = e.target.closest('.edu-row');
        if (!row) return;
        dragEl = row;
        row.classList.add('dragging');
        row.setAttribute('aria-grabbed','true');
        e.dataTransfer.effectAllowed = 'move';
        try { e.dataTransfer.setData('text/plain', ''); } catch(_){ }
    });
    container.addEventListener('dragend', (e) => {
        const row = e.target.closest('.edu-row');
        if (row) { row.classList.remove('dragging'); row.setAttribute('aria-grabbed','false'); }
        dragEl = null;
    });
    container.addEventListener('dragover', (e) => {
        if (!dragEl) return;
        e.preventDefault();
        const after = getDragAfterElement(container, e.clientY);
        if (after == null) container.appendChild(dragEl);
        else container.insertBefore(dragEl, after);
    });
    function getDragAfterElement(container, y){
        const els = [...container.querySelectorAll('.edu-row:not(.dragging)')];
        return els.reduce((closest, child) => {
            const box = child.getBoundingClientRect();
            const offset = y - box.top - box.height / 2;
            if (offset < 0 && offset > closest.offset) { return { offset, element: child }; }
            else { return closest; }
        }, { offset: Number.NEGATIVE_INFINITY }).element;
    }
}

// Back link with unsaved changes confirm
(function(){
    const form = document.getElementById('edit-resume-form');
    const backLink = document.getElementById('nav-back-link');
    const redirectInput = document.getElementById('redirect_to_input');
    const modal = document.getElementById('unsaved-modal');
    const btnSave = document.getElementById('unsaved-save');
    const btnDiscard = document.getElementById('unsaved-discard');
    const btnCancel = document.getElementById('unsaved-cancel');
    if (!form || !backLink || !redirectInput || !modal) return;

    function serializeForm(f){
        const fd = new FormData(f);
        // ignore token field
        fd.delete('_token');
        const obj = {};
        for (const [k,v] of fd.entries()) {
            // For multi-value fields, last value wins (simple enough for this form)
            obj[k] = v == null ? '' : String(v);
        }
        // Also include repeater state (education rows) since inputs lack names until submit
        try {
            const rows = Array.from(document.querySelectorAll('#edu-rows .edu-row')).map(row => ({
                level: row.querySelector('.edu-level')?.value || '',
                description: row.querySelector('.edu-description')?.value || '',
                address: row.querySelector('.edu-address')?.value || '',
                year: row.querySelector('.edu-year')?.value || ''
            }));
            obj['__edu__'] = JSON.stringify(rows);
        } catch(e) {}
        // Include experience rows for dirty check
        try {
            const rows = Array.from(document.querySelectorAll('#exp-rows .edu-row')).map(row => ({
                title: row.querySelector('.exp-title')?.value || '',
                company: row.querySelector('.exp-company')?.value || '',
                period: row.querySelector('.exp-period')?.value || '',
                address: row.querySelector('.exp-address')?.value || '',
                description: row.querySelector('.exp-description')?.value || ''
            }));
            obj['__exp__'] = JSON.stringify(rows);
        } catch(e) {}
        // Include skills repeater
        try {
            const rows = Array.from(document.querySelectorAll('#skill-rows .edu-row')).map(row => ({
                name: row.querySelector('.skill-name')?.value || ''
            }));
            obj['__skills__'] = JSON.stringify(rows);
        } catch(e) {}
        // Include socials repeater
        try {
            const rows = Array.from(document.querySelectorAll('#social-rows .edu-row')).map(row => ({
                platform: row.querySelector('.social-platform')?.value || '',
                url: row.querySelector('.social-url')?.value || ''
            }));
            obj['__socials__'] = JSON.stringify(rows);
        } catch(e) {}
        // Include attachments repeater
        try {
            const rows = Array.from(document.querySelectorAll('#attachment-rows .edu-row')).map(row => ({
                label: row.querySelector('.attachment-label')?.value || '',
                url: row.querySelector('.attachment-url')?.value || ''
            }));
            obj['__attachments__'] = JSON.stringify(rows);
        } catch(e) {}
        return JSON.stringify(obj);
    }
    const initial = serializeForm(form);

    function isDirty(){
        try { return serializeForm(form) !== initial; } catch(e) { return false; }
    }

    function openModal(){
        modal.style.display = 'block';
        modal.classList.add('show');
        modal.removeAttribute('aria-hidden');
    }
    function closeModal(){
        modal.classList.remove('show');
        modal.setAttribute('aria-hidden','true');
        modal.style.display = 'none';
    }

    backLink.addEventListener('click', function(e){
        // If no changes, navigate back immediately (prefer history back)
        if (!isDirty()) {
            if (window.history.length > 1) {
                e.preventDefault();
                window.history.back();
                return;
            }
            return; // allow default nav to href
        }
        // Unsaved changes: show modal
        e.preventDefault();
        openModal();

        // Wire buttons (one-time)
        btnSave?.addEventListener('click', function(){
            // Always redirect to Resume page after successful save
            const resumeUrl = form.getAttribute('data-resume-url') || backLink.getAttribute('href') || '';
            redirectInput.value = resumeUrl;
            // Flag for Resume page to show a success toast after redirect
            try { sessionStorage.setItem('profileUpdated', '1'); } catch(e) {}
            // Submit the form in a way that triggers submit handlers and HTML5 validation
            if (typeof form.requestSubmit === 'function') {
                form.requestSubmit();
            } else {
                const tmpBtn = document.createElement('button');
                tmpBtn.type = 'submit';
                tmpBtn.style.display = 'none';
                form.appendChild(tmpBtn);
                tmpBtn.click();
                form.removeChild(tmpBtn);
            }
        }, { once: true });
        btnDiscard?.addEventListener('click', function(){
            closeModal();
            if (window.history.length > 1) { window.history.back(); }
            else { window.location.href = backLink.getAttribute('href') || '/resume'; }
        }, { once: true });
        btnCancel?.addEventListener('click', function(){ closeModal(); }, { once: true });
        modal.querySelector('.modal-backdrop')?.addEventListener('click', function(){ closeModal(); }, { once: true });
    });
})();

// Floating scroll-to-top button for the editor (match resume page behavior)
(function(){
    const header = document.getElementById('about');
    if (!header) return;
    let btn = document.getElementById('scroll-top-btn');
    if (!btn) {
        btn = document.createElement('button');
        btn.id = 'scroll-top-btn';
        btn.className = 'scroll-top-btn';
        btn.type = 'button';
        btn.title = 'Back to top';
        btn.setAttribute('aria-label', 'Back to top');
        btn.setAttribute('hidden','');
        btn.innerHTML = '<svg viewBox="0 0 24 24" aria-hidden="true"><path d="M6 14l6-6 6 6" /></svg>';
        document.body.appendChild(btn);
    }
    let hideTimer = null;
    function onScroll(){
        const y = window.scrollY || window.pageYOffset || 0;
        if (y > 300) {
            if (hideTimer) { clearTimeout(hideTimer); hideTimer = null; }
            btn.removeAttribute('hidden');
            requestAnimationFrame(() => btn.classList.add('show'));
        } else {
            btn.classList.remove('show');
            hideTimer = setTimeout(() => { btn.setAttribute('hidden',''); }, 220);
        }
    }
    window.addEventListener('scroll', onScroll, { passive: true });
    onScroll();
    btn.addEventListener('click', function(){
        const startY = window.scrollY || window.pageYOffset || 0;
        const rect = header.getBoundingClientRect();
        const targetY = startY + rect.top;
        const duration = 500;
        const startTime = performance.now();
        const ease = t => t < 0.5 ? 4*t*t*t : 1 - Math.pow(-2*t+2, 3)/2; // cubic easeInOut
        document.documentElement.classList.add('no-smooth');
        function frame(now){
            const elapsed = now - startTime;
            const t = Math.min(1, elapsed / duration);
            const y = startY + (targetY - startY) * ease(t);
            window.scrollTo(0, y);
            if (t < 1) requestAnimationFrame(frame);
            else setTimeout(() => document.documentElement.classList.remove('no-smooth'), 0);
        }
        requestAnimationFrame(frame);
    });
})();

</script>
</body>
</html>
