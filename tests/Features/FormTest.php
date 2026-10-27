<?php

use eftec\bladeone\BladeOne;

function renderComponent(string $view, array $data = []): string
{
    $views = __DIR__ . '/../../components';
    $cache = __DIR__ . '/../../docs/cache';

    $blade = new BladeOne($views, $cache, BladeOne::MODE_DEBUG);

    return $blade->run($view, $data);
}

it('renders form container with correct action, method, and initial data json', function () {
    $html = renderComponent('jkc.forms.form', [
        'action' => '/api/submit',
        'method' => 'POST',
        'initialData' => [
            'name' => 'Kingsley',
            'email' => 'contact@juksgraphic.com',
        ],
    ]);

    $decodedHtml = html_entity_decode($html);

    expect($decodedHtml)->toContain('action="/api/submit"')
                        ->toContain('method="POST"')
                        ->toContain('formData: {"name":"Kingsley","email":"contact@juksgraphic.com"}');
});
it('renders standard text input with label and x-model binding', function () {
    $html = renderComponent('jkc.forms.input', [
        'label' => 'Full Name',
        'name' => 'full_name',
        'type' => 'text',
        'required' => true,
        'model' => 'formData',
    ]);

    expect($html)->toContain('Full Name')
                 ->toContain('name="full_name"')
                 ->toContain('type="text"')
                 ->toContain('required')
                 ->toContain('x-model="formData.full_name"');
});

it('renders textarea with quill js rich editor when useQuill is true', function () {
    $html = renderComponent('jkc.forms.textarea', [
        'label' => 'Description',
        'name' => 'content',
        'useQuill' => true,
        'model' => 'formData',
    ]);

    expect($html)->toContain('Description')
                 ->toContain('x-ref="editor"')
                 ->toContain('useQuill: true')
                 ->toContain('type="hidden"')
                 ->toContain('name="content"');
});

it('renders file upload with multi-format support and remote initial image preview', function () {
    $html = renderComponent('jkc.forms.file', [
        'label' => 'Avatar',
        'name' => 'avatar',
        'accept' => 'image/*',
        'recommended' => 'Recommended: JPG/PNG up to 5MB',
        'initialData' => [
            'avatar' => 'https://images.unsplash.com/photo-1534528741775-53994a69daeb',
        ],
    ]);

    expect($html)->toContain('Avatar')
                 ->toContain('accept="image/*"')
                 ->toContain('Recommended: JPG/PNG up to 5MB')
                 ->toContain('https://images.unsplash.com/photo-1534528741775-53994a69daeb')
                 ->toContain('handleFileChange');
});

it('renders custom radio group with hidden input and alpine click trigger', function () {
    $html = renderComponent('jkc.forms.radio', [
        'label' => 'Account Type',
        'name' => 'account_type',
        'options' => [
            ['id' => 'personal', 'title' => 'Personal'],
            ['id' => 'business', 'title' => 'Business'],
        ],
        'initialData' => ['account_type' => 'business'],
    ]);

    expect($html)->toContain('Account Type')
                 ->toContain('type="hidden"')
                 ->toContain('name="account_type"')
                 ->toContain('select(\'personal\')')
                 ->toContain('Business');
});

it('renders custom switch/checkbox with hidden input and toggles', function () {
    $html = renderComponent('jkc.forms.checkbox', [
        'label' => 'Enable Notifications',
        'name' => 'notifications_enabled',
        'multiple' => false,
        'initialData' => ['notifications_enabled' => true],
    ]);

    expect($html)->toContain('Enable Notifications')
                 ->toContain('type="hidden"')
                 ->toContain('name="notifications_enabled"')
                 ->toContain('toggle()');
});
