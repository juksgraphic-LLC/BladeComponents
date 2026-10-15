<?php

use eftec\bladeone\BladeOne;

function renderModal(array $data = []): string
{
    $views = __DIR__ . '/../../components';
    $cache = __DIR__ . '/../../docs/cache';

    $blade = new BladeOne($views, $cache, BladeOne::MODE_DEBUG);
    
    $defaults = [
        'id'         => 'test-modal',
        'size'       => null,
        'position'   => null,
        'centered'   => false,
        'static'     => false,
        'scrollable' => false,
        'animation'  => 'fade',
        'showClose'  => true,
        'title'      => 'Modal Title',
    ];

    return $blade->run('jkc.modals.modal', array_merge($defaults, $data));
}

it('renders default modal correctly with title and default buttons', function () {
    $html = renderModal([
        'id'    => 'my-modal',
        'title' => 'Custom Modal Title',
    ]);

    expect($html)->toContain('Custom Modal Title')
                 ->toContain('role="dialog"')
                 ->toContain('aria-modal="true"')
                 ->toContain('Close')
                 ->toContain('Save changes')
                 ->toContain("detail === 'my-modal'");
});

it('renders modal with size and animation attributes correctly', function () {
    $html = renderModal([
        'size'      => 'lg',
        'animation' => 'zoom',
    ]);

    expect($html)->toContain('max-w-2xl')
                 ->toContain('opacity-0 scale-75');
});

it('renders event listeners for open and close commands', function () {
    $html = renderModal([
        'id' => 'event-modal',
    ]);

    expect($html)->toContain('@jkc-open-modal.window="if ($event.detail === \'event-modal\') setOpen(true)"')
                 ->toContain('@jkc-close-modal.window="if ($event.detail === \'event-modal\') setOpen(false)"');
});

it('renders static backdrop attribute when static is true', function () {
    $html = renderModal([
        'static' => true,
    ]);

    expect($html)->not->toContain('@click.self="setOpen(false)"');
});