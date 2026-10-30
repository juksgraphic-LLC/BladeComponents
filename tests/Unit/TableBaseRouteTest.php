<?php

declare(strict_types=1);

use Juksgraphic\BladeComponents\Table\TableConfig;

it('has no base route by default', function () {
    expect(TableConfig::fromInput([])->getbaseRoute())->toBe('');
});

it('removes the trailing slash of the base route', function () {
    expect(TableConfig::fromInput(['baseRoute' => '/admin/posts/'])->getbaseRoute())->toBe('/admin/posts');
});

it('keeps the base route empty when only an endpoint is given', function () {
    expect(TableConfig::fromInput(['endpoint' => '/api/posts'])->getbaseRoute())->toBe('');
});

it('does not put the base route in the JSON config', function () {
    expect(TableConfig::fromInput(['baseRoute' => '/admin/posts'])->toArray())->not->toHaveKey('baseRoute');
});