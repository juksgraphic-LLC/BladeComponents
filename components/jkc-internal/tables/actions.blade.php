{{-- Internal partial of jkc.tables.dynamic-table. Not a public component: include the facade instead. --}}
@php
    /** With a baseRoute, view and edit are real links (htmx, new tab, keyboard); without one they stay buttons that only emit the event. */
    $jkcLinks = $jkcTable->getbaseRoute() !== '';
    $jkcTag = $jkcLinks ? 'a' : 'button';

    $jkcViewAttrs = $jkcLinks
        ? ':href="rowUrl(item)" @click="follow($event, \'view\', item)"'
        : 'type="button" x-on:click="triggerView(item)"';
    
    $jkcEditAttrs = $jkcLinks
        ? ':href="editUrl(item)" @click="follow($event, \'edit\', item)"'
        : 'type="button" x-on:click="triggerEdit(item)"';
@endphp

{{-- Standard row actions (view / edit as real links when a baseRoute is given) --}}
<template x-if="withAction.length > 0">
    <td class="px-6 py-3 text-right relative z-10" @click.stop>
        <div class="flex justify-end gap-2">
            <template x-if="hasAction('view')">
                <{{ $jkcTag }} {!! $jkcViewAttrs !!}
                    class="inline-flex items-center justify-center bg-transparent rounded-radius border border-outline dark:border-outline-dark p-1.5 text-on-surface/60 dark:text-on-surface-dark/60 transition hover:opacity-75"
                    aria-label="View">
                    <svg xmlns="http://www.w3.org/2000/svg" class="size-4" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                    </svg>
                </{{ $jkcTag }}>
            </template>

            <template x-if="hasAction('edit')">
                <{{ $jkcTag }} {!! $jkcEditAttrs !!}
                    class="inline-flex items-center justify-center bg-transparent rounded-radius border border-outline dark:border-outline-dark p-1.5 text-on-surface/60 dark:text-on-surface-dark/60 transition hover:opacity-75"
                    aria-label="Edit">
                    <svg xmlns="http://www.w3.org/2000/svg" class="size-4" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                    </svg>
                </{{ $jkcTag }}>
            </template>

            <template x-if="hasAction('delete')">
                <button type="button" x-on:click="triggerDelete(item)"
                    class="inline-flex items-center justify-center bg-transparent rounded-radius border border-outline dark:border-outline-dark p-1.5 text-on-surface/60 dark:text-on-surface-dark/60 transition hover:opacity-75"
                    aria-label="Delete">
                    <svg xmlns="http://www.w3.org/2000/svg" class="size-4" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                    </svg>
                </button>
            </template>
        </div>
    </td>
</template>