<?php

use eftec\bladeone\BladeOne;

function renderBreadcrumb(array $data = []): string
{
    $views = __DIR__ . '/../../components';
    $cache = __DIR__ . '/../../docs/cache';

    $blade = new BladeOne($views, $cache, BladeOne::MODE_DEBUG);

    return $blade->run('jkc.navigations.breadcrumb', $data);
}

it('renders breadcrumb items correctly with links and active last item', function () {
    $html = renderBreadcrumb([
        'items' => [
            ['label' => 'Dashboard', 'url' => '/admin'],
            ['label' => 'Users', 'url' => '/admin/users'],
            ['label' => 'Profile', 'url' => '']
        ]
    ]);

    expect($html)->toContain('aria-label="breadcrumb"') 
                 ->toContain('href="/admin"')
                 ->toContain('Dashboard')
                 ->toContain('href="/admin/users"')
                 ->toContain('Users')
                 ->toContain('Profile')
                 ->toContain('aria-current="page"');
});
it('renders custom SVG separator between items', function () {
    $html = renderBreadcrumb([
        'items' => [
            ['label' => 'Home', 'url' => '/'],
            ['label' => 'Settings', 'url' => '/settings']
        ]
    ]);

    expect($html)->toContain('svg')
        ->toContain('Home')
        ->toContain('Settings');
});
