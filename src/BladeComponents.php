<?php

declare(strict_types=1);

namespace Juksgraphic\BladeComponents;

final class BladeComponents
{
    /**
     * Whether component scripts are inlined by the views (false when a bundler loads them).
     */
    private static bool $inlineScripts = true;

    /**
     * Scripts already inlined during this PHP process, indexed by name.
     *
     * @var array<string, true>
     */
    private static array $emittedScripts = [];

    /**
     * Folder paths to add in Laravel and Blade projects
     * @return string
     */
    public static function path(): string
    {
        return dirname(__DIR__) . '/components';
    }

    /**
     * File that contains css theme
     * @return string
     */
    public static function theme(): string
    {
        return dirname(__DIR__) . '/resources/theme.css';
    }

    /**
     * Path of a component script (without extension), e.g. "jkc-table".
     * Load it with your bundler (Vite) or let the views inline it.
     *
     * @param string $name Script name, e.g. "jkc-table".
     * @return string
     */
    public static function script(string $name): string
    {
        return dirname(__DIR__) . '/resources/js/' . $name . '.js';
    }

    /**
     * Tells a view whether it must inline the given script now.
     * Returns true once per script and per PHP process (a request on php-fpm),
     * and always false when a bundler loads the scripts (see useBundledScripts()).
     *
     * @param string $name Script name, e.g. "jkc-table".
     * @return bool
     */
    public static function shouldInline(string $name): bool
    {
        if (! self::$inlineScripts || isset(self::$emittedScripts[$name])) {
            return false;
        }

        self::$emittedScripts[$name] = true;

        return true;
    }

    /**
     * Stops the views from inlining scripts (for projects that import
     * resources/js/*.js through Vite or another bundler).
     *
     * @param bool $bundled True when a bundler loads the scripts.
     * @return void
     */
    public static function useBundledScripts(bool $bundled = true): void
    {
        self::$inlineScripts = ! $bundled;
    }

    /**
     * Forgets which scripts were inlined and re-enables inlining.
     * Call it at the start of each request on long-running servers
     * (Swoole, RoadRunner, Octane) and between tests.
     *
     * @return void
     */
    public static function resetScripts(): void
    {
        self::$inlineScripts = true;
        self::$emittedScripts = [];
    }

    /**
     * Components name available ( without extension )
     *
     * @return string[]
     */
    public static function all(): array
    {
        $path = self::path() . '/jkc';

        if (! is_dir($path)) {
            return [];
        }

        $iterator = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator(
                $path,
                \RecursiveDirectoryIterator::SKIP_DOTS
            )
        );

        $names = [];

        foreach ($iterator as $file) {
            if (
                $file->isFile() && $file->getExtension() === 'php'
                && str_ends_with($file->getFilename(), '.blade.php')
            ) {
                $names[] = basename($file->getFilename(), '.blade.php');
            }
        }

        sort($names);

        return array_unique($names);
    }
}
