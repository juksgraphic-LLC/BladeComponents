<?php

use Juksgraphic\BladeComponents\Table\TableConfig;

/**
 * Unit tests of the normalization rules, without rendering any view.
 *
 * @return array<string, mixed>
 */
function tableConfigOf(array $input = []): array
{
    return TableConfig::fromInput($input)->toArray();
}

it('falls back to the documented defaults', function () {
    expect(tableConfigOf())->toBe([
        'endpoint' => '/table',
        'headers' => [],
        'filters' => [],
        'withAction' => [],
        'selectable' => false,
        'perPage' => 5,
        'perPageOptions' => [5, 10, 25, 50, 100],
    ]);
});

it('treats null variables like missing ones', function () {
    $input = [
        'baseRoute' => null,
        'endpoint' => null,
        'headers' => null,
        'columns' => null,
        'filters' => null,
        'withAction' => null,
        'selectable' => null,
        'perPage' => null,
        'tableId' => null,
    ];

    expect(tableConfigOf($input))->toBe(tableConfigOf());
});

it('strips every trailing slash of the baseRoute and derives the endpoint', function () {
    expect(tableConfigOf(['baseRoute' => '/admin/posts//'])['endpoint'])->toBe('/admin/posts/table');
});

it('keeps an explicit endpoint, even an empty one', function () {
    expect(tableConfigOf(['baseRoute' => '/a', 'endpoint' => '/api/x'])['endpoint'])->toBe('/api/x')
        ->and(tableConfigOf(['baseRoute' => '/a', 'endpoint' => ''])['endpoint'])->toBe('');
});

it('prefers headers over the columns alias', function () {
    $headers = [['key' => 'a']];
    $columns = [['key' => 'b']];

    expect(tableConfigOf(['headers' => $headers, 'columns' => $columns])['headers'])->toBe($headers)
        ->and(tableConfigOf(['columns' => $columns])['headers'])->toBe($columns)
        ->and(tableConfigOf(['headers' => 'nope', 'columns' => $columns])['headers'])->toBe($columns)
        ->and(tableConfigOf(['headers' => 'nope', 'columns' => 'nope'])['headers'])->toBe([]);
});

it('enforces a minimum of 1 row per page and sorts the options', function () {
    expect(tableConfigOf(['perPage' => 0])['perPage'])->toBe(1)
        ->and(tableConfigOf(['perPage' => 0])['perPageOptions'])->toBe([1, 5, 10, 25, 50, 100])
        ->and(tableConfigOf(['perPage' => '15'])['perPageOptions'])->toBe([5, 10, 15, 25, 50, 100])
        ->and(tableConfigOf(['perPage' => 10])['perPageOptions'])->toBe([5, 10, 25, 50, 100]);
});

it('casts selectable to a boolean and reindexes withAction', function () {
    expect(tableConfigOf(['selectable' => 1])['selectable'])->toBeTrue()
        ->and(tableConfigOf(['selectable' => 0])['selectable'])->toBeFalse()
        ->and(tableConfigOf(['withAction' => [3 => 'edit', 7 => 'delete']])['withAction'])->toBe(['edit', 'delete'])
        ->and(tableConfigOf(['withAction' => 'view'])['withAction'])->toBe([]);
});

it('normalizes filter options from a map and keeps a list as is', function () {
    $list = [['value' => 'fr', 'label' => 'France']];

    $filters = tableConfigOf(['filters' => [
        ['key' => 'status', 'label' => 'Status', 'options' => ['active' => 'Active', 1 => 'One']],
        ['key' => 'country', 'label' => 'Country', 'options' => $list],
        ['key' => 'empty', 'label' => 'Empty'],
    ]])['filters'];

    expect($filters[0]['options'])->toBe([
        ['value' => 'active', 'label' => 'Active'],
        ['value' => '1', 'label' => 'One'],
    ])
        ->and($filters[1]['options'])->toBe($list)
        ->and($filters[2]['options'])->toBe([]);
});

it('always exposes filters as a list and ignores entries that are not filters', function () {
    $filters = tableConfigOf(['filters' => ['status' => ['key' => 'status', 'label' => 'S'], 'oops', 12]])['filters'];

    expect(array_is_list($filters))->toBeTrue()
        ->and($filters)->toHaveCount(1);
});

it('encodes the configuration as JSON without raw HTML characters', function () {
    $json = TableConfig::fromInput([
        'headers' => [['label' => '<b>"Q"</b> O\'Neil & Co', 'key' => 'name']],
    ])->toJson();

    expect($json)->not->toContain('<')
        ->not->toContain('>')
        ->not->toContain("'")
        ->not->toContain('&')
        ->and(json_decode($json, true)['headers'][0]['label'])->toBe('<b>"Q"</b> O\'Neil & Co');
});

it('keeps the JSON valid when a label contains invalid UTF-8', function () {
    $json = TableConfig::fromInput(['headers' => [['label' => "bad \xB1 byte", 'key' => 'x']]])->toJson();

    expect(json_decode($json, true))->toBeArray();
});

it('encodes the same configuration that toArray() returns', function () {
    $config = TableConfig::fromInput(['baseRoute' => '/x', 'perPage' => 10, 'withAction' => ['view']]);

    expect(json_decode($config->toJson(), true))->toBe($config->toArray());
});

it('uses the given tableId, trimmed', function () {
    expect(TableConfig::fromInput(['tableId' => '  students  '])->id())->toBe('students');
});

it('derives a stable id from the endpoint when no tableId is given', function () {
    $first = TableConfig::fromInput(['baseRoute' => '/admin/posts'])->id();
    $again = TableConfig::fromInput(['baseRoute' => '/admin/posts'])->id();
    $other = TableConfig::fromInput(['baseRoute' => '/admin/users'])->id();

    expect($first)->toBe($again)
        ->and($first)->toMatch('/^jkc-table-[0-9a-f]{8}$/')
        ->and($other)->not->toBe($first);
});

it('falls back to the derived id when tableId is blank', function () {
    expect(TableConfig::fromInput(['baseRoute' => '/a', 'tableId' => '   '])->id())
        ->toBe(TableConfig::fromInput(['baseRoute' => '/a'])->id());
});

it('does not add the id to the Alpine configuration', function () {
    expect(tableConfigOf(['tableId' => 'students']))->not->toHaveKey('tableId')
        ->not->toHaveKey('id');
});
