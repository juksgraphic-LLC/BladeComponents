{{--
    Dropdown for one `select` / `multiselect` filter.
    Expects: $filter (one item of FilterBarConfig::toArray()['filters']).
    Expects: $style (FilterBarStyles slots). Optional: $texts (UI strings).
    Must be rendered inside the filterBar x-data scope.
--}}
@php
    $texts     = $texts ?? [];
    $labelled  = $style['label'] !== null;
    $fieldName = $filter['label'] ?? $filter['key'];
    $selectPayload = [
        'key'         => $filter['key'],
        'label'       => $fieldName,
        'placeholder' => $labelled ? ($texts['all'] ?? 'Tous') : $fieldName,
        'summary'     => $labelled ? ($texts['selected'] ?? 'Sélection') : $fieldName,
        'multiple' => ($filter['type'] ?? 'select') === 'multiselect',
        'options'  => $filter['options'] ?? [],
        'texts'    => $texts,
    ];
@endphp

<div
    x-data="filterSelect({{ json_encode($selectPayload) }})"
    x-effect="sync(filterValues[key])"
    x-on:keydown.escape="open && closeList(true)"
    x-on:keydown.arrow-down.prevent="moveActive(1)"
    x-on:keydown.arrow-up.prevent="moveActive(-1)"
    x-on:keydown.home="open && ($event.preventDefault(), jumpActive('Home'))"
    x-on:keydown.end="open && ($event.preventDefault(), jumpActive('End'))"
    x-on:click.outside="open && closeList()"
    class="relative w-full min-w-40"
>
    {{-- Trigger --}}
    <button
        type="button"
        x-ref="trigger"
        x-on:click="toggleList()"
        x-on:keydown.enter.prevent="onEnter()"
        x-bind:aria-expanded="open"
        x-bind:aria-controls="uid + '-list'"
        x-bind:aria-activedescendant="showSearch ? null : activeId()"
        x-bind:aria-label="label"
        aria-haspopup="listbox"
        class="inline-flex w-full items-center justify-between gap-2 px-3 text-left {{ $style['field'] }} {{ $style['control'] }}"
    >
        <span
            class="truncate"
            x-text="triggerLabel"
            x-bind:class="hasValue ? 'font-medium' : 'opacity-70'"
        ></span>
        <span class="flex shrink-0 items-center gap-1.5">
            <span
                x-show="hasValue"
                x-on:click.stop="clear()"
                role="button"
                x-bind:aria-label="texts.clear"
                x-bind:title="texts.clear"
                class="inline-flex size-5 items-center justify-center rounded-full hover:bg-on-surface/10 dark:hover:bg-on-surface-dark/10"
                style="display: none;"
            >
                <i class="bi bi-x-lg text-xs" aria-hidden="true"></i>
            </span>
            <i
                class="bi bi-chevron-down text-xs transition-transform duration-150"
                x-bind:class="open && 'rotate-180'"
                aria-hidden="true"
            ></i>
        </span>
    </button>

    {{-- Panel --}}
    <div
        x-show="open"
        x-transition.opacity.duration.100ms
        style="display: none;"
        class="absolute left-0 z-20 mt-1 w-full min-w-48 overflow-hidden rounded-radius border shadow-md {{ $style['popover'] }}"
    >
        <div x-show="showSearch" class="border-b border-outline p-2 dark:border-outline-dark" style="display: none;">
            <input
                type="text"
                x-ref="search"
                x-model="query"
                x-on:input="onQueryInput()"
                x-on:keydown.enter.prevent="onEnter()"
                x-bind:placeholder="texts.search"
                x-bind:aria-label="texts.search"
                x-bind:aria-controls="uid + '-list'"
                x-bind:aria-activedescendant="activeId()"
                autocomplete="off"
                class="w-full px-2.5 {{ $style['field'] }} {{ $style['control'] }}"
            />
        </div>

        <ul
            x-bind:id="uid + '-list'"
            role="listbox"
            x-bind:aria-multiselectable="multiple"
            x-bind:aria-label="label"
            class="max-h-60 overflow-y-auto py-1"
        >
            <template x-for="(option, index) in visibleOptions" x-bind:key="option.value">
                <li
                    role="option"
                    x-bind:id="optionId(index)"
                    x-bind:aria-selected="isPicked(option)"
                    x-on:click="pick(option)"
                    x-on:mousemove="activeIndex = index"
                    class="flex cursor-pointer items-center gap-2 px-3 py-2 text-sm text-on-surface dark:text-on-surface-dark"
                    x-bind:class="[
                        activeIndex === index && 'bg-on-surface/10 dark:bg-on-surface-dark/10',
                        isPicked(option) && 'font-medium',
                    ]"
                >
                    {{-- Multi: checkbox marker. Single: check on the right. --}}
                    <span
                        x-show="multiple"
                        class="inline-flex size-4 shrink-0 items-center justify-center rounded-sm border border-outline dark:border-outline-dark"
                        x-bind:class="isPicked(option) && 'border-primary bg-primary text-on-primary dark:border-primary-dark dark:bg-primary-dark dark:text-on-primary-dark'"
                        style="display: none;"
                    >
                        <i class="bi bi-check2 text-xs" x-show="isPicked(option)" aria-hidden="true"></i>
                    </span>
                    <span class="flex-1 truncate" x-text="option.label"></span>
                    <i class="bi bi-check2 text-sm" x-show="!multiple && isPicked(option)" aria-hidden="true" style="display: none;"></i>
                </li>
            </template>

            <li x-show="visibleOptions.length === 0" class="px-3 py-2 text-sm opacity-70" style="display: none;" x-text="texts.empty"></li>
        </ul>
    </div>
</div>