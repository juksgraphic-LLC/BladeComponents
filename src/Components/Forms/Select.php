<?php

declare(strict_types=1);

namespace Juksgraphic\BladeComponents\Components\Forms;

/**
 * Searchable single & multi-select configuration and initial state helper.
 */
class Select extends FormField
{
    /**
     * Default configuration values.
     *
     * @var array<string, mixed>
     */
    protected array $defaults = [
        'label' => '',
        'name' => 'select',
        'multiple' => false,
        'options' => [],
        'model' => 'formData',
        'initialData' => [],
        'class' => '',
    ];

    /**
     * Create and sanitize the select field configuration.
     *
     * @param array<string, mixed> $config Custom configuration values.
     */
    public function __construct(array $config = [])
    {
        parent::__construct($config);

        $isMultiple = (bool) $this->config['multiple'];
        $name = $this->sanitizeFieldName($this->config['name'], 'select');
        $initialData = is_array($this->config['initialData']) ? $this->config['initialData'] : [];
        $rawValue = $initialData[$name] ?? null;

        $this->config['label'] = is_scalar($this->config['label']) ? (string) $this->config['label'] : '';
        $this->config['name'] = $name;
        $this->config['multiple'] = $isMultiple;
        $this->config['options'] = $this->normalizeOptions((array) $this->config['options']);
        $this->config['model'] = $this->sanitizeModelPath($this->config['model']);
        $this->config['initialData'] = $initialData;
        $this->config['class'] = is_scalar($this->config['class']) ? trim((string) $this->config['class']) : '';

        $this->config['initialValue'] = $isMultiple
            ? $this->normalizeMultiValue($rawValue)
            : (is_array($rawValue) ? (string) ($rawValue[0] ?? '') : (string) ($rawValue ?? ''));
    }

    /**
     * Check whether multiple pill selection is enabled.
     *
     * @return bool
     */
    public function isMultiple(): bool
    {
        return $this->config['multiple'];
    }

    /**
     * Get normalized select options.
     *
     * @return array<int, array{id: string, title: string}>
     */
    public function options(): array
    {
        return $this->config['options'];
    }

    /**
     * Get normalized initial selected value(s).
     *
     * @return string|array<int, string>
     */
    public function initialValue(): string|array
    {
        return $this->config['initialValue'];
    }
}
