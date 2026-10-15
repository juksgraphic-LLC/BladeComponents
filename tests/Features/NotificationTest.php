<?php

use eftec\bladeone\BladeOne;

function renderNotification(array $data = []): string
{
    $views = __DIR__ . '/../../components';
    $cache = __DIR__ . '/../../docs/cache';

    $blade = new BladeOne($views, $cache, BladeOne::MODE_DEBUG);


    return $blade->run('jkc.feedbacks.notification', $data);
}

it('renders notifications container and root container correctly', function () {
    $html = renderNotification();

    expect($html)->toContain('id="global-notifications"')
                 ->toContain('x-data="{')
                 ->toContain('@jkc-notify.window')
                 ->toContain('@jkc-toast-show.window');
});

it('renders position configuration elements for Alpine state', function () {
    $html = renderNotification();

    expect($html)->toContain("position: 'top-right'")
                 ->toContain('@jkc-toast-position.window')
                 ->toContain('top-0 right-0 items-end');
});

it('includes templates for various notification variants', function () {
    $html = renderNotification();

    expect($html)->toContain("notification.variant === 'info'")
                 ->toContain("notification.variant === 'success'")
                 ->toContain("notification.variant === 'warning'")
                 ->toContain("notification.variant === 'danger' || notification.variant === 'error'")
                 ->toContain("notification.variant === 'message'");
});
