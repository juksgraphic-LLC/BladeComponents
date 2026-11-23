<?php

declare(strict_types=1);

namespace Juksgraphic\BladeComponents\Components\Filters\Pagers;


use InvalidArgumentException;
use Juksgraphic\BladeComponents\Components\Filters\Bars\BarCommands;
use Juksgraphic\BladeComponents\Components\Filters\Bars\BarEvents;

/**
 * Builds the pager configuration.
 */
final class Pager
{
    /** @var string DOM id prefix. */
    private $id;

    /** @var string Followed bar id. */
    private $bar;

    /** @var array<int, int> Page size choices. */
    private $perPageOptions;

    /** @var int Pages kept around the current one. */
    private $siblings;

    /** @var bool Show the "x to y of z" text. */
    private $summary;

    /** @var bool Show the page size select. */
    private $perPage;

    /** @var bool Show the page buttons. */
    private $pages;

    /** @var array<string, string> Texts and templates. */
    private $texts;

    /**
     * Builds and validates the configuration.
     *
     * @param array<string, mixed> $props Raw component props.
     *
     * @throws InvalidArgumentException On invalid configuration.
     */
    public function __construct(array $props)
    {
        $this->bar            = PagerNormalizer::bar($props['bar'] ?? null);
        $this->id             = PagerNormalizer::id($props['id'] ?? null);
        $this->perPageOptions = PagerNormalizer::perPageOptions($props['perPageOptions'] ?? null);
        $this->siblings       = PagerNormalizer::siblings($props['siblings'] ?? null);
        $this->summary        = PagerNormalizer::part($props['summary'] ?? null, PagerDefaults::ITEMS['SUMMARY']);
        $this->perPage        = PagerNormalizer::part($props['perPage'] ?? null, PagerDefaults::ITEMS['PER_PAGE']);
        $this->pages          = PagerNormalizer::part($props['pages']   ?? null, PagerDefaults::ITEMS['PAGES']);
        $this->texts          = PagerNormalizer::texts($props['texts']  ?? null);
    }

    /**
     * Returns the DOM id prefix.
     *
     * @return string
     */
    public function id(): string
    {
        return $this->id;
    }

    /**
     * Tells whether the summary text shows.
     *
     * @return bool
     */
    public function hasSummary(): bool
    {
        return $this->summary;
    }

    /**
     * Tells whether the size select shows.
     *
     * @return bool
     */
    public function hasPerPage(): bool
    {
        return $this->perPage && $this->perPageOptions !== [];
    }

    /**
     * Tells whether the page buttons show.
     *
     * @return bool
     */
    public function hasPages(): bool
    {
        return $this->pages;
    }

    /**
     * Tells whether the pager renders anything.
     *
     * @return bool
     */
    public function isVisible(): bool
    {
        return $this->hasSummary() || $this->hasPerPage() || $this->hasPages();
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
            'barId' => $this->bar,
            'perPageOptions' => $this->perPageOptions,
            'siblings' => $this->siblings,
            'texts' => $this->texts,
            'events' => BarEvents::ITEMS,
            'commands' => BarCommands::ITEMS,
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
            JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT | JSON_THROW_ON_ERROR
        );
    }
}
