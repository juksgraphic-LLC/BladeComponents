<?php

declare(strict_types=1);

use Juksgraphic\BladeComponents\Table\TablePagination;

it('defaults to 5 rows per page', function () {
    $pagination = TablePagination::from(null);

    expect($pagination->perPage())->toBe(5)
        ->and($pagination->perPageOptions())->toBe([5, 10, 25, 50, 100]);
});

it('adds a custom per-page value to the options, sorted', function () {
    expect(TablePagination::from(7)->perPageOptions())->toBe([5, 7, 10, 25, 50, 100])
        ->and(TablePagination::from(200)->perPageOptions())->toBe([5, 10, 25, 50, 100, 200]);
});

it('does not duplicate a value that is already an option', function () {
    expect(TablePagination::from(25)->perPageOptions())->toBe([5, 10, 25, 50, 100]);
});

it('never goes below 1 row per page', function (mixed $value) {
    $pagination = TablePagination::from($value);

    expect($pagination->perPage())->toBe(1)
        ->and($pagination->perPageOptions())->toBe([1, 5, 10, 25, 50, 100]);
})->with([0, -5, 'abc']);

it('casts numeric strings', function () {
    expect(TablePagination::from('20')->perPage())->toBe(20);
});

it('exposes the values given to the constructor', function () {
    $pagination = new TablePagination(15, [15, 30]);

    expect($pagination->perPage())->toBe(15)
        ->and($pagination->perPageOptions())->toBe([15, 30]);
});
