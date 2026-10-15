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
     * @return string[]
     */
    public static function all(): array
    {
        $files = glob(self::path() . '/jkc/*.blade.php') ?: [];
        $names = array_map(fn (string $f) => basename($f, '.blade.php'), $files);
        sort($names);

        return $names;
    }
}
