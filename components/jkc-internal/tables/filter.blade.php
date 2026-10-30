{{-- Internal partial of jkc.tables.dynamic-table. Not a public component: include the facade instead. --}}
{{-- One filter dropdown, rendered once per filter (`filter` is the x-for variable of the toolbar). --}}
@include('jkc-internal.tables.select', [
    'jkcSelectData' =>  'jkcTableFilter(filter)',
    'jkcSelectStyle' => 'filter',
])