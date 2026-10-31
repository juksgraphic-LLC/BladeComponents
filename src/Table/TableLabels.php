<?php

declare(strict_types=1);

namespace Juksgraphic\BladeComponents\Table;

/** Handles table labels, messages, and placeholder definitions. */
final class TableLabels
{
    /**
     * Summary of DEFAULT_LABELS
     * @var array
     */
    private const DEFAULT_LABELS = [
        'loadingError' => 'Failed to load the data',
        'deleteError' => 'Failed to delete the item',
        'imagePlaceholder' => '/assets/img/placeholder.png',
        'avatarPlaceholder' => '/assets/img/avatar-placeholder.png',
        'na' => 'N/A',
        'empty' => '-',
    ];

    /**
     * Summary of __construct
     * @param array<string, string> $labels
     */
    public function __construct(
        private readonly array $labels,
    ) {
    }

    /**
     * Creates table labels configuration from raw input.
     * @param mixed $value
     * @return TableLabels
     */
    public static function from(mixed $value): self
    {
        $given = is_array($value) ? $value : [];

        return new self(array_merge(self::DEFAULT_LABELS, $given));
    }

    /**
     *
     * Summary of toArray
     * @return string[]
     */
    public function toArray(): array
    {
        return $this->labels;
    }
}
