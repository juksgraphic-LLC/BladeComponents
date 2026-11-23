@php
    use Juksgraphic\BladeComponents\Components\Dropdowns\Dropdown;
    use Juksgraphic\BladeComponents\Components\Dropdowns\DropdownStyles;

   
    $dropdown = new Dropdown([
        'id'          => $id       ?? null,
        'trigger'     => $trigger  ?? 'button',
        'color'       => $color    ?? 'primary',
        'size'        => $size     ?? null,
        'darkMenu'    => $darkMenu ?? false,
        'align'       => $align    ?? null,
        'autoClose'   => $autoClose ?? true,
        'items'       => $items    ?? [],
        'header'      => $header   ?? null,
        'text'        => $text     ?? null,
        'splitAction' => $splitAction ?? null,
        'buttonText'  => $buttonText  ?? 'Dropdown',
        'buttonIcon'  => $buttonIcon  ?? null,
        'menuClass'   => $menuClass   ?? '',
    ]);

    $styles = new DropdownStyles($dropdown);
@endphp

<!-- Root dropdown wrapper with Alpine.js reactive toggle state -->
<div
    id="{{ $dropdown->id() }}"
    x-data="{
        cfg: {{ $dropdown->toJson() }},
        open: false,

        // Toggle dropdown visibility and dispatch custom event
        toggle() {
            this.open = !this.open;
            this.$dispatch('jkc-dropdown-toggle', {
                id: this.cfg.id,
                isOpen: this.open
            });
        }
    }"
    @if ($dropdown->closesOutside()) @click.outside="open = false" @endif
    class="{{ $styles->container() }}"
>

    <!-- Single button or link trigger -->
    @if ($dropdown->isTrigger('button', 'link'))
        <button
            x-on:click="toggle()"
            type="button"
            class="{{ $styles->button() }}"
            aria-expanded="false"
            :aria-expanded="open.toString()"
        >
            {{ $dropdown->buttonText() }}
            {!! $dropdown->buttonIcon() !!}
        </button>
    @endif

    <!-- Split button trigger (primary link + separate dropdown toggle) -->
    @if ($dropdown->isTrigger('split'))
        <a
            href="{{ $dropdown->splitAction() }}"
            class="{{ $styles->splitPrimaryButton() }}"
        >
            {{ $dropdown->buttonText() }}
        </a>

        <button
            x-on:click="toggle()"
            type="button"
            class="{{ $styles->splitToggleButton() }}"
            aria-expanded="false"
            :aria-expanded="open.toString()"
        >
            <span class="sr-only">Toggle dropdown</span>
            {!! $dropdown->buttonIcon() !!}
        </button>
    @endif

    <!-- Floating dropdown menu panel -->
    <div
        x-show="open"
        x-transition:enter="transition ease-out duration-150"
        x-transition:enter-start="opacity-0 translate-y-1"
        x-transition:enter-end="opacity-100 translate-y-0"
        x-transition:leave="transition ease-in duration-100"
        x-transition:leave-start="opacity-100 translate-y-0"
        x-transition:leave-end="opacity-0 translate-y-1"
        @if ($dropdown->closesInside()) x-on:click="open = false" @endif
        class="{{ $styles->menu() }}"
        style="display: none;"
        :style="open ? 'display: block;' : 'display: none;'"
    >

        <!-- Optional top menu header -->
        @if ($dropdown->header() !== null)
            <div class="{{ $styles->menuHeader() }}">
                {{ $dropdown->header() }}
            </div>
        @endif

        <!-- Optional top static description text -->
        @if ($dropdown->text() !== null)
            <div class="{{ $styles->menuText() }}">
                {{ $dropdown->text() }}
            </div>
        @endif

        <!-- Dropdown items list -->
        <div class="py-1 z-5">
            @foreach ($dropdown->items() as $index => $item)

                <!-- Horizontal divider item -->
                @if ($item['type'] === 'divider')
                    <div class="{{ $styles->itemDivider() }}"></div>

                <!-- Section header item -->
                @elseif ($item['type'] === 'header')
                    <div class="{{ $styles->itemHeader() }}">
                        {{ $item['text'] }}
                    </div>

                <!-- Non-interactive text item -->
                @elseif ($item['type'] === 'text')
                    <span class="{{ $styles->itemText() }}">
                        {{ $item['text'] }}
                    </span>

                <!-- Custom embedded form/HTML item -->
                @elseif ($item['type'] === 'form')
                    <div class="{{ $styles->itemForm() }}">
                        {!! $item['content'] !!}
                    </div>

                <!-- Standard interactive link item -->
                @else
                    <a
                        href="{{ $item['href'] }}"
                        class="{{ $styles->itemLink($index) }}"
                        @if ($item['disabled']) tabindex="-1" aria-disabled="true" @endif
                    >
                        @if ($item['icon'] !== null)
                            <span class="inline-flex items-center gap-2">
                                {!! $item['icon'] !!}
                                {{ $item['text'] }}
                            </span>
                        @else
                            {{ $item['text'] }}
                        @endif
                    </a>
                @endif

            @endforeach
        </div>

    </div>

</div>