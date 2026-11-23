<?php

declare(strict_types=1);

namespace Juksgraphic\BladeComponents\Components\Forms;

/**
 * Checkbox switch and multi-checkbox group configuration helper.
 */
class Checkbox extends FormField
{
    /**
     * Default configuration values.
     *
     * @var array<string, mixed>
     */
    protected array $defaults = [
        'label'       => '',
        'name'        => 'checkbox',
        'multiple'    => false,
        'options'     => [],
        'model'       => 'formData',
        'initialData' => [],
        'class'       => '',
    ];

    /**
     * Create and sanitize the checkbox configuration.
     *
     * @param array<string, mixed> $config Custom configuration values.
     */
    public function __construct(array $config = [])
    {
        parent::__construct($config);

        $isMultiple  = (bool) $this->config['multiple'];
        $name        = $this->sanitizeFieldName($this->config['name'], 'checkbox');
        $initialData = is_array($this->config['initialData']) ? $this->config['initialData'] : [];
        $rawValue    = $initialData[$name] ?? null;

        $this->config['label']       = is_scalar($this->config['label']) ? (string) $this->config['label'] : '';
        $this->config['name']        = $name;
        $this->config['multiple']    = $isMultiple;
        $this->config['options']     = $this->normalizeOptions((array) $this->config['options']);
        $this->config['model']       = $this->sanitizeModelPath($this->config['model']);
        $this->config['initialData'] = $initialData;
        $this->config['class']       = is_scalar($this->config['class']) ? trim((string) $this->config['class']) : '';

        $this->config['initialValue'] = $isMultiple
            ? $this->normalizeMultiValue($rawValue)
            : (bool) filter_var($rawValue ?? false, FILTER_VALIDATE_BOOLEAN);
    }

    /**
     * Check whether multi-checkbox mode is enabled.
     *
     * @return bool
     */
    public function isMultiple(): bool
    {
        return $this->config['multiple'];
    }

    /**
     * Get normalized option items.
     *
     * @return array<int, array{id: string, title: string}>
     */
    public function options(): array
    {
        return $this->config['options'];
    }

    /**
     * Get normalized initial value (`bool` for single switch, `string[]` for multiple).
     *
     * @return bool|array<int, string>
     */
    public function initialValue(): bool|array
    {
        return $this->config['initialValue'];
    }
}