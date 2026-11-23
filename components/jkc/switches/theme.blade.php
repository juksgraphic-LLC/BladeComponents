@php
    use Juksgraphic\BladeComponents\Components\Theme\ThemeSwitch;
    use Juksgraphic\BladeComponents\Components\Theme\ThemeSwitchStyles;

   
    $themeSwitch = new ThemeSwitch([
        'id'          => $id ?? null,
        'size'        => $size ?? 'md',
        'initialDark' => $initialDark ?? false,
        'storageKey'  => $storageKey  ?? null,
        'label'       => $label ?? 'Toggle dark mode',
        'class'       => $class ?? '',
        'attrs'       => $attrs ?? '',
    ]);

    $styles = new ThemeSwitchStyles($themeSwitch);
@endphp

<!-- Theme switch button with Alpine.js persistence and event broadcasting -->
<button
    @if ($themeSwitch->id() !== null) id="{{ $themeSwitch->id() }}" @endif
    type="button"
    role="switch"
    aria-checked="{{ $themeSwitch->initialDark() ? 'true' : 'false' }}"
    aria-label="{{ htmlspecialchars($themeSwitch->label(), ENT_QUOTES, 'UTF-8') }}"
    data-ready="false"
    x-cloak
    x-data="{
        ...{{ $themeSwitch->toJson() }},

        init() {
            if (this.storageKey) {
                try {
                    const saved = localStorage.getItem(this.storageKey);
                    if (saved !== null) {
                        this.isDark = (saved === '1');
                    }
                } catch (e) {}
            }
            requestAnimationFrame(() => requestAnimationFrame(() => {
                this.ready = true;
            }));
        },

        
        toggle() {
            this.isDark = !this.isDark;
            if (this.storageKey) {
                try {
                    localStorage.setItem(this.storageKey, this.isDark ? '1' : '0');
                } catch (e) {}
            }
            const detail = {
                isDark: this.isDark,
                theme: this.isDark ? 'dark' : 'light'
            };
            this.$dispatch(this.isDark ? 'jkc-theme-dark' : 'jkc-theme-light', detail);
            this.$dispatch('jkc-theme-change', detail);
        }
    }"
    x-on:click="toggle()"
    x-bind:aria-checked="isDark.toString()"
    x-bind:data-ready="ready"
    x-on:jkc-theme-change.window="isDark = $event.detail.isDark"
    class="{{ $styles->button() }}"
    {!! $themeSwitch->attrs() !!}
>

    <!-- Sun background icon on left side -->
    <span class="{{ $styles->trackIconSlot() }}" aria-hidden="true">
        <svg class="{{ $styles->iconSize() }}" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="{{ ThemeSwitch::SUN_PATH }}" />
        </svg>
    </span>

    <!-- Moon background icon on right side -->
    <span class="{{ $styles->trackIconSlot() }}" aria-hidden="true">
        <svg class="{{ $styles->iconSize() }}"
             fill="none" stroke="currentColor"
             stroke-width="1.5" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round"
             d="{{ ThemeSwitch::MOON_PATH }}" />
        </svg>
    </span>

    <!-- Sliding knob with rotating and scaling sun/moon icons -->
    <span class="{{ $styles->knob() }}" aria-hidden="true">
        <svg class="{{ $styles->knobSunIcon() }}"
             fill="none" stroke="currentColor"
              stroke-width="1.5" viewBox="0 0 24 24">
            <path stroke-linecap="round"
             stroke-linejoin="round" d="{{ ThemeSwitch::SUN_PATH }}" />
        </svg>

        <svg class="{{ $styles->knobMoonIcon() }}"
             fill="none" stroke="currentColor"
              stroke-width="1.5" viewBox="0 0 24 24">
            <path stroke-linecap="round"
             stroke-linejoin="round"
              d="{{ ThemeSwitch::MOON_PATH }}" />
        </svg>
    </span>

</button>