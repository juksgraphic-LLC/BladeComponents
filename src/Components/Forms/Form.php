<?php

declare(strict_types=1);

namespace Juksgraphic\BladeComponents\Components\Forms;

use Juksgraphic\BladeComponents\Components\Component;

/**
 * Form container configuration and dynamic field state helper.
 */
class Form extends Component
{
    /**
     * Allowed HTTP verbs.
     *
     * @var array<int, string>
     */
    public const METHODS = [
        'GET', 'POST', 'PUT', 'PATCH', 'DELETE',
    ];

    /**
     * Mapping from field type to Blade view path.
     *
     * @var array<string, string>
     */
    public const FIELD_VIEWS = [
        'select' => 'jkc.forms.select',
        'textarea' => 'jkc.forms.textarea',
        'file' => 'jkc.forms.file',
        'checkbox' => 'jkc.forms.checkbox',
        'radio' => 'jkc.forms.radio',
    ];

    /**
     * Default configuration values.
     *
     * @var array<string, mixed>
     */
    protected array $defaults = [
        'fields' => [],
        'initialData' => [],
        'action' => '',
        'method' => 'POST',
        'submitAttrs' => '',
        'submitText' => null,
        'class' => '',
        'attrs' => '',
    ];

    /**
     * Create and sanitize the form configuration.
     *
     * @param array<string, mixed> $config Custom configuration values.
     */
    public function __construct(array $config = [])
    {
        parent::__construct($config);

        $method = strtoupper(is_scalar($this->config['method']) ? (string) $this->config['method'] : 'POST');

        $this->config['fields'] = is_array($this->config['fields']) ? array_values($this->config['fields']) : [];
        $this->config['initialData'] = is_array($this->config['initialData']) ? $this->config['initialData'] : [];
        $this->config['action'] = is_scalar($this->config['action']) ? (string) $this->config['action'] : '';
        $this->config['method'] = in_array($method, self::METHODS, true) ? $method : 'POST';
        $this->config['submitAttrs'] = is_scalar($this->config['submitAttrs']) ? trim((string) $this->config['submitAttrs']) : '';
        $this->config['submitText'] = isset($this->config['submitText']) && is_scalar($this->config['submitText']) && (string) $this->config['submitText'] !== ''
            ? (string) $this->config['submitText']
            : null;
        $this->config['class'] = is_scalar($this->config['class']) ? trim((string) $this->config['class']) : '';
        $this->config['attrs'] = is_scalar($this->config['attrs']) ? trim((string) $this->config['attrs']) : '';
    }

    /**
     * Get normalized field definitions.
     *
     * @return array<int, array<string, mixed>>
     */
    public function fields(): array
    {
        return $this->config['fields'];
    }

    /**
     * Get initial form data values.
     *
     * @return array<string, mixed>
     */
    public function initialData(): array
    {
        return $this->config['initialData'];
    }

    /**
     * Check whether the form represents an edit operation.
     *
     * @return bool
     */
    public function isEdit(): bool
    {
        return ! empty($this->config['initialData']['id']);
    }

    /**
     * Get the form action target URL.
     *
     * @return string
     */
    public function action(): string
    {
        return $this->config['action'];
    }

    /**
     * Get the normalized HTTP method.
     *
     * @return string
     */
    public function method(): string
    {
        return $this->config['method'];
    }

    /**
     * Get the HTML form `method` attribute (`GET` or `POST`).
     *
     * @return string
     */
    public function htmlMethod(): string
    {
        return in_array($this->config['method'], ['GET', 'POST'], true) ? $this->config['method'] : 'POST';
    }

    /**
     * Check whether a hidden `_method` spoofing input is needed.
     *
     * @return bool
     */
    public function needsMethodSpoofing(): bool
    {
        return ! in_array($this->config['method'], ['GET', 'POST'], true);
    }

    /**
     * Get extra `<form>` tag attributes.
     *
     * @return string
     */
    public function submitAttrs(): string
    {
        return $this->config['submitAttrs'];
    }

    /**
     * Get custom submit button label when provided.
     *
     * @return string|null
     */
    public function submitText(): ?string
    {
        return $this->config['submitText'];
    }

    /**
     * Get extra container CSS classes.
     *
     * @return string
     */
    public function extraClass(): string
    {
        return $this->config['class'];
    }

    /**
     * Get extra container HTML attributes.
     *
     * @return string
     */
    public function attrs(): string
    {
        return $this->config['attrs'];
    }

    /**
     * Resolve the Blade view path for a given field definition.
     *
     * @param array<string, mixed> $field Field definition array.
     *
     * @return string
     */
    public function viewFor(array $field): string
    {
        $type = (string) ($field['type'] ?? 'text');

        return self::FIELD_VIEWS[$type] ?? 'jkc.forms.input';
    }

    /**
     * Build the include parameter array for a dynamic field.
     *
     * @param array<string, mixed> $field Field definition array.
     *
     * @return array<string, mixed>
     */
    public function fieldParams(array $field): array
    {
        $type = (string) ($field['type'] ?? 'text');

        return [
            'label' => $field['label'] ?? '',
            'name' => $field['name'] ?? 'field',
            'type' => $type,
            'options' => $field['options'] ?? [],
            'model' => 'formData',
            'multiple' => $field['multiple'] ?? false,
            'required' => $field['required'] ?? false,
            'useQuill' => $field['useQuill'] ?? false,
            'isSimple' => $field['isSimple'] ?? false,
            'accept' => $field['accept'] ?? '',
            'recommended' => $field['recommended'] ?? '',
            'placeholder' => $field['placeholder'] ?? '',
            'rows' => $field['rows'] ?? 4,
            'class' => $field['class'] ?? '',
            'initialData' => $this->config['initialData'],
        ];
    }

    /**
     * Get Alpine initial state for the form scope.
     *
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return [
            'formData' => $this->config['initialData'],
            'isEdit' => $this->isEdit(),
            'loading' => false,
        ];
    }
}
