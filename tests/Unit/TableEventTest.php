<?php

declare(strict_types=1);

use Juksgraphic\BladeComponents\BladeComponents;

it('dispatches the table events under their jkc-table-* names', function (string $event) {
    $script = (string) file_get_contents(BladeComponents::script('jkc-table'));

    expect(preg_match('/\$dispatch\(\s*["\']' . preg_quote($event, '/') . '["\']/', $script))->toBe(1);
})->with(['jkc-table-view', 'jkc-table-edit', 'jkc-table-delete', 'jkc-table-selection-change']);

it('no longer mentions the old on-jkc-table event names', function () {
    $root = dirname(__DIR__, 2);
    $offenders = [];

    foreach (['components', 'resources', 'src', 'docs/views'] as $folder) {
        if (! is_dir($root . '/' . $folder)) {
            continue;
        }

        $files = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($root . '/' . $folder, FilesystemIterator::SKIP_DOTS),
        );

        foreach ($files as $file) {
            if (
                $file->isFile()
                && in_array($file->getExtension(), ['php', 'js'], true)
                && str_contains((string) file_get_contents($file->getPathname()), 'on-jkc-table')
            ) {
                $offenders[] = substr($file->getPathname(), strlen($root) + 1);
            }
        }
    }

    expect($offenders)->toBe([]);
});