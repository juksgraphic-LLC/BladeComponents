<?php

use eftec\bladeone\BladeOne;

function renderAccordion(array $data = []): string
{
    $views = __DIR__ . '/../../components';
    $cache = __DIR__ . '/../../docs/cache';

    $blade = new BladeOne($views, $cache, BladeOne::MODE_DEBUG);

    $defaults = [
        'type' => 'default',
        'items' => [
            ['title' => 'Section 1', 'content' => 'Content 1', 'expanded' => true],
            ['title' => 'Section 2', 'content' => 'Content 2'],
        ],
        'color' => 'primary',
        'multiple' => false,
        'flush' => false,
        'id' => 'test-accordion',
    ];

    return $blade->run('jkc.accordions.accordion', array_merge($defaults, $data));
}

it('renders default accordion with items correctly', function () {
    $html = renderAccordion([
        'id' => 'my-accordion',
        'items' => [
            ['title' => 'First Title', 'content' => 'First Content', 'expanded' => true],
        ],
    ]);

    expect($html)->toContain('First Title')
                 ->toContain('First Content')
                 ->toContain('jkc-accordion-toggle');
});

it('renders different accordion types correctly', function () {
    $html = renderAccordion([
        'type' => 'plus',
        'items' => [
            ['title' => 'Plus Accordion', 'content' => 'Content'],
        ],
    ]);

    expect($html)->toContain('Plus Accordion');
});
