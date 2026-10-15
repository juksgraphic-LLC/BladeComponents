<?php

use eftec\bladeone\BladeOne;

function renderToast(array $data = []): string
{
    $views = __DIR__ . '/../../components';
    $cache = __DIR__ . '/../../docs/cache';

    $blade = new BladeOne($views, $cache, BladeOne::MODE_DEBUG);


    $defaults = [
        'variant' => 'info',
        'type' => 'default',
        'title' => 'Notification',
        'message' => 'Hello, world! This is a toast message.',
        'time' => 'Just now',
        'senderName' => null,
        'senderAvatar' => null,
        'dismissible' => true,
        'translucent' => false,
        'duration' => null,
        'id' => null,
        'live' => false,
        'stacked' => false,
    ];

    return $blade->run('jkc.feedbacks.toast', array_merge($defaults, $data));
}

it('renders default toast correctly', function () {
    $html = renderToast([
        'title' => 'Test Title',
        'message' => 'Test toast message.',
    ]);

    expect($html)->toContain('Test Title')
                 ->toContain('Test toast message.')
                 ->toContain('role="alert"')
                 ->toContain('aria-live="polite"');
});

it('renders bordered toast type with success variant correctly', function () {
    $html = renderToast([
        'type' => 'bordered',
        'variant' => 'success',
        'title' => 'Success',
        'message' => 'Operation successful.',
    ]);

    expect($html)->toContain('border-success')
                 ->toContain('text-success')
                 ->toContain('Success')
                 ->toContain('Operation successful.');
});

it('renders message toast type with sender details correctly', function () {
    $html = renderToast([
        'type' => 'message',
        'senderName' => 'John Doe',
        'senderAvatar' => 'https://example.com/avatar.jpg',
        'message' => 'Hello from John!',
    ]);

    expect($html)->toContain('John Doe')
                 ->toContain('https://example.com/avatar.jpg')
                 ->toContain('Hello from John!');
});

it('renders live mode attributes and custom ID correctly', function () {
    $html = renderToast([
        'id' => 'my-custom-toast',
        'live' => true,
        'message' => 'Live toast content.',
    ]);

    expect($html)->toContain('id="my-custom-toast"')
                 ->toContain('@jkc-open-toast.window')
                 ->toContain('@jkc-close-toast.window')
                 ->toContain('style="display: none;"');
});
