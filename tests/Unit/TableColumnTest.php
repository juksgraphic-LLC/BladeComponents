<?php

declare(strict_types=1);

use Juksgraphic\BladeComponents\BladeComponents;
use Juksgraphic\BladeComponents\Table\TableColumn;
use Juksgraphic\BladeComponents\Table\TableConfig;

it('maps each built-in type to its own cell view', function (string $type) {
    $column = TableColumn::from(['key' => 'x', 'type' => $type], 0);

    expect($column->type())->toBe($type)
        ->and($column->view())->toBe('jkc-internal.tables.cells.' . $type);
})->with(['text', 'badge', 'progress', 'image', 'avatar_text', 'link', 'boolean', 'currency', 'date']);

it('falls back to text for a missing, unknown or unsafe type', function (array $definition) {
    $column = TableColumn::from($definition, 0);

    expect($column->type())->toBe('text')
        ->and($column->view())->toBe('jkc-internal.tables.cells.text');
})->with([
    'no type'        => [[]],
    'unknown type'   => [['type' => 'unknown']],
    'wrong case'     => [['type' => 'Badge']],
    'path traversal' => [['type' => '../row']],
    'with a slash'   => [['type' => 'cells/text']],
    'trailing space' => [['type' => 'badge ']],
    'empty string'   => [['type' => '']],
    'not a string'   => [['type' => 123]],
    'null'           => [['type' => null]],
]);

it('uses the view given by the column', function () {
    $column = TableColumn::from(['type' => 'badge', 'view' => ' app.cells.stock '], 0);

    expect($column->view())->toBe('app.cells.stock');
});

it('ignores an empty custom view', function () {
    $column = TableColumn::from(['type' => 'badge', 'view' => '   '], 0);

    expect($column->view())->toBe('jkc-internal.tables.cells.badge');
});

it('references the column by its position in headers', function () {
    expect(TableColumn::from([], 2)->ref())->toBe('headers[2]')
        ->and(TableColumn::from([], 'name')->ref())->toBe('headers["name"]');
});

it('adds a cell type with a single file', function () {
    $file = BladeComponents::path() . '/jkc-internal/tables/cells/zz_tmp_cell.blade.php';
    $column = TableColumn::from(['type' => 'zz_tmp_cell'], 0);

    expect($column->type())->toBe('text');

    file_put_contents($file, '<span></span>');

    try {
        expect($column->type())->toBe('zz_tmp_cell')
            ->and($column->view())->toBe('jkc-internal.tables.cells.zz_tmp_cell');
    } finally {
        unlink($file);
    }

    expect($column->type())->toBe('text');
});

it('creates one column per header, even a malformed one', function () {
    $columns = TableConfig::fromInput([
        'headers' => [['key' => 'a', 'type' => 'badge'], 'oops', ['key' => 'b']],
    ])->columns();

    expect($columns)->toHaveCount(3)
        ->and(array_map(fn(TableColumn $column) => $column->type(), $columns))->toBe(['badge', 'text', 'text'])
        ->and($columns[1]->ref())->toBe('headers[1]');
});

it('keeps the keys of associative headers', function () {
    $columns = TableConfig::fromInput([
        'headers' => ['name' => ['key' => 'name'], 'state' => ['key' => 'state', 'type' => 'badge']],
    ])->columns();

    expect(array_map(fn(TableColumn $column) => $column->ref(), $columns))
        ->toBe(['headers["name"]', 'headers["state"]']);
});

it('has no column without headers', function () {
    expect(TableConfig::fromInput([])->columns())->toBe([]);
});
