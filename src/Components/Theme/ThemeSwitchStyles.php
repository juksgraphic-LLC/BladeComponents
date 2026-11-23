<?php

declare(strict_types=1);

namespace Juksgraphic\BladeComponents\Components\Theme;

/**
 * Tailwind classes for the theme switch track, knob, and animated icons.
 */
class ThemeSwitchStyles
{
    /**
     * Dimension tokens indexed by switch size.
     *
     * @var array<string, array<string, string>>
     */
    private const SIZE_MAP = [
        'sm' => [
            'track' => 'h-7 w-14',
            'knob'  => 'size-5',
            'shift' => 'group-aria-checked:translate-x-7',
            'icon'  => 'size-3.5',
        ],
        'md' => [
            'track' => 'h-8 w-16',
            'knob'  => 'size-6',
            'shift' => 'group-aria-checked:translate-x-8',
            'icon'  => 'size-4',
        ],
        'lg' => [
            'track' => 'h-10 w-20',
            'knob'  => 'size-8',
            'shift' => 'group-aria-checked:translate-x-10',
            'icon'  => 'size-5',
        ],
    ];

    /**
     * ThemeSwitch instance providing size and custom classes.
     *
     * @var ThemeSwitch
     */
    private ThemeSwitch $switch;

    /**
     * Resolved size token set.
     *
     * @var array<string, string>
     */
    private array $tokens;

    /**
     * Create the styles helper.
     *
     * @param ThemeSwitch $switch ThemeSwitch instance to style.
     */
    public function __construct(ThemeSwitch $switch)
    {
        $this->switch = $switch;
        $this->tokens = self::SIZE_MAP[$switch->size()] ?? self::SIZE_MAP['md'];
    }

    /**
     * Get switch button track classes.
     *
     * @return string
     */
    public function button(): string
    {
        $classes = "group relative inline-flex shrink-0 cursor-pointer items-center justify-between rounded-full border p-[3px] {$this->tokens['track']} border-outline bg-surface-alt transition-colors motion-reduce:transition-none dark:border-outline-dark dark:bg-surface-dark-alt focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary dark:focus-visible:outline-primary-dark";

        if ($this->switch->extraClass() !== '') {
            $classes .= ' ' . $this->switch->extraClass();
        }

        $normalized = preg_replace('/\s+/', ' ', trim($classes));

        return $normalized ?? trim($classes);
    }

    /**
     * Get background track icon container classes.
     *
     * @return string
     */
    public function trackIconSlot(): string
    {
        return "flex {$this->tokens['knob']} items-center justify-center text-on-surface opacity-60 dark:text-on-surface-dark";
    }

    /**
     * Get SVG size classes for track and knob icons.
     *
     * @return string
     */
    public function iconSize(): string
    {
        return $this->tokens['icon'];
    }

    /**
     * Get sliding knob container classes.
     *
     * @return string
     */
    public function knob(): string
    {
        return "pointer-events-none absolute left-[3px] top-[3px] flex {$this->tokens['knob']} translate-x-0 {$this->tokens['shift']} items-center justify-center rounded-full bg-primary text-on-primary shadow-sm duration-300 ease-out group-data-[ready=true]:transition-transform motion-reduce:transition-none dark:bg-primary-dark dark:text-on-primary-dark";
    }

    /**
     * Get sun icon classes inside the sliding knob.
     *
     * @return string
     */
    public function knobSunIcon(): string
    {
        $base  = 'absolute duration-300 group-data-[ready=true]:transition-all motion-reduce:transition-none';
        $state = 'rotate-0 scale-100 opacity-100 group-aria-checked:-rotate-90 group-aria-checked:scale-50 group-aria-checked:opacity-0';

        return "{$base} {$this->tokens['icon']} {$state}";
    }

    /**
     * Get moon icon classes inside the sliding knob.
     *
     * @return string
     */
    public function knobMoonIcon(): string
    {
        $base  = 'absolute duration-300 group-data-[ready=true]:transition-all motion-reduce:transition-none';
        $state = 'rotate-90 scale-50 opacity-0 group-aria-checked:rotate-0 group-aria-checked:scale-100 group-aria-checked:opacity-100';

        return "{$base} {$this->tokens['icon']} {$state}";
    }
}