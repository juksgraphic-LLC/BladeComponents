<?php

declare(strict_types=1);

namespace Juksgraphic\BladeComponents\Components\Forms;

/**
 * Advanced file upload configuration and initial preview helper.
 */
class FileInput extends FormField
{
    /**
     * Default configuration values.
     *
     * @var array<string, mixed>
     */
    protected array $defaults = [
        'label' => '',
        'name' => 'file',
        'required' => false,
        'accept' => '',
        'recommended' => '',
        'model' => 'formData',
        'initialData' => [],
        'class' => '',
    ];

    /**
     * Create and sanitize the file input configuration.
     *
     * @param array<string, mixed> $config Custom configuration values.
     */
    public function __construct(array $config = [])
    {
        parent::__construct($config);

        $name = $this->sanitizeFieldName($this->config['name'], 'file');
        $initialData = is_array($this->config['initialData']) ? $this->config['initialData'] : [];
        $rawUrl = is_scalar($initialData[$name] ?? null) ? trim((string) $initialData[$name]) : '';

        $this->config['label'] = is_scalar($this->config['label']) ? (string) $this->config['label'] : '';
        $this->config['name'] = $name;
        $this->config['required'] = (bool) $this->config['required'];
        $this->config['accept'] = is_scalar($this->config['accept']) ? trim((string) $this->config['accept']) : '';
        $this->config['recommended'] = is_scalar($this->config['recommended']) ? (string) $this->config['recommended'] : '';
        $this->config['model'] = $this->sanitizeModelPath($this->config['model']);
        $this->config['initialData'] = $initialData;
        $this->config['class'] = is_scalar($this->config['class']) ? trim((string) $this->config['class']) : '';
        $this->config['initialFileUrl'] = $this->sanitizeFileUrl($rawUrl);
    }

    /**
     * Get accepted file types attribute.
     *
     * @return string
     */
    public function accept(): string
    {
        return $this->config['accept'];
    }

    /**
     * Get recommended formats/sizes helper text.
     *
     * @return string
     */
    public function recommended(): string
    {
        return $this->config['recommended'];
    }

    /**
     * Get sanitized initial file URL when present.
     *
     * @return string
     */
    public function initialFileUrl(): string
    {
        return (string) $this->config['initialFileUrl'];
    }

    /**
     * Reject unsafe URI schemes on initial file URLs.
     *
     * @param string $url Raw URL string.
     *
     * @return string
     */
    private function sanitizeFileUrl(string $url): string
    {
        if ($url === '' || preg_match('/^\s*(javascript|data|vbscript):/i', $url)) {
            return '';
        }

        return $url;
    }
}
