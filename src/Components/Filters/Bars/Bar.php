<?php

declare(strict_types=1);

namespace Juksgraphic\BladeComponents\Components\Filters\Bars;

use InvalidArgumentException;

/**
 * Builds the filter bar configuration.
 */
final class Bar
{
    /** @var string Bar identifier. */
    private $id;

    /** @var string Data endpoint URL. */
    private $endpoint;

    /** @var array<int, array<string, mixed>> Normalized filters. */
    private $filters;

    /** @var array<string, mixed> Search settings. */
    private $search;

    /** @var string Style variant. */
    private $variant;

    /** @var int Items per page. */
    private $perPage;

    /** @var array{column: string|null, direction: string} Initial sort. */
    private $sort;

    /** @var bool Fetch on load. */
    private $autoload;

    /**
     * Builds and validates the configuration.
     *
     * @param array<string, mixed> $props Raw component props.
     *
     * @throws InvalidArgumentException On invalid configuration.
     */
    public function __construct(array $props)
    {
        $this->endpoint = BarNormalizer::endpoint($props['endpoint'] ?? null);
        $this->id       = BarNormalizer::id($props['id'] ?? null);
        $this->filters  = FilterNormalizer::normalizeAll($props['filters'] ?? []);
        $this->search   = BarNormalizer::search($props['search'] ?? null);
        $this->variant  = BarNormalizer::variant($props['variant'] ?? null);
        $this->perPage  = BarNormalizer::perPage($props['perPage'] ?? null);
        $this->sort     = BarNormalizer::sort($props['sort'] ?? null);
        $this->autoload = (bool) ($props['autoload'] ?? true);
    }

    /**
     * Returns the bar identifier.
     *
     * @return string
     */
    public function id(): string
    {
        return $this->id;
    }

    /**
     * Returns the style variant.
     *
     * @return string
     */
    public function variant(): string
    {
        return $this->variant;
    }

    /**
     * Tells whether filters exist.
     *
     * @return bool
     */
    public function hasFilters(): bool
    {
        return $this->filters !== [];
    }

    /**
     * Tells whether search is enabled.
     *
     * @return bool
     */
    public function hasSearch(): bool
    {
        return $this->search['enabled'];
    }

    /**
     * Tells whether the bar renders UI.
     *
     * Sort has no UI, so it never makes the bar visible.
     *
     * @return bool
     */
    public function isVisible(): bool
    {
        return $this->hasFilters() || $this->hasSearch();
    }

    /**
     * Returns the configuration for Alpine.
     *
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'endpoint' => $this->endpoint,
            'page'     => BarDefaults::ITEMS['PAGE'],
            'perPage'  => $this->perPage,
            'autoload' => $this->autoload,
            'search'   => $this->search,
            'filters'   => $this->filters,
            'initialFilters' => $this->initialFilters(),
            'sort' => $this->sort,
            'directions' => BarDirections::ITEMS,
            'events'     => BarEvents::ITEMS,
            'commands'   => BarCommands::ITEMS,
        ];
    }

    /**
     * Returns attribute-safe JSON configuration.
     *
     * @return string
     */
    public function toJson(): string
    {
        return json_encode(
            $this->toArray(),
            JSON_HEX_TAG |
                JSON_HEX_APOS |
                JSON_HEX_AMP |
                JSON_HEX_QUOT |
                JSON_THROW_ON_ERROR
        );
    }

    /**
     * Collects explicit first-request defaults.
     *
     * @return array<string, string|array<int, string>>
     */
    private function initialFilters(): array
    {
        $initial = [];

        foreach ($this->filters as $filter) {
            if ($filter['default'] !== null && $filter['default'] !== []) {
                $initial[$filter['key']] = $filter['default'];
            }
        }

        return $initial;
    }
}
