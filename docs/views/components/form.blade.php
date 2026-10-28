@extends('layout')

@section('content')

    @php
        $rows = [
            [
                'Properties & Configuration',
                [
                    ['fields', 'array', '[]', 'Configuration array for form fields.'],
                    [
                        'initialData',
                        'array',
                        '[]',
                        'Initial data as an associative array (e.g., <code>$user->toArray()</code>).',
                    ],
                    ['action', 'string', "''", 'Request destination URL endpoint.'],
                    ['method', 'string', "'POST'", 'HTTP request method (POST, PUT, DELETE).'],
                    [
                        'submitAttrs',
                        'string',
                        "''",
                        'HTMX or AlpineJS attributes applied to the <code>&lt;form&gt;</code> tag.',
                    ],
                    ['submitText', 'string', 'null', 'Custom label for the submit button.'],
                ],
            ],
        ];

        $fieldTypesProps = [
            'Input (`text`, `email`, `password`, etc.)' => [
                ['label', 'string', "''", 'Label text for the input field.'],
                ['name', 'string', "'input'", 'HTML field name attribute.'],
                ['type', 'string', "'text'", 'HTML input type attribute.'],
                ['required', 'bool', 'false', 'Mandatory field flag.'],
                ['placeholder', 'string', "''", 'Placeholder text.'],
                ['class', 'string', "''", 'Additional CSS utility classes.'],
            ],
            'Select (`select`)' => [
                ['label', 'string', "''", 'Label text for the select field.'],
                ['name', 'string', "'select'", 'HTML field name.'],
                ['options', 'array', '[]', 'Options list `[["id" => "1", "title" => "Label"]]`.'],
                ['multiple', 'bool', 'false', 'Enable multi-selection dropdown with pills.'],
                ['required', 'bool', 'false', 'Mandatory field flag.'],
            ],
            'Textarea (`textarea`)' => [
                ['label', 'string', "''", 'Label text.'],
                ['name', 'string', "'content'", 'HTML field name.'],
                ['useQuill', 'bool', 'false', 'Enable rich text Quill JS editor.'],
                ['isSimple', 'bool', 'false', 'Legacy/alias flag for standard textarea mode.'],
                ['rows', 'int', '4', 'Rows height in standard textarea mode.'],
                ['placeholder', 'string', "'Enter your text here...'", 'Placeholder text.'],
            ],
            'File Upload (`file`)' => [
                ['label', 'string', "''", 'Label text.'],
                ['name', 'string', "'file'", 'HTML field name.'],
                ['accept', 'string', "''", 'Accepted file MIME types or extensions (e.g., `image/*, .pdf`).'],
                ['recommended', 'string', "''", 'Helper text for recommended file dimensions/sizes.'],
                ['initialData', 'array', '[]', 'Initial data containing existing remote file/image URL.'],
            ],
            'Checkbox (`checkbox`)' => [
                ['label', 'string', "''", 'Label text or toggle title.'],
                ['name', 'string', "'checkbox'", 'HTML field name.'],
                ['multiple', 'bool', 'false', 'Enable multi-checkbox option group mode.'],
                ['options', 'array', '[]', 'Options array for multi-checkbox mode.'],
            ],
            'Radio (`radio`)' => [
                ['label', 'string', "''", 'Group label text.'],
                ['name', 'string', "'radio'", 'HTML field name.'],
                ['options', 'array', '[]', 'Radio options list `[["id" => "val", "title" => "Label"]]`.'],
                ['required', 'bool', 'false', 'Mandatory radio choice selection.'],
            ],
        ];
    @endphp

    <div class="grid grid-cols-1 gap-8 lg:grid-cols-3">

        {{-- ── LEFT COLUMN : USAGE & EXAMPLES (2/3) ────────────── --}}
        <section id="utilisations" class="scroll-mt-6 lg:col-span-2">

            <header class="mb-10">
                <p class="font-code text-xs uppercase tracking-wide text-primary dark:text-primary-dark">
                    Form &amp; Data Input
                </p>
                <h1 class="mt-1 font-title text-3xl font-semibold text-on-surface-strong dark:text-on-surface-dark-strong">
                    Forms
                </h1>
                <p class="mt-2 max-w-2xl font-paragraph text-on-surface/70 dark:text-on-surface-dark/70">
                    Explore structural form types powered by modular fields, native Alpine search selects, Quill
                    rich text, custom file upload previews, and submission actions.
                </p>
            </header>

            <div class="mt-6 grid grid-cols-1 gap-6">

                {{-- Type 1: Standard User Profile Form --}}
                @component('partials.section', [
                    'title' => '1. Standard User Profile Form',
                    'description' => 'Basic edit form handling text inputs, email, and simple role selection.',
                ])
                    @component('partials.example')
                        @verbatim
                            @include('jkc.forms.form', [
                                'fields' => [
                                    [
                                        'name' => 'full_name',
                                        'label' => 'Full Name',
                                        'type' => 'text',
                                        'required' => true,
                                    ],
                                    [
                                        'name' => 'email',
                                        'label' => 'Email Address',
                                        'type' => 'email',
                                        'required' => true,
                                    ],
                                    [
                                        'name' => 'role_id',
                                        'label' => 'User Role',
                                        'type' => 'select',
                                        'options' => [
                                            ['id' => '1', 'title' => 'Administrator'],
                                            ['id' => '2', 'title' => 'Editor'],
                                        ],
                                    ],
                                ],
                                'initialData' => [
                                    'full_name' => 'Kingsley Guillaume',
                                    'email' => 'contact@juksgraphic.com',
                                    'role_id' => '2',
                                ],
                                'action' => '/api/users/profile',
                                'method' => 'POST',
                            ])
                        @endverbatim
                    @endcomponent
                @endcomponent

                {{-- Type 2: Project Settings & Multi-select Form --}}
                @component('partials.section', [
                    'title' => '2. Project Settings & Multi-Select Form',
                    'description' => 'Advanced configuration utilizing multi-selection pills and file uploads.',
                ])
                    @component('partials.example')
                        @verbatim
                            @include('jkc.forms.form', [
                                'fields' => [
                                    [
                                        'name' => 'project_title',
                                        'label' => 'Project Title',
                                        'type' => 'text',
                                        'required' => true,
                                    ],
                                    [
                                        'name' => 'skills',
                                        'label' => 'Assigned Technologies',
                                        'type' => 'select',
                                        'multiple' => true,
                                        'options' => [
                                            ['id' => 'php', 'title' => 'PHP / FlightPHP'],
                                            ['id' => 'js', 'title' => 'JavaScript / Alpine.js'],
                                            ['id' => 'ui', 'title' => 'UI/UX Design'],
                                        ],
                                    ],
                                    ['name' => 'project_logo', 'label' => 'Project Logo', 'type' => 'file'],
                                ],
                                'initialData' => [
                                    'project_title' => 'JuksGraphic Starter Kit',
                                    'skills' => ['php', 'js'],
                                ],
                                'action' => '/api/projects/update',
                                'method' => 'POST',
                            ])
                        @endverbatim
                    @endcomponent
                @endcomponent

                {{-- Type 3: Article Publication with Quill Rich Text --}}
                @component('partials.section', [
                    'title' => '3. Article Publication (Quill Rich Text)',
                    'description' => 'Content publishing form featuring Quill rich text editor and checkbox toggles.',
                ])
                    @component('partials.example')
                        @verbatim
                            @include('jkc.forms.form', [
                                'fields' => [
                                    [
                                        'name' => 'title',
                                        'label' => 'Article Title',
                                        'type' => 'text',
                                        'required' => true,
                                    ],
                                    [
                                        'name' => 'content',
                                        'label' => 'Body Content',
                                        'type' => 'textarea',
                                        'useQuill' => true,
                                    ],
                                    [
                                        'name' => 'is_published',
                                        'label' => 'Publish Immediately',
                                        'type' => 'checkbox',
                                    ],
                                ],
                                'initialData' => [
                                    'title' => 'Understanding Modular PHP Architecture',
                                    'content' =>
                                        '<p>Building lightweight components with <strong>FlightPHP</strong> and <strong>BladeOne</strong>.</p>',
                                    'is_published' => true,
                                ],
                                'action' => '/api/articles',
                                'method' => 'POST',
                            ])
                        @endverbatim
                    @endcomponent
                @endcomponent

                {{-- Type 4: Security & Authentication Form --}}
                @component('partials.section', [
                    'title' => '4. Security & Password Update Form',
                    'description' => 'Secure input types for password management and authentication settings.',
                ])
                    @component('partials.example')
                        @verbatim
                            @include('jkc.forms.form', [
                                'fields' => [
                                    [
                                        'name' => 'current_password',
                                        'label' => 'Current Password',
                                        'type' => 'password',
                                        'required' => true,
                                    ],
                                    [
                                        'name' => 'new_password',
                                        'label' => 'New Password',
                                        'type' => 'password',
                                        'required' => true,
                                    ],
                                    [
                                        'name' => 'two_factor',
                                        'label' => 'Enable Two-Factor Authentication',
                                        'type' => 'checkbox',
                                    ],
                                ],
                                'initialData' => ['two_factor' => false],
                                'action' => '/api/security/password',
                                'method' => 'POST',
                                'submitText' => 'Update Password',
                            ])
                        @endverbatim
                    @endcomponent
                @endcomponent

                {{-- Type 5: System Preferences Form --}}
                @component('partials.section', [
                    'title' => '5. System Preferences Form',
                    'description' => 'Single select options combined with standard textareas and checkboxes.',
                ])
                    @component('partials.example')
                        @verbatim
                            @include('jkc.forms.form', [
                                'fields' => [
                                    [
                                        'name' => 'timezone',
                                        'label' => 'System Timezone',
                                        'type' => 'select',
                                        'options' => [
                                            ['id' => 'utc', 'title' => 'UTC'],
                                            ['id' => 'est', 'title' => 'Eastern Standard Time'],
                                            ['id' => 'cet', 'title' => 'Central European Time'],
                                        ],
                                    ],
                                    [
                                        'name' => 'system_notes',
                                        'label' => 'Maintenance Notes',
                                        'type' => 'textarea',
                                        'useQuill' => false,
                                    ],
                                    [
                                        'name' => 'maintenance_mode',
                                        'label' => 'Maintenance Mode',
                                        'type' => 'checkbox',
                                    ],
                                ],
                                'initialData' => [
                                    'timezone' => 'est',
                                    'system_notes' => 'Scheduled updates planned for Sunday.',
                                    'maintenance_mode' => false,
                                ],
                                'action' => '/api/settings',
                                'method' => 'POST',
                            ])
                        @endverbatim
                    @endcomponent
                @endcomponent

                {{-- Type 6: Custom Slot Submission Form (HTMX / Fetch) --}}
                @component('partials.section', [
                    'title' => '6. Custom Slot Action Form (HTMX / Fetch)',
                    'description' =>
                        'Using custom slots to bind advanced submission workflows and interact directly with Alpine state.',
                ])
                    @component('partials.example')
                        @verbatim
                            @component('jkc.forms.form', [
                                'fields' => [
                                    ['name' => 'ticket_subject', 'label' => 'Support Subject', 'type' => 'text', 'required' => true],
                                    ['name' => 'message', 'label' => 'Issue Description', 'type' => 'textarea', 'useQuill' => false],
                                ],
                                'initialData' => ['ticket_subject' => 'API Integration Bug'],
                                'action' => '/api/support',
                            ])
                                @slot('submit')
                                    <button type="button" x-on:click="alert('Dispatching payload: ' + JSON.stringify(formData))"
                                        class="bg-secondary px-6 py-2.5 text-sm text-white rounded-radius hover:bg-secondary/90 cursor-pointer">
                                        Send Support Ticket (Fetch)
                                    </button>
                                @endslot
                            @endcomponent
                        @endverbatim
                    @endcomponent
                @endcomponent

                {{-- Type 7: Radio Group & Multi-Checkboxes Form --}}
                @component('partials.section', [
                    'title' => '7. Radio Groups & Checkbox Variants',
                    'description' =>
                        'Demonstrating radio button groups, multiple checkboxes array binding, and single toggle switches.',
                ])
                    @component('partials.example')
                        @verbatim
                            @include('jkc.forms.form', [
                                'fields' => [
                                    [
                                        'name' => 'account_type',
                                        'label' => 'Account Type (Radio Group)',
                                        'type' => 'radio',
                                        'required' => true,
                                        'options' => [
                                            ['id' => 'personal', 'title' => 'Personal Account'],
                                            ['id' => 'business', 'title' => 'Business Agency'],
                                            ['id' => 'developer', 'title' => 'Developer Account'],
                                        ],
                                    ],
                                    [
                                        'name' => 'notifications',
                                        'label' => 'Notification Channels (Multi Checkboxes)',
                                        'type' => 'checkbox',
                                        'multiple' => true,
                                        'options' => [
                                            ['id' => 'email', 'title' => 'Email Alerts'],
                                            ['id' => 'sms', 'title' => 'SMS Notifications'],
                                            ['id' => 'push', 'title' => 'Push Messages'],
                                        ],
                                    ],
                                    [
                                        'name' => 'terms_accepted',
                                        'label' => 'Accept Terms & Conditions (Single Toggle)',
                                        'type' => 'checkbox',
                                        'multiple' => false,
                                    ],
                                ],
                                'initialData' => [
                                    'account_type' => 'business',
                                    'notifications' => ['email', 'push'],
                                    'terms_accepted' => true,
                                ],
                                'action' => '/api/account/preferences',
                                'method' => 'POST',
                            ])
                        @endverbatim
                    @endcomponent
                @endcomponent

                {{-- Type 8: Advanced Media Upload Form (Images Unsplash, Videos, Audio, Docs) --}}
                @component('partials.section', [
                    'title' => '8. Multi-Type Media & Document Upload Center',
                    'description' =>
                        'Advanced file inputs supporting multiple formats (Unsplash images, MP3 audio, videos, and PDF documentation) with dynamic size rendering and format recommendations.',
                ])
                    @component('partials.example')
                        @verbatim
                            @include('jkc.forms.form', [
                                'fields' => [
                                    [
                                        'name' => 'cover_image',
                                        'label' => 'Hero Cover Image (Unsplash Sample)',
                                        'type' => 'file',
                                        'accept' => 'image/*',
                                        'recommended' => 'Recommended: JPG, PNG or WebP up to 5MB.',
                                    ],
                                    [
                                        'name' => 'promo_video',
                                        'label' => 'Promotional Video',
                                        'type' => 'file',
                                        'accept' => 'video/*',
                                        'recommended' => 'Recommended: MP4 or WebM format up to 50MB.',
                                    ],
                                    [
                                        'name' => 'podcast_audio',
                                        'label' => 'Audio Track (MP3)',
                                        'type' => 'file',
                                        'accept' => 'audio/mp3,audio/*',
                                        'recommended' => 'Recommended: High quality MP3 or WAV audio stream.',
                                    ],
                                    [
                                        'name' => 'contract_doc',
                                        'label' => 'Project Documentation (PDF)',
                                        'type' => 'file',
                                        'accept' => '.pdf,application/pdf',
                                        'recommended' => 'Recommended: PDF document format containing specs.',
                                    ],
                                ],
                                'initialData' => [],
                                'action' => '/api/media/upload',
                                'method' => 'POST',
                                'submitText' => 'Upload All Media Assets',
                            ])
                        @endverbatim
                    @endcomponent
                @endcomponent

                {{-- Type 9: Pre-filled Media Upload with Unsplash Preview --}}
                @component('partials.section', [
                    'title' => '9. Pre-filled Media Upload with Unsplash Preview',
                    'description' =>
                        'Demonstrating pre-existing file data with a pre-rendered image preview fetched directly from an external Unsplash link.',
                ])
                    @component('partials.example')
                        @verbatim
                            @include('jkc.forms.form', [
                                'fields' => [
                                    [
                                        'name' => 'avatar',
                                        'label' => 'User Profile Avatar',
                                        'type' => 'file',
                                        'accept' => 'image/*',
                                        'recommended' => 'Recommended: Square portrait image (JPG, PNG).',
                                    ],
                                ],
                                'initialData' => [
                                    'avatar' =>
                                        'https://images.unsplash.com/photo-1534528741775-53994a69daeb?w=500&auto=format&fit=crop&q=60',
                                ],
                                'action' => '/api/users/avatar',
                                'method' => 'POST',
                                'submitText' => 'Save Avatar',
                            ])
                        @endverbatim
                    @endcomponent
                @endcomponent

                {{-- Type 10: Quill with initialData as Delta --}}
                @component('partials.section', [
                    'title' => '10. Quill Editor with Delta initialData',
                    'description' =>
                        'The initial content is provided as a Delta (array with an "ops" key). Quill loads it directly, and the field always returns a Delta on submit.',
                ])
                    @component('partials.example')
                        @verbatim
                            @include('jkc.forms.form', [
                                'fields' => [
                                    [
                                        'name' => 'title',
                                        'label' => 'Article Title',
                                        'type' => 'text',
                                        'required' => true,
                                    ],
                                    [
                                        'name' => 'content',
                                        'label' => 'Body Content (Delta)',
                                        'type' => 'textarea',
                                        'useQuill' => true,
                                    ],
                                ],
                                'initialData' => [
                                    'title' => 'Delta Format Explained',
                                    'content' => [
                                        'ops' => [
                                            ['insert' => 'Delta Format'],
                                            ['attributes' => ['header' => 1], 'insert' => "\n"],
                                            ['insert' => 'Quill stores content as a '],
                                            ['attributes' => ['bold' => true], 'insert' => 'Delta'],
                                            ['insert' => ', a clean JSON structure.' . "\n"],
                                            ['insert' => 'Easy to store'],
                                            ['attributes' => ['list' => 'bullet'], 'insert' => "\n"],
                                            ['insert' => 'Easy to transform'],
                                            ['attributes' => ['list' => 'bullet'], 'insert' => "\n"],
                                        ],
                                    ],
                                ],
                                'action' => '/api/articles/delta',
                                'method' => 'POST',
                                'submitText' => 'Save Article',
                            ])
                        @endverbatim
                    @endcomponent
                @endcomponent

                {{-- Type 11: Quill with initialData as legacy HTML --}}
                @component('partials.section', [
                    'title' => '11. Quill Editor with HTML initialData (Legacy)',
                    'description' =>
                        'The initial content is an HTML string from older records. It is converted to a Delta when the editor loads, so the field returns a Delta even if the user changes nothing.',
                ])
                    @component('partials.example')
                        @verbatim
                            @include('jkc.forms.form', [
                                'fields' => [
                                    [
                                        'name' => 'title',
                                        'label' => 'Article Title',
                                        'type' => 'text',
                                        'required' => true,
                                    ],
                                    [
                                        'name' => 'content',
                                        'label' => 'Body Content (HTML → Delta)',
                                        'type' => 'textarea',
                                        'useQuill' => true,
                                    ],
                                ],
                                'initialData' => [
                                    'title' => 'Migrating Legacy Content',
                                    'content' =>
                                        '<h1>Legacy HTML</h1><p>This text was saved as <strong>HTML</strong> and is now converted to a <em>Delta</em>.</p><ul><li>Old format in</li><li>Delta format out</li></ul>',
                                ],
                                'action' => '/api/articles/legacy',
                                'method' => 'PUT',
                                'submitText' => 'Update Article',
                            ])
                        @endverbatim
                    @endcomponent
                @endcomponent

            </div>
        </section>

        {{-- ── RIGHT COLUMN : PROPERTIES & SOURCE (1/3) ────────── --}}
        <aside id="proprietes" class="scroll-mt-6 lg:col-span-1">
            <h2 class="font-title text-2xl font-semibold text-on-surface-strong dark:text-on-surface-dark-strong">
                Properties
            </h2>

            <div x-data="{ query: '' }" class="mt-6">
                {{-- Champ de recherche dynamique AlpineJS --}}
                <div class="relative mb-6">
                    <input type="text" x-model="query" placeholder="Filter properties & options..."
                        class="w-full rounded-radius border border-outline bg-surface-alt py-2 pl-9 pr-3 text-xs text-on-surface outline-none transition-all focus:border-primary focus:ring-2 focus:ring-primary/20 dark:border-outline-dark dark:bg-surface-dark-alt/50 dark:text-on-surface-dark dark:focus:border-primary-dark">
                    <svg class="absolute left-3 top-2.5 h-4 w-4 text-on-surface/50 dark:text-on-surface-dark/50"
                        fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
                    </svg>
                </div>

                {{-- Form Container Properties --}}
                @foreach ($rows as $group)
                    <div class="mb-6 overflow-hidden rounded-radius border border-outline dark:border-outline-dark">
                        <h3
                            class="border-b border-outline bg-surface-alt px-4 py-2.5 font-title text-sm font-semibold uppercase tracking-wide text-on-surface-strong dark:border-outline-dark dark:bg-surface-dark-alt dark:text-on-surface-dark-strong">
                            {{ $group[0] }}
                        </h3>
                        <div>
                            @foreach ($group[1] as $row)
                                <div x-show="!query || '{{ strtolower($row[0] . ' ' . $row[1] . ' ' . strip_tags($row[3])) }}'.includes(query.toLowerCase())"
                                    class="border-b border-outline px-4 py-3 last:border-b-0 dark:border-outline-dark">
                                    <div class="flex flex-wrap items-center gap-2">
                                        <code
                                            class="rounded bg-primary/10 px-2 py-0.5 font-code text-xs font-semibold text-primary dark:bg-primary-dark/10 dark:text-primary-dark">{{ $row[0] }}</code>
                                        <span
                                            class="font-code text-xs text-on-surface/50 dark:text-on-surface-dark/50">{{ $row[1] }}</span>
                                        <code
                                            class="ml-auto rounded bg-surface-alt px-2 py-0.5 font-code text-xs text-on-surface/60 dark:bg-surface-dark-alt dark:text-on-surface-dark/60">{{ $row[2] }}</code>
                                    </div>
                                    <p
                                        class="mt-1.5 font-paragraph text-xs leading-relaxed text-on-surface/70 dark:text-on-surface-dark/70">
                                        {!! $row[3] !!}
                                    </p>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endforeach

                {{-- Specific Field Types Properties Explanation --}}
                <div class="mt-8 mb-4">
                    <h3 class="font-title text-lg font-semibold text-on-surface-strong dark:text-on-surface-dark-strong">
                        Field Types Options
                    </h3>
                    <p class="mt-1 text-xs text-on-surface/70 dark:text-on-surface-dark/70">
                        Detailed configuration parameters expected by each specific field type array.
                    </p>
                </div>

                @foreach ($fieldTypesProps as $typeLabel => $props)
                    <div x-show="!query || '{{ strtolower(strip_tags($typeLabel)) }}'.includes(query.toLowerCase()) || {{ json_encode(array_map(fn($p) => strtolower($p[0] . ' ' . $p[1] . ' ' . strip_tags($p[3])), $props)) }}.some(text => text.includes(query.toLowerCase()))"
                        class="mb-6 overflow-hidden rounded-radius border border-outline dark:border-outline-dark">
                        <h4
                            class="border-b border-outline bg-surface-alt px-4 py-2 font-title text-xs font-semibold uppercase tracking-wide text-primary dark:border-outline-dark dark:bg-surface-dark-alt dark:text-primary-dark">
                            {!! $typeLabel !!}
                        </h4>
                        <div>
                            @foreach ($props as $p)
                                <div x-show="!query || '{{ strtolower($p[0] . ' ' . $p[1] . ' ' . strip_tags($p[3]) . ' ' . strip_tags($typeLabel)) }}'.includes(query.toLowerCase())"
                                    class="border-b border-outline px-4 py-2.5 last:border-b-0 dark:border-outline-dark">
                                    <div class="flex flex-wrap items-center gap-2">
                                        <code
                                            class="rounded bg-primary/10 px-1.5 py-0.5 font-code text-xs font-semibold text-primary dark:bg-primary-dark/10 dark:text-primary-dark">{{ $p[0] }}</code>
                                        <span
                                            class="font-code text-[11px] text-on-surface/50 dark:text-on-surface-dark/50">{{ $p[1] }}</span>
                                        <code
                                            class="ml-auto rounded bg-surface-alt px-1.5 py-0.5 font-code text-[11px] text-on-surface/60 dark:bg-surface-dark-alt dark:text-on-surface-dark/60">{{ $p[2] }}</code>
                                    </div>
                                    <p
                                        class="mt-1 font-paragraph text-xs leading-normal text-on-surface/70 dark:text-on-surface-dark/70">
                                        {!! $p[3] !!}
                                    </p>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>

            <div id="source" class="mt-8 scroll-mt-6">
                @component('partials.section', [
                    'title' => 'Source Code',
                    'description' => 'Main component source view for forms.',
                ])
                    @include('partials.source', ['view' => 'jkc.forms.form'])
                @endcomponent
            </div>
        </aside>

    </div>
@endsection
