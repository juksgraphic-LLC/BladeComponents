{{-- Search field. Expects: $config, $data, $style. --}}
@if ($config->hasSearch())
    <div class="{{ $style['search'] }}">
        <i class="bi bi-search pointer-events-none absolute left-3 top-1/2 -translate-y-1/2 text-sm opacity-60" aria-hidden="true"></i>
        <input
            type="search"
            x-bind:value="search"
            x-on:input="setSearch($event.target.value)"
            placeholder="{{ $data['search']['placeholder'] }}"
            aria-label="{{ $data['search']['placeholder'] }}"
            autocomplete="off"
            class="w-full pl-9 pr-3 {{ $style['field'] }} {{ $style['control'] }}"
        />
    </div>
@endif