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

it('returns the path of an existing component script', function () {
    $script = BladeComponents::script('jkc-table');

    expect($script)->toBeString()
        ->and(is_file($script))->toBeTrue()
        ->and($script)->toContain('resources/js/jkc-table.js');
});

it('asks to inline a script only once until it is reset', function () {
    BladeComponents::resetScripts();

    expect(BladeComponents::shouldInline('jkc-table'))->toBeTrue()
        ->and(BladeComponents::shouldInline('jkc-table'))->toBeFalse();

    BladeComponents::resetScripts();

    expect(BladeComponents::shouldInline('jkc-table'))->toBeTrue();

    BladeComponents::resetScripts();
});

it('never asks to inline a script when a bundler loads it', function () {
    BladeComponents::useBundledScripts();

    expect(BladeComponents::shouldInline('jkc-table'))->toBeFalse();

    BladeComponents::resetScripts();
});