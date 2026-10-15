<?php

use eftec\bladeone\BladeOne;

function renderOffcanvas(array $data = []): string
{
    $views = __DIR__ . '/../../components';
    $cache = __DIR__ . '/../../docs/cache';

    $blade = new BladeOne($views, $cache, BladeOne::MODE_DEBUG);


    $defaults = [
        'id' => 'test-offcanvas',
        'placement' => 'start',
        'size' => null,
        'backdrop' => true,
        'scroll' => false,
        'title' => 'Offcanvas',
        'showClose' => true,
    ];

    return $blade->run('jkc.overlays.offcanvas', array_merge($defaults, $data));
}

it('renders default offcanvas correctly with title and close button', function () {
    $html = renderOffcanvas([
        'id' => 'my-offcanvas',
        'title' => 'Custom Offcanvas Title',
    ]);

    expect($html)->toContain('Custom Offcanvas Title')
        ->toContain('Close offcanvas')
        ->toContain("detail === 'my-offcanvas'")
        ->toContain('jkc-on-open-offcanvas')
        ->toContain('jkc-on-close-offcanvas');
});

it('renders placement and size classes correctly', function () {
    $html = renderOffcanvas([
        'placement' => 'end',
        'size' => 'lg',
    ]);

    expect($html)->toContain('top-0 right-0 h-full')
        ->toContain('w-[32rem]');
});

it('renders backdrop overlay by default and hides it when backdrop is false', function () {
    $htmlWithBackdrop = renderOffcanvas(['backdrop' => true]);
    expect($htmlWithBackdrop)->toContain('backdrop-blur-sm');

    $htmlWithoutBackdrop = renderOffcanvas(['backdrop' => false]);
    expect($htmlWithoutBackdrop)->not->toContain('backdrop-blur-sm');
});

it('renders body and footer content when provided', function () {
    $html = renderOffcanvas([
        'body' => 'Offcanvas body content',
        'footer' => 'Offcanvas footer content',
    ]);

    expect($html)->toContain('Offcanvas body content')
        ->toContain('Offcanvas footer content');
});
