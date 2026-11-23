<?php

declare(strict_types=1);

namespace Juksgraphic\BladeComponents\Components\Forms;

/**
 * Standard and Quill rich-text textarea configuration helper.
 */
class Textarea extends FormField
{
    /**
     * Default Quill Snow toolbar configuration.
     *
     * @var array<int, array<int, mixed>>
     */
    public const DEFAULT_TOOLBAR = [
        [['header' => [1, 2, 3, 4, 5, 6, false]]],
        [['font' => []]],
        ['link', 'image', 'video', 'formula'],
        ['bold', 'italic', 'underline', 'strike'],
        ['blockquote', 'code-block'],
        [['list' => 'ordered'], ['list' => 'bullet'], ['list' => 'check']],
        [['indent' => '-1'], ['indent' => '+1']],
        [['align' => []]],
        ['clean'],
    ];

    /**
     * Default configuration values.
     *
     * @var array<string, mixed>
     */
    protected array $defaults = [
        'label'       => '',
        'name'        => 'content',
        'model'       => 'formData',
        'required'    => false,
        'useQuill'    => null,
        'isSimple'    => false,
        'placeholder' => 'Enter your text here...',
        'rows'        => 4,
        'class'       => '',
    ];

    /**
     * Create and sanitize the textarea configuration.
     *
     * @param array<string, mixed> $config Custom configuration values.
     */
    public function __construct(array $config = [])
    {
        parent::__construct($config);

        $useQuill = ($this->config['useQuill'] ?? null) === null
            ? !((bool) ($this->config['isSimple'] ?? false))
            : (bool) $this->config['useQuill'];

        $rows = is_numeric($this->config['rows']) ? (int) $this->config['rows'] : 4;

        $this->config['label']       = is_scalar($this->config['label']) ? (string) $this->config['label'] : '';
        $this->config['name']        = $this->sanitizeFieldName($this->config['name'], 'content');
        $this->config['model']       = $this->sanitizeModelPath($this->config['model']);
        $this->config['required']    = (bool) $this->config['required'];
        $this->config['useQuill']    = $useQuill;
        $this->config['isSimple']    = (bool) $this->config['isSimple'];
        $this->config['placeholder'] = is_scalar($this->config['placeholder']) ? (string) $this->config['placeholder'] : 'Enter your text here...';
        $this->config['rows']        = $rows > 0 ? $rows : 4;
        $this->config['class']       = is_scalar($this->config['class']) ? trim((string) $this->config['class']) : '';
    }

    /**
     * Check whether Quill editor mode is active.
     *
     * @return bool
     */
    public function usesQuill(): bool
    {
        return $this->config['useQuill'];
    }

    /**
     * Get textarea placeholder text.
     *
     * @return string
     */
    public function placeholder(): string
    {
        return $this->config['placeholder'];
    }

    /**
     * Get row count for standard textarea mode.
     *
     * @return int
     */
    public function rows(): int
    {
        return $this->config['rows'];
    }

    /**
     * Get Quill toolbar modules configuration.
     *
     * @return array<int, array<int, mixed>>
     */
    public function toolbar(): array
    {
        return self::DEFAULT_TOOLBAR;
    }

    /**
     * Get Alpine property access expression (`model['name']`).
     *
     * @return string
     */
    public function modelProperty(): string
    {
        return $this->model() . "['" . $this->name() . "']";
    }
}