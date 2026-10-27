<?php

use eftec\bladeone\BladeOne;

function renderPagination(array $data = []): string
{
    $views = __DIR__ . '/../../components';
    $cache = __DIR__ . '/../../docs/cache';

    $blade = new BladeOne($views, $cache, BladeOne::MODE_DEBUG);

    return $blade->run('jkc.navigations.pagination', $data);
}

it('renders pagination control buttons and page numbers', function () {
    $html = renderPagination([
        'current' => 2,      // Était 'currentPage'
        'total' => 50,     // Était 'totalPages' => 5 (le total d'items, ex: 50 items à 10 par page = 5 pages)
        'perPage' => 10,
        'baseUrl' => '/items?page=',
    ]);

    $decodedHtml = html_entity_decode($html);

    expect($decodedHtml)->toContain('href="/items?page=1"')
                        ->toContain('href="/items?page=3"')
                        ->toContain('2')
                        ->toContain('aria-label="pagination"');
});

it('disables previous button on first page and next button on last page', function () {
    $htmlFirstPage = renderPagination([
        'current' => 1,
        'total' => 50,
        'perPage' => 10,
        'baseUrl' => '/items?page=',
    ]);

    expect(html_entity_decode($htmlFirstPage))->toContain('disabled')
                                              ->toContain('aria-disabled="true"');

    $htmlLastPage = renderPagination([
        'current' => 5,
        'total' => 50,
        'perPage' => 10,
        'baseUrl' => '/items?page=',
    ]);

    expect(html_entity_decode($htmlLastPage))->toContain('disabled');
});
