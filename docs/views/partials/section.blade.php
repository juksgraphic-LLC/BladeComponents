<section id="{{ trim(preg_replace('/[^a-z0-9]+/', '-', strtolower($title ?? '')), '-') }}"
    class="scroll-mt-6 rounded-radius border border-outline bg-surface dark:border-outline-dark dark:bg-surface-dark {{ $wide ?? false ? 'xl:col-span-2' : '' }}">
    <div class="border-b border-outline px-6 py-4 dark:border-outline-dark">
        <h2 class="font-title text-base font-semibold text-on-surface dark:text-on-surface-dark">{{ $title }}</h2>
        @if (!empty($description))
            <p class="mt-0.5 font-paragraph text-sm text-on-surface/60 dark:text-on-surface-dark/60">{!! $description !!}</p>
        @endif
    </div>
    <div class="px-6 py-4">{!! $slot !!}</div>
</section>