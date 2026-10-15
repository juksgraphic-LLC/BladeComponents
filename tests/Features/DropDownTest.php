<?php

use eftec\bladeone\BladeOne;

function renderDropdown(array $data = []): string
{
    $views = __DIR__ . '/../../components';
    $cache = __DIR__ . '/../../docs/cache';

    $blade = new BladeOne($views, $cache, BladeOne::MODE_DEBUG);
    
    $defaults = [
        'trigger'     => 'button',
        'color'       => 'primary',
        'size'        => null,
        'darkMenu'    => false,
        'align'       => null,
        'autoClose'   => true,
        'items'       => [
            ['text' => 'Action 1', 'href' => '#'],
            ['text' => 'Action 2', 'href' => '#'],
        ],
        'header'      => null,
        'text'        => null,
        'splitAction' => null,
        'buttonText'  => 'Dropdown',
        'menuClass'   => '',
        'id'          => 'test-dropdown',
    ];

    return $blade->run('jkc.dropdowns.dropdown', array_merge($defaults, $data));
}

it('renders default dropdown button and items correctly', function () {
    $html = renderDropdown([
        'buttonText' => 'My Dropdown',
        'items'      => [
            ['text' => 'Profile', 'href' => '/profile']
        ]
    ]);

    expect($html)->toContain('My Dropdown')
                 ->toContain('Profile')
                 ->toContain('/profile')
                 ->toContain('jkc-dropdown-toggle');
});

it('renders split dropdown type correctly', function () {
    $html = renderDropdown([
        'trigger'     => 'split',
        'splitAction' => '/action',
        'buttonText'  => 'Split Action',
    ]);

    expect($html)->toContain('Split Action')
                 ->toContain('href="/action"')
                 ->toContain('Toggle dropdown');
});