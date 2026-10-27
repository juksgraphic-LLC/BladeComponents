@php
    $val = trim($code ?? '');
    $lbl = $label ?? 'blade';
    $lng = preg_replace('/[^a-z0-9-]/i', '', $lang ?? 'blade');
@endphp
<div x-data="{
    html: '',
    src: '',
    copied: false,
    async init() {
        this.src = this.$el.querySelector('[data-src]').textContent;
        this.html = await window.highlight(this.src, '{{ $lng }}');
    },
    async copy() {
        await navigator.clipboard.writeText(this.src);
        this.copied = true;
        setTimeout(() => (this.copied = false), 1800);
    }
}" class="bg-neutral-950">
    <div class="flex items-center justify-between border-b border-white/10 px-4 py-2 text-xs text-neutral-400">
        <span class="font-code">{{ $lbl }}</span>

        <button type="button" @click="copy()"
            class="inline-flex items-center gap-1.5 rounded-radius px-2.5 py-1 font-medium text-neutral-400 transition hover:bg-white/10 hover:text-white focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary dark:focus-visible:outline-primary-dark">
            <svg x-show="!copied" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                stroke="currentColor" stroke-width="2" class="size-4" aria-hidden="true">
                <rect x="9" y="9" width="13" height="13" rx="2" />
                <path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1" />
            </svg>
            <svg x-show="copied" x-cloak xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                stroke="currentColor" stroke-width="2.5" class="size-4 text-success" aria-hidden="true">
                <path d="M20 6 9 17l-5-5" />
            </svg>
            <span x-text="copied ? 'Copied!' : 'Copy'"></span>
        </button>
    </div>

    <pre x-show="!html" class="overflow-x-auto p-4 text-[13px] leading-relaxed text-neutral-200"><code data-src class="font-code">{{ $val }}</code></pre>
    <div x-show="html" x-cloak x-html="html"></div>
</div>