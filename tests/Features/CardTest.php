<?php

use eftec\bladeone\BladeOne;

function renderCard(array $data = []): string
{
    $views = __DIR__ . '/../../components';
    $cache = __DIR__ . '/../../docs/cache';

    $blade = new BladeOne($views, $cache, BladeOne::MODE_DEBUG);

    return $blade->run('jkc.cards.card', $data);
}

it('renders default card correctly without header or footer', function () {
    $html = renderCard([
        'body' => 'Main card body content.',
    ]);

    expect($html)->toContain('Main card body content.')
                 ->toContain('bg-surface')
                 ->toContain('rounded-radius');
});

it('renders card with title and subtitle correctly', function () {
    $html = renderCard([
        'title' => 'Card Title Test',
        'subtitle' => 'Card Subtitle Test',
        'body' => 'Content inside card.',
    ]);

    expect($html)->toContain('Card Title Test')
                 ->toContain('Card Subtitle Test')
                 ->toContain('Content inside card.');
});

it('renders body without padding when noPadding is true', function () {
    $html = renderCard([
        'body' => 'No padding body content.',
        'noPadding' => true,
    ]);

    expect($html)->toContain('No padding body content.');
    expect($html)->not->toContain('px-6 py-4');
});

it('renders header actions and footer correctly', function () {
    $html = renderCard([
        'title' => 'Action Card',
        'headerAction' => '<button>Action</button>',
        'body' => 'Body content',
        'footer' => 'Footer content',
    ]);

    expect($html)->toContain('Action Card')
                 ->toContain('<button>Action</button>')
                 ->toContain('Body content')
                 ->toContain('Footer content');
});
