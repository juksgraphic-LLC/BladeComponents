<div class="overflow-x-auto rounded-radius border border-outline dark:border-outline-dark">
    <table class="w-full text-left text-sm">
        <thead
            class="bg-surface-alt text-xs uppercase tracking-wide text-on-surface/60 dark:bg-surface-dark-alt dark:text-on-surface-dark/60">
            <tr>
                <th class="px-4 py-3 font-semibold">Prop</th>
                <th class="px-4 py-3 font-semibold">Type</th>
                <th class="px-4 py-3 font-semibold">Default</th>
                <th class="px-4 py-3 font-semibold">Description</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-outline dark:divide-outline-dark">
            @foreach ($rows as $row)
                <tr class="align-top">
                    <td class="px-4 py-3"><code
                            class="font-code text-xs text-primary dark:text-primary-dark">{{ $row[0] }}</code>
                    </td>
                    <td class="px-4 py-3 font-code text-xs text-on-surface/70 dark:text-on-surface-dark/70">
                        {{ $row[1] }}</td>
                    <td class="px-4 py-3 font-code text-xs text-on-surface/70 dark:text-on-surface-dark/70">
                        {{ $row[2] }}</td>
                    <td class="px-4 py-3 text-on-surface/80 dark:text-on-surface-dark/80">{!! $row[3] !!}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
