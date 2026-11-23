<?php

declare(strict_types=1);

namespace Juksgraphic\BladeComponents\Components\Feedbacks;

use Juksgraphic\BladeComponents\Components\Component;

/**
 * Global notifications stack manager configuration and initial state helper.
 */
class Notification extends Component
{
    /**
     * Allowed screen positions for the notification stack.
     *
     * @var array<int, string>
     */
    public const POSITIONS = [
        'top-left',
        'top-center',
        'top-right',
        'bottom-left',
        'bottom-center',
        'bottom-right',
    ];

    /**
     * Allowed notification variant aliases.
     *
     * @var array<int, string>
     */
    public const VARIANTS = [
        'info',
        'default',
        'primary',
        'success',
        'warning',
        'danger',
        'error',
        'message',
    ];

    /**
     * Default sound effect URL for notifications.
     *
     * @var string
     */
    public const DEFAULT_SOUND_URL = 'https://res.cloudinary.com/ds8pgw1pf/video/upload/v1728571480/penguinui/component-assets/sounds/ding.mp3';

    /**
     * Default configuration values.
     *
     * @var array<string, mixed>
     */
    protected array $defaults = [
        'id' => 'global-notifications',
        'position' => 'top-right',
        'max' => 20,
        'displayDuration' => 8000,
        'leaveDuration' => 400,
        'soundEffect' => false,
        'soundUrl' => self::DEFAULT_SOUND_URL,
    ];

    /**
     * Create and sanitize the notification stack configuration.
     *
     * @param array<string, mixed> $config Custom configuration values.
     */
    public function __construct(array $config = [])
    {
        parent::__construct($config);

        $position = (string) $this->config['position'];
        $max = $this->config['max'];
        $displayDuration = $this->config['displayDuration'];
        $leaveDuration = $this->config['leaveDuration'];

        $this->config['id'] = $this->safeId($this->config['id']);
        $this->config['position'] = in_array($position, self::POSITIONS, true) ? $position : 'top-right';
        $this->config['max'] = is_numeric($max) && (int) $max > 0 ? (int) $max : 20;
        $this->config['displayDuration'] = is_numeric($displayDuration) && (int) $displayDuration >= 0 ? (int) $displayDuration : 8000;
        $this->config['leaveDuration'] = is_numeric($leaveDuration) && (int) $leaveDuration >= 0 ? (int) $leaveDuration : 400;
        $this->config['soundEffect'] = (bool) $this->config['soundEffect'];
        $this->config['soundUrl'] = $this->sanitizeSoundUrl($this->config['soundUrl']);
    }

    /**
     * Get the container HTML identifier.
     *
     * @return string
     */
    public function id(): string
    {
        return $this->config['id'];
    }

    /**
     * Get the initial stack screen position.
     *
     * @return string
     */
    public function position(): string
    {
        return $this->config['position'];
    }

    /**
     * Get the maximum number of queued notifications.
     *
     * @return int
     */
    public function max(): int
    {
        return $this->config['max'];
    }

    /**
     * Get the auto-dismiss delay in milliseconds.
     *
     * @return int
     */
    public function displayDuration(): int
    {
        return $this->config['displayDuration'];
    }

    /**
     * Get the leave transition duration in milliseconds.
     *
     * @return int
     */
    public function leaveDuration(): int
    {
        return $this->config['leaveDuration'];
    }

    /**
     * Check whether sound playback is enabled on new notifications.
     *
     * @return bool
     */
    public function hasSoundEffect(): bool
    {
        return $this->config['soundEffect'];
    }

    /**
     * Get the sanitized sound audio URL.
     *
     * @return string
     */
    public function soundUrl(): string
    {
        return $this->config['soundUrl'];
    }

    /**
     * Get Alpine initial state for the notification stack manager.
     *
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return [
            'notifications' => [],
            'max' => $this->config['max'],
            'displayDuration' => $this->config['displayDuration'],
            'leaveDuration' => $this->config['leaveDuration'],
            'soundEffect' => $this->config['soundEffect'],
            'soundUrl' => $this->config['soundUrl'],
        ];
    }

    /**
     * Build a safe HTML identifier.
     *
     * @param mixed $id Raw identifier value.
     *
     * @return string
     */
    private function safeId(mixed $id): string
    {
        $clean = is_scalar($id) ? preg_replace('/[^A-Za-z0-9_-]/', '', (string) $id) : '';

        return ($clean !== null && $clean !== '') ? $clean : 'global-notifications';
    }

    /**
     * Reject unsafe URI schemes on the audio sound URL.
     *
     * @param mixed $url Raw audio URL.
     *
     * @return string
     */
    private function sanitizeSoundUrl(mixed $url): string
    {
        if (! is_string($url)) {
            return self::DEFAULT_SOUND_URL;
        }

        $trimmed = trim($url);

        if ($trimmed === '' || preg_match('/^\s*(javascript|data|vbscript):/i', $trimmed)) {
            return self::DEFAULT_SOUND_URL;
        }

        return $trimmed;
    }
}
