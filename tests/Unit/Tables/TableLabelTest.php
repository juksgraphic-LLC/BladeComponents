<?php

declare(strict_types=1);

use Juksgraphic\BladeComponents\Table\TableLabels;

it('provides the default labels', function () {
    expect(TableLabels::from(null)->toArray())->toBe([
        'loadingError'      => 'Failed to load the data',
        'deleteError'       => 'Failed to delete the item',
        'imagePlaceholder'  => '/assets/img/placeholder.png',
        'avatarPlaceholder' => '/assets/img/avatar-placeholder.png',
        'na'                => 'N/A',
        'empty'             => '-',
    ]);
});

it('falls back to the defaults when the input is not an array', function (mixed $value) {
    expect(TableLabels::from($value)->toArray()['loadingError'])->toBe('Failed to load the data');
})->with([null, 'text', 5, true]);

it('overrides only the given labels', function () {
    $labels = TableLabels::from(['loadingError' => 'Oups', 'na' => '—'])->toArray();

    expect($labels['loadingError'])->toBe('Oups')
        ->and($labels['na'])->toBe('—')
        ->and($labels['deleteError'])->toBe('Failed to delete the item');
});

it('keeps custom labels that are not part of the defaults', function () {
    $labels = TableLabels::from(['custom' => 'Hello'])->toArray();

    expect($labels)->toHaveKey('custom', 'Hello')
        ->and($labels)->toHaveCount(7);
});
