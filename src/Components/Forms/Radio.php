<?php

declare(strict_types=1);

namespace Juksgraphic\BladeComponents\Components\Forms;

/**
 * Custom radio group configuration and initial state helper.
 */
class Radio extends FormField
{
    /**
     * Default configuration values.
     *
     * @var array<string, mixed>
     */
    protected array $defaults = [
        'label'       => '',
        'name'        => 'radio',
        'options'     => [],
        'model'       => 'formData',
        'required'    => false,
        'initialData' => [],
        'class'       => '',
    ];

    /**
     * Create and sanitize the radio group configuration.
     *
     * @param array<string, mixed> $config Custom configuration values.
     */
    public function __construct(array $config = [])
    {
        parent::__construct($config);

        $name        = $this->sanitizeFieldName($this->config['name'], 'radio');
        $options     = $this->normalizeOptions((array) $this->config['options']);
        $initialData = is_array($this->config['initialData']) ? $this->config['initialData'] : [];
        $fallbackId  = $options[0]['id'] ?? '';

        $this->config['label']        = is_scalar($this->config['label']) ? (string) $this->config['label'] : '';
        $this->config['name']         = $name;
        $this->config['options']      = $options;
        $this->config['model']        = $this->sanitizeModelPath($this->config['model']);
        $this->config['required']     = (bool) $this->config['required'];
        $this->config['initialData']  = $initialData;
        $this->config['class']        = is_scalar($this->config['class']) ? trim((string) $this->config['class']) : '';
        $this->config['initialValue'] = (string) ($initialData[$name] ?? $fallbackId);
    }

    /**
     * Get normalized radio options.
     *
     * @return array<int, array{id: string, title: string}>
     */
    public function options(): array
    {
        return $this->config['options'];
    }

    /**
     * Get normalized initial selected value.
     *
     * @return string
     */
    public function initialValue(): string
    {
        return (string) $this->config['initialValue'];
    }
}