<?php

declare(strict_types=1);

namespace Juksgraphic\BladeComponents;

final class BladeComponents
{
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
