<?php

declare(strict_types=1);

namespace Juksgraphic\BladeComponents\Table;

/**
 * Handles cell-level configuration, value expression compilation, and variant resolution.
 */
final class TableCell
{
    private const VARIANTS = [
        'success' => [
            'badge' => 'bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 border-emerald-500/20',
            'bar'   => 'bg-emerald-500',
        ],
        'warning' => [
            'badge' => 'bg-amber-500/10 text-amber-600 dark:text-amber-400 border-amber-500/20',
            'bar'   => 'bg-amber-500',
        ],
        'danger' => [
            'badge' => 'bg-rose-500/10 text-rose-600 dark:text-rose-400 border-rose-500/20',
            'bar'   => 'bg-rose-500',
        ],
        'primary' => [
            'badge' => 'bg-primary/10 text-primary border-primary/20',
            'bar'   => 'bg-primary',
        ],
        'info' => [
            'badge' => 'bg-sky-500/10 text-sky-600 dark:text-sky-400 border-sky-500/20',
            'bar'   => 'bg-sky-500',
        ],
        'neutral' => [
            'badge' => 'bg-surface-alt text-on-surface/70 dark:bg-surface-dark-alt dark:text-on-surface-dark/70 border-outline dark:border-outline-dark',
            'bar'   => '',
        ],
    ];

    /**
     * @param array<string, mixed> $definition
     */
    public function __construct(
        private readonly array $definition,
    ) {}

    /**
     * Compiles the cell value path into a safe Alpine property access expression.
     */
    public function valueExpr(string|int|null $overrideKey = null): string
    {
        $key = $overrideKey !== null ? (string) $overrideKey : ($this->definition['key'] ?? '');

        return self::compileKey($key);
    }

    /**
     * Compiles the avatar path into a safe Alpine expression.
     */
    public function avatarExpr(): string
    {
        $avatarKey = $this->definition['avatarKey'] ?? 'avatar';

        return self::compileKey((string) $avatarKey);
    }

    /**
     * Compiles the subtitle path into a safe Alpine expression.
     */
    public function subtitleExpr(): string
    {
        $subtitleKey = $this->definition['subtitleKey'] ?? null;

        return is_string($subtitleKey) && trim($subtitleKey) !== '' ? self::compileKey($subtitleKey) : '';
    }

    /**
     * Indicates whether a subtitle key is present.
     */
    public function hasSubtitle(): bool
    {
        $subtitleKey = $this->definition['subtitleKey'] ?? null;

        return is_string($subtitleKey) && trim($subtitleKey) !== '';
    }

    /**
     * Resolves the Tailwind progress bar class based on variant rules.
     */
    public function progressClass(): string
    {
        $name = $this->definition['variant'] ?? 'primary';
        $variant = self::VARIANTS[$name] ?? self::VARIANTS['primary'];

        return $variant['bar'] !== '' ? $variant['bar'] : self::VARIANTS['primary']['bar'];
    }

    /**
     * Generates the JSON map for badge variants and default styles.
     */
    public function badgeMapJson(): string
    {
        $variants = is_array($this->definition['variants'] ?? null) ? $this->definition['variants'] : [];
        $defaultVariant = $this->definition['defaultVariant'] ?? 'neutral';

        $map = [];
        foreach ($variants as $val => $variantName) {
            $variantData = self::VARIANTS[$variantName] ?? self::VARIANTS['neutral'];
            $map[(string) $val] = $variantData['badge'];
        }

        $defaultData = self::VARIANTS[$defaultVariant] ?? self::VARIANTS['neutral'];

        return (string) json_encode([
            'map' => $map,
            'default' => $defaultData['badge'],
        ], JSON_HEX_TAG |
            JSON_HEX_APOS |
            JSON_HEX_QUOT |
            JSON_HEX_AMP |
            JSON_UNESCAPED_UNICODE);
    }

    /**
     * Compiles a dot-notated string path into a safe JavaScript optional chaining expression.
     */
    private static function compileKey(string $path): string
    {
        $path = trim($path);
        if ($path === '') {
            return "''";
        }

        $segments = explode('.', $path);
        $expr = 'item';

        foreach ($segments as $segment) {
            $segment = trim($segment);
            if ($segment === '') {
                continue;
            }

            if (preg_match('/^[a-zA-Z_$][a-zA-Z0-9_$]*$/', $segment) === 1) {
                $expr .= '?.' . $segment;
            } else {
                $expr .= '?.' . '[' . json_encode($segment, JSON_HEX_APOS | JSON_UNESCAPED_UNICODE) . ']';
            }
        }

        return $expr;
    }
}
