{{-- ROW CHECKBOX --}}
@php
    $jkcCheckAttrs = ':class="selectedIds.includes(item.id) ? \'opacity-100\' : \'opacity-0\'"';
@endphp

@if($jkcTable->selectable())
    <td class="px-4 py-3 text-center relative z-10" @click.stop>
        <div x-on:click="toggleSelectRow(item.id)"
            :class="selectedIds.includes(item.id) ?
                'border-primary bg-primary dark:border-primary-dark dark:bg-primary-dark' :
                'border-outline bg-surface-alt dark:border-outline-dark dark:bg-surface-dark-alt'"
            class="flex h-5 w-5 mx-auto shrink-0 items-center justify-center rounded border
               transition-all duration-150 cursor-pointer
                roup hover:border-primary dark:hover:border-primary-dark">
            @include('jkc-internal.tables.icon', [
                'jkcIcon' => 'check',
                'jkcIconClass' => 'h-3.5 w-3.5 text-white transition-opacity duration-150',
                'jkcIconStroke' => '',
                'jkcIconAttrs' => $jkcCheckAttrs,
            ])
        </div>
    </td>
@endif
