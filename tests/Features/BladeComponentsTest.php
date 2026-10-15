<?php

use Juksgraphic\BladeComponents\BladeComponents;

it('returns the correct components path', function () {
    $path = BladeComponents::path();

    expect($path)->toBeString()
        ->and(is_dir($path))->toBeTrue()
        ->and($path)->toContain('components');
});

it('returns the correct theme css file path', function () {
    $theme = BladeComponents::theme();

    expect($theme)->toBeString()
        ->and($theme)->toContain('resources/theme.css');
});

it('returns an array of available components', function () {
    $components = BladeComponents::all();

    expect($components)->toBeArray();
});
