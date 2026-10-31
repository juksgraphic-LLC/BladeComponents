<?php

declare(strict_types=1);

use Juksgraphic\BladeComponents\Table\TableCell;
use Juksgraphic\BladeComponents\Table\TableColumn;

it('resolves every built-in cell type', function (string $type) {
    $column = TableColumn::from(['type' => $type], 0);

    expect($column->type())->toBe($type)
        ->and($column->view())->toBe('jkc-internal.tables.cells.' . $type);
})->with(['avatar_text', 'badge', 'boolean', 'currency', 'date', 'image', 'link', 'progress', 'text']);

it('falls back to text when the type is missing', function () {
    $column = TableColumn::from(['key' => 'name'], 0);

    expect($column->type())->toBe('text')
        ->and($column->view())->toBe('jkc-internal.tables.cells.text');
});

it('falls back to text for unknown or invalid types', function (mixed $type) {
    expect(TableColumn::from(['type' => $type], 0)->type())->toBe('text');
})->with([
    'unknown' => 'does_not_exist',
    'uppercase' => 'Badge',
    'path traversal' => '../../secret',
    'with dot' => 'badge.blade',
    'not a string' => 42,
    'null' => null,
    'empty' => '',
]);

it('falls back to text when the definition is not an array', function (mixed $definition) {
    expect(TableColumn::from($definition, 0)->type())->toBe('text');
})->with([null, 'badge', 12]);

it('uses a custom view when given, trimmed', function () {
    $column = TableColumn::from(['type' => 'badge', 'view' => '  my.custom.cell  '], 0);

    expect($column->view())->toBe('my.custom.cell')
        ->and($column->type())->toBe('badge');
});

it('ignores a blank or non-string custom view', function (mixed $view) {
    expect(TableColumn::from(['type' => 'badge', 'view' => $view], 0)->view())
        ->toBe('jkc-internal.tables.cells.badge');
})->with(['   ', '', 5, null]);

it('exposes a cell helper built from the definition', function () {
    $column = TableColumn::from(['key' => 'user.name'], 'name');

    expect($column->cell())->toBeInstanceOf(TableCell::class)
        ->and($column->cell()->valueExpr())->toBe('item?.user?.name')
        ->and($column->cell())->toBe($column->cell());
});
