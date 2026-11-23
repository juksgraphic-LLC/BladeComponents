<?php

declare(strict_types=1);

namespace Juksgraphic\BladeComponents\Components\Forms;

use Juksgraphic\BladeComponents\Components\Component;

/**
 * Shared configuration and option normalization helpers for form fields.
 */
abstract class FormField extends Component
{
    /**
     * Get the sanitized HTML field name.
     *
     * @return string
     */
    public function name(): string
    {
        return (string) ($this->config['name'] ?? '');
    }

    /**
     * Get the field label text.
     *
     * @return string
     */
    public function label(): string
    {
        return (string) ($this->config['label'] ?? '');
    }

    /**
     * Check whether the field has a non-empty label.
     *
     * @return bool
     */
    public function hasLabel(): bool
    {
        return $this->label() !== '';
    }

    /**
     * Get the parent Alpine.js model path.
     *
     * @return string
     */
    public function model(): string
    {
        return (string) ($this->config['model'] ?? 'formData');
    }

    /**
     * Check whether the field is required.
     *
     * @return bool
     */
    public function isRequired(): bool
    {
        return (bool) ($this->config['required'] ?? false);
    }

    /**
     * Get extra CSS classes for the control.
     *
     * @return string
     */
    public function extraClass(): string
    {
        return (string) ($this->config['class'] ?? '');
    }

    /**
     * Sanitize an HTML field identifier/name.
     *
     * @param mixed  $raw      Raw name input.
     * @param string $fallback Fallback field name.
     * @param string $pattern  Regex pattern of allowed characters.
     *
     * @return string
     */
    protected function sanitizeFieldName(
        mixed $raw,
        string $fallback,
        string $pattern = '/[^A-Za-z0-9_-]/'
    ): string {
        $string = is_scalar($raw) ? (string) $raw : '';
        $clean = preg_replace($pattern, '', $string);

        return ($clean !== null && $clean !== '') ? $clean : $fallback;
    }

    /**
     * Sanitize an Alpine.js model path expression.
     *
     * @param mixed $raw Raw model path.
     *
     * @return string
     */
    protected function sanitizeModelPath(mixed $raw): string
    {
        $string = is_scalar($raw) ? trim((string) $raw) : '';
        $clean = preg_replace('/[^A-Za-z0-9_.\[\]\'\"]/', '', $string);

        return ($clean !== null && $clean !== '') ? $clean : 'formData';
    }

    /**
     * Normalize select, radio, or checkbox options into `['id', 'title']` items.
     *
     * @param array<int, mixed> $options Raw options list.
     *
     * @return array<int, array{id: string, title: string}>
     */
    protected function normalizeOptions(array $options): array
    {
        $normalized = [];

        foreach ($options as $option) {
            $item = (array)$option;

            $normalized[] = [
                'id' => (string) ($item['id'] ?? $item['value'] ?? ''),
                'title' => (string) ($item['title'] ?? $item['name'] ?? $item['label'] ?? ''),
            ];
        }

        return $normalized;
    }

    /**
     * Normalize a multi-value field initial state into a string array.
     *
     * @param mixed $value Raw initial value.
     *
     * @return array<int, string>
     */
    protected function normalizeMultiValue(mixed $value): array
    {
        if (is_string($value)) {
            $value = array_filter(explode(',', $value), static fn (string $part): bool => $part !== '');
        } elseif (! is_array($value)) {
            $value = $value !== null ? [$value] : [];
        }

        return array_values(array_map('strval', $value));
    }

    /**
     * Encode any value safely for embedding inside Alpine.js attributes.
     *
     * @param mixed $value Value to encode.
     *
     * @return string
     */
    public function encodeJson(mixed $value): string
    {
        return json_encode($value, self::JSON_FLAGS | JSON_THROW_ON_ERROR);
    }
}
