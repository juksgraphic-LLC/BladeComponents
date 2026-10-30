<?php

declare(strict_types=1);

use Juksgraphic\BladeComponents\Table\TableFilters;

it('is empty when the input is not an array', function (mixed $value) {
    $filters = TableFilters::from($value);

    expect($filters->hasFilters())->toBeFalse()
        ->and($filters->toArray())->toBe([]);
})->with([null, 'text', 12, false]);

it('reports that filters are defined', function () {
    expect(TableFilters::from([['key' => 'status']])->hasFilters())->toBeTrue();
});

it('skips entries that are not arrays', function () {
    $filters = TableFilters::from([['key' => 'a'], 'junk', 5, null, ['key' => 'b']]);

    expect($filters->toArray())->toHaveCount(2)
        ->and(array_column($filters->toArray(), 'key'))->toBe(['a', 'b']);
});

it('turns a value => label map into a list of options', function () {
    $filters = TableFilters::from([[
        'key' => 'status',
        'options' => ['active' => 'Active', 'banned' => 'Banned'],
    ]]);

    expect($filters->toArray()[0]['options'])->toBe([
        ['value' => 'active', 'label' => 'Active'],
        ['value' => 'banned', 'label' => 'Banned'],
    ]);
});

it('casts option values and labels to strings', function () {
    $filters = TableFilters::from([['options' => [1 => 2, 3 => 4.5]]]);

    expect($filters->toArray()[0]['options'])->toBe([
        ['value' => '1', 'label' => '2'],
        ['value' => '3', 'label' => '4.5'],
    ]);
});

it('keeps options that are already defined as arrays', function () {
    $option = ['value' => 'x', 'label' => 'X', 'icon' => 'star'];
    $filters = TableFilters::from([['options' => [$option]]]);

    expect($filters->toArray()[0]['options'])->toBe([$option]);
});

it('defaults to no options when they are missing or invalid', function () {
    $filters = TableFilters::from([['key' => 'a'], ['key' => 'b', 'options' => 'nope']]);

    expect($filters->toArray()[0]['options'])->toBe([])
        ->and($filters->toArray()[1]['options'])->toBe([]);
});

it('keeps the other keys of the filter untouched', function () {
    $filters = TableFilters::from([['key' => 'status', 'label' => 'Status', 'options' => []]]);

    expect($filters->toArray()[0])->toMatchArray(['key' => 'status', 'label' => 'Status']);
});
