@php
    $jkcRow = $jkcTable->row();
@endphp

@if ($jkcRow->hasAnyAction())
    <td class="px-6 py-3 text-right relative z-10" @click.stop>
        <div class="flex justify-end gap-2">
            @if ($jkcRow->hasAction('view'))
                <{{ $jkcRow->actionTag() }} {!! $jkcRow->viewAttrs() !!}
                    class="inline-flex items-center justify-center bg-transparent rounded-radius border border-outline dark:border-outline-dark p-1.5 text-on-surface/60 dark:text-on-surface-dark/60 transition hover:opacity-75"
                    aria-label="View">
                    @include('jkc-internal.tables.icon', [
                        'jkcIcon' => 'eye',
                        'jkcIconClass' => 'size-4',
                        'jkcIconStroke' => '',
                        'jkcIconAttrs' => '',
                    ])
                </{{ $jkcRow->actionTag() }}>
            @endif

            @if ($jkcRow->hasAction('edit'))
                <{{ $jkcRow->actionTag() }} {!! $jkcRow->editAttrs() !!}
                    class="inline-flex items-center justify-center bg-transparent rounded-radius border border-outline dark:border-outline-dark p-1.5 text-on-surface/60 dark:text-on-surface-dark/60 transition hover:opacity-75"
                    aria-label="Edit">
                    @include('jkc-internal.tables.icon', [
                        'jkcIcon' => 'pencil',
                        'jkcIconClass' => 'size-4',
                        'jkcIconStroke' => '',
                        'jkcIconAttrs' => '',
                    ])
                </{{ $jkcRow->actionTag() }}>
            @endif

            @if ($jkcRow->hasAction('delete'))
                <button type="button" x-on:click="triggerDelete(item)"
                    class="inline-flex items-center justify-center bg-transparent rounded-radius border border-outline dark:border-outline-dark p-1.5 text-on-surface/60 dark:text-on-surface-dark/60 transition hover:opacity-75"
                    aria-label="Delete">
                    @include('jkc-internal.tables.icon', [
                        'jkcIcon' => 'trash',
                        'jkcIconClass' => 'size-4',
                        'jkcIconStroke' => '',
                        'jkcIconAttrs' => '',
                    ])
                </button>
            @endif
        </div>
    </td>
@endif