<?php

declare(strict_types=1);

use Juksgraphic\BladeComponents\BladeComponents;

beforeEach(fn () => BladeComponents::resetScripts());
afterEach(fn () => BladeComponents::resetScripts());

describe('paths', function () {
    it('points to the components folder', function () {
        expect(BladeComponents::path())->toEndWith('/components')
            ->and(is_dir(BladeComponents::path()))->toBeTrue();
    });

    it('points to the theme file', function () {
        expect(BladeComponents::theme())->toEndWith('/resources/theme.css')
            ->and(is_file(BladeComponents::theme()))->toBeTrue();
    });

    it('points to the source of a script', function () {
        expect(BladeComponents::script('jkc-table'))->toEndWith('/resources/js/jkc-table.js')
            ->and(is_file(BladeComponents::script('jkc-table')))->toBeTrue();
    });

    it('points to the bundled version of a script', function () {
        expect(BladeComponents::bundle('jkc-table'))->toEndWith('/dist/jkc-table.js');
    });
});

describe('bundled script', function () {
    it('exists in dist/ (run `npm run build:table` and commit it)', function () {
        expect(is_file(BladeComponents::bundle('jkc-table')))->toBeTrue();
    });

    it('is a single file without ES module syntax', function () {
        $code = BladeComponents::inline('jkc-table');

        expect($code)->not->toBeEmpty()
            ->and($code)->not->toMatch('/^\s*import\s/m')
            ->and($code)->not->toMatch('/^\s*export\s/m')
            ->and($code)->toContain('JkcTable');
    });

    it('returns the exact contents of the bundle', function () {
        expect(BladeComponents::inline('jkc-table'))
            ->toBe(file_get_contents(BladeComponents::bundle('jkc-table')));
    });

    it('throws a helpful exception when the bundle is missing', function () {
        BladeComponents::inline('does-not-exist');
    })->throws(RuntimeException::class, 'Bundled script not found');
});

describe('script inlining', function () {
    it('inlines a script the first time only', function () {
        expect(BladeComponents::shouldInline('jkc-table'))->toBeTrue()
            ->and(BladeComponents::shouldInline('jkc-table'))->toBeFalse()
            ->and(BladeComponents::shouldInline('jkc-table'))->toBeFalse();
    });

    it('tracks each script separately', function () {
        expect(BladeComponents::shouldInline('a'))->toBeTrue()
            ->and(BladeComponents::shouldInline('b'))->toBeTrue()
            ->and(BladeComponents::shouldInline('a'))->toBeFalse();
    });

    it('never inlines when a bundler loads the scripts', function () {
        BladeComponents::useBundledScripts();

        expect(BladeComponents::shouldInline('jkc-table'))->toBeFalse()
            ->and(BladeComponents::shouldInline('jkc-table'))->toBeFalse();
    });

    it('does not consume the first inline while bundled mode is on', function () {
        BladeComponents::useBundledScripts();
        BladeComponents::shouldInline('jkc-table');
        BladeComponents::useBundledScripts(false);

        expect(BladeComponents::shouldInline('jkc-table'))->toBeTrue();
    });

    it('forgets emitted scripts and re-enables inlining on reset', function () {
        BladeComponents::shouldInline('jkc-table');
        BladeComponents::resetScripts();

        expect(BladeComponents::shouldInline('jkc-table'))->toBeTrue();

        BladeComponents::useBundledScripts();
        BladeComponents::resetScripts();

        expect(BladeComponents::shouldInline('other'))->toBeTrue();
    });
});

describe('all', function () {
    it('lists the public components', function () {
        expect(BladeComponents::all())
            ->toContain('table', 'modal', 'accordion', 'card', 'dropdown', 'pagination');
    });

    it('does not list internal partials', function () {
        expect(BladeComponents::all())
            ->not->toContain('actions', 'row', 'delete-modal', 'toolbar', 'head');
    });

    it('returns sorted names without extension and without duplicates', function () {
        $names = BladeComponents::all();

        $sorted = $names;
        sort($sorted);

        expect(array_values($names))->toBe($sorted)
            ->and(array_values($names))->toBe(array_values(array_unique($names)));

        foreach ($names as $name) {
            expect($name)->not->toContain('.');
        }
    });

    it('matches the blade files of components/jkc', function () {
        $expected = array_map(
            fn (string $file) => basename($file, '.blade.php'),
            glob(BladeComponents::path() . '/jkc/*/*.blade.php') ?: []
        );
        $expected = array_values(array_unique($expected));
        sort($expected);

        expect(array_values(BladeComponents::all()))->toBe($expected);
    });
});
