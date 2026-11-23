<?php

declare(strict_types=1);

namespace Juksgraphic\BladeComponents\Components\Forms;

/**
 * Standard text/HTML input field configuration helper.
 */
class Input extends FormField
{
    /**
     * Default configuration values.
     *
     * @var array<string, mixed>
     */
    protected array $defaults = [
        'label' => '',
        'name' => 'input',
        'type' => 'text',
        'model' => 'formData',
        'required' => false,
        'placeholder' => '',
        'class' => '',
    ];

    /**
     * Create and sanitize the input configuration.
     *
     * @param array<string, mixed> $config Custom configuration values.
     */
    public function __construct(array $config = [])
    {
        parent::__construct($config);

        $type = is_scalar($this->config['type']) ? (string) $this->config['type'] : 'text';

        $this->config['label'] = is_scalar($this->config['label']) ? (string) $this->config['label'] : '';
        $this->config['name'] = $this->sanitizeFieldName($this->config['name'], 'input', '/[^A-Za-z0-9_\[\]-]/');
        $this->config['type'] = $type !== '' ? $type : 'text';
        $this->config['model'] = $this->sanitizeModelPath($this->config['model']);
        $this->config['required'] = (bool) $this->config['required'];
        $this->config['placeholder'] = is_scalar($this->config['placeholder']) ? (string) $this->config['placeholder'] : '';
        $this->config['class'] = is_scalar($this->config['class']) ? trim((string) $this->config['class']) : '';
    }

    /**
     * Get the input HTML `type` attribute.
     *
     * @return string
     */
    public function type(): string
    {
        return $this->config['type'];
    }

    /**
     * Get the input placeholder text.
     *
     * @return string
     */
    public function placeholder(): string
    {
        return $this->config['placeholder'];
    }

    /**
     * Get the Alpine `x-model` binding expression.
     *
     * @return string
     */
    public function modelBinding(): string
    {
        return $this->model() . '.' . $this->name();
    }
}
