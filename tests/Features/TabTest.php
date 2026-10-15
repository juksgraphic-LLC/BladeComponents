<?php

use eftec\bladeone\BladeOne;

function renderTabs(array $data = []): string
{
    $views = __DIR__ . '/../../components';
    $cache = __DIR__ . '/../../docs/cache';

    $blade = new BladeOne($views, $cache, BladeOne::MODE_DEBUG);
    
    $defaults = [
        'variant'      => 'default',
        'color'        => 'primary',
        'tabs'         => [
            ['label' => 'Tab 1', 'content' => 'Content 1', 'active' => true],
            ['label' => 'Tab 2', 'content' => 'Content 2'],
        ],
        'id'           => 'test-tabs',
        'tabClass'     => '',
        'contentClass' => '',
    ];

    return $blade->run('jkc.tabs.tab', array_merge($defaults, $data));
}

it('renders default tabs and panels correctly', function () {
    $html = renderTabs([
        'id'   => 'my-tabs',
        'tabs' => [
            ['label' => 'Home Panel', 'content' => 'Welcome home', 'active' => true],
            ['label' => 'About Panel', 'content' => 'About us']
        ]
    ]);

    expect($html)->toContain('Home Panel')
                 ->toContain('Welcome home')
                 ->toContain('About Panel')
                 ->toContain('About us')
                 ->toContain('jkc-tabs-change');
});

it('renders pills variant correctly', function () {
    $html = renderTabs([
        'variant' => 'pills',
        'tabs'    => [
            ['label' => 'Pill Tab', 'content' => 'Pill content', 'active' => true]
        ]
    ]);

    expect($html)->toContain('Pill Tab')
                 ->toContain('rounded-full');
});