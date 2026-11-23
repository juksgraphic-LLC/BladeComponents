{{-- Reset button, shown only when a criterion is active. Expects: $style, $texts. --}}
<button
    type="button"
    x-on:click="resetFilters()"
    x-show="hasActiveFilters"
    class="{{ $style['reset'] }}"
    style="display: none;"
>
    <i class="bi bi-arrow-counterclockwise" aria-hidden="true"></i>
    <span>{{ $texts['reset'] }}</span>
</button>