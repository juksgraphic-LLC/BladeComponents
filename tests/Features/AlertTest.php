<?php

use eftec\bladeone\BladeOne;

function renderAlert(array $data = []): string
{
    $views = __DIR__ . '/../../components';
    $cache = __DIR__ . '/../../docs/cache';

    $blade = new BladeOne($views, $cache, BladeOne::MODE_DEBUG);

    return $blade->run('jkc.feedbacks.alert', $data);
}

it('renders default alert correctly', function () {
    $html = renderAlert([
        'message' => 'This is a test alert message.',
    ]);

    expect($html)->toContain('This is a test alert message.')
                 ->toContain('role="status"');
});

it('renders alert with correct type and role for danger', function () {
    $html = renderAlert([
        'type' => 'danger',
        'message' => 'Danger alert message.',
    ]);

    expect($html)->toContain('role="alert"')
                 ->toContain('bg-danger/10')
                 ->toContain('Danger alert message.');
});

it('renders title and optional link correctly', function () {
    $html = renderAlert([
        'title' => 'Update Available',
        'message' => 'A new version is ready.',
        'link' => 'https://example.com',
        'linkText' => 'Download',
    ]);

    expect($html)->toContain('Update Available')
                 ->toContain('A new version is ready.')
                 ->toContain('href="https://example.com"')
                 ->toContain('Download');
});

it('renders dismissible close button and attributes when enabled', function () {
    $html = renderAlert([
        'dismissible' => true,
        'id' => 'my-alert',
        'message' => 'Dismissible alert content.',
    ]);

    expect($html)->toContain('id="my-alert"')
                 ->toContain('x-data=')
                 ->toContain('dismiss()')
                 ->toContain('jkc-alert-close')
                 ->toContain('aria-label="Close"');
});

it('renders live mode attributes correctly', function () {
    $html = renderAlert([
        'live' => true,
        'id' => 'live-alert',
        'message' => 'Live alert message.',
    ]);

    expect($html)->toContain('id="live-alert"')
                 ->toContain('x-on:jkc-alert.window')
                 ->toContain('style="display: none"');
});
