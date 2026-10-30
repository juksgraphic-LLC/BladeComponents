{{-- Internal partial of jkc.tables.dynamic-table. Not a public component: include the facade instead. --}}
<tr x-on:click="triggerView(item)"
    @keydown.enter.self="canNavigate() || triggerView(item)"
    :tabindex="hasAction('view') && !canNavigate() ? 0 : null"
    :class="[
        hasAction('view') ? 'cursor-pointer' : '',
        selectedIds.includes(item.id) ? 'bg-primary/10 dark:bg-primary/20' :
        'hover:bg-primary/5 dark:hover:bg-primary/10'
    ]"
    class="relative transition-colors group whitespace-nowrap">

    @include("jkc-internal.tables.checkbox")
  
    @foreach ($jkcTable->columns() as $jkcIndex => $jkcColumn)
        <td class="px-6 py-3 text-sm text-on-surface dark:text-on-surface-dark" x-data="{ column: {{ $jkcColumn->ref() }} }">
            @if ($jkcIndex === 0 && $jkcTable->getBaseRoute() !== '')
                <template x-if="hasAction('view')">
                    <a :href="rowUrl(item)"
                     @click.stop="follow($event, 'view', item)"
                     tabindex="-1"
                     aria-hidden="true"
                     class="absolute inset-0"></a>
                </template>
            @endif
            @include($jkcColumn->view(), ['jkcColumn' => $jkcColumn])
        </td>
    @endforeach

    @include('jkc-internal.tables.actions')
</tr>