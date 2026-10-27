@php
/**
 * Advanced File Input Component with Initial Data Preview & SRP Architecture — Juksgraphic / Penguin UI
 *
 * @param string      $label       Field label. Default: ''.
 * @param string      $name        HTML name attribute. Default: 'file'.
 * @param bool        $required    Whether the file upload is required. Default: false.
 * @param string      $accept      Accepted file types (e.g., 'image/*,video/*,.pdf,.mp3'). Default: ''.
 * @param string      $recommended Helper text for recommended formats/sizes. Default: ''.
 * @param string      $model       Parent Alpine.js object path. Default: 'formData'.
 * @param array       $initialData Array containing initial values. Default: [].
 * @param string      $class       Extra CSS classes. Default: ''.
 */

/** @var string $label */
$label = (string) ($label ?? '');

/** @var string $name */
$name = preg_replace('/[^A-Za-z0-9_-]/', '', (string) ($name ?? 'file'));

/** @var bool $required */
$required = (bool) ($required ?? false);

/** @var string $accept */
$accept = (string) ($accept ?? '');

/** @var string $recommended */
$recommended = (string) ($recommended ?? '');

/** @var string $model */
$model = (string) ($model ?? 'formData');

/** @var array<string, mixed> $initialData */
$initialData = is_array($initialData ?? null) ? $initialData : [];

$initialFileUrl = (string) ($initialData[$name] ?? '');

/** @var string $class */
$class = (string) ($class ?? '');

/** @var string $fileClasses */
$fileClasses = preg_replace('/\s+/', ' ', trim(
    'w-full rounded-radius border border-outline bg-surface-alt px-3 py-2 text-sm text-on-surface cursor-pointer dark:border-outline-dark dark:bg-surface-dark-alt/50 dark:text-on-surface-dark file:mr-4 file:py-1 file:px-4 file:rounded-radius file:border-0 file:text-xs file:font-semibold file:bg-primary file:text-on-primary hover:file:opacity-90 dark:file:bg-primary-dark dark:file:text-on-primary-dark ' . $class
));
@endphp

<div class="flex flex-col gap-2 col-span-full text-on-surface dark:text-on-surface-dark"
     x-data="{
        fileName: '',
        fileSize: '',
        fileType: 'image',
        previewUrl: '{{ $initialFileUrl }}',
        
        init() {
            if (this.previewUrl) {
                this.fileName = this.previewUrl.split('/').pop().split('?')[0] || 'Fichier distant';
            }
        },

        handleFileChange(event) {
            const file = event.target.files[0];
            if (!file) {
                this.resetFile();
                return;
            }
            this.fileName = file.name;
            this.fileSize = this.formatBytes(file.size);
            this.fileType = this.resolveFileType(file);
            this.previewUrl = this.fileType === 'image' ? URL.createObjectURL(file) : null;
        },

        
        formatBytes(bytes) {
            if (bytes === 0) return '0 Bytes';
            const k = 1024;
            const sizes = ['Bytes', 'KB', 'MB', 'GB'];
            const i = Math.floor(Math.log(bytes) / Math.log(k));
            return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
        },

        
        resolveFileType(file) {
            const type = file.type;
            if (type.startsWith('image/')) return 'image';
            if (type.startsWith('video/')) return 'video';
            if (type.startsWith('audio/')) return 'audio';
            if (type.includes('pdf') || type.includes('document') || type.includes('text') || type.includes('sheet')) return 'document';
            return 'file';
        },

       
        resetFile() {
            this.fileName = '';
            this.fileSize = '';
            this.fileType = 'file';
            this.previewUrl = null;
            if (this.$refs.fileInput) {
                this.$refs.fileInput.value = '';
            }
        }
     }"
     x-init="init()"
>
    @if ($label !== '')
        <label for="{{ $name }}" class="w-fit text-sm font-semibold text-on-surface-strong dark:text-on-surface-dark-strong">
            {{ $label }}
            @if ($required)<span class="text-danger dark:text-danger-dark">*</span>@endif
        </label>
    @endif

    {{-- Zone d'affichage du fichier sélectionné ou initialisé (Aperçu / Carte d'info) --}}
    <template x-if="fileName || previewUrl">
        <div class="relative flex items-center gap-3.5 p-3 rounded-radius border border-outline bg-surface-alt dark:border-outline-dark dark:bg-surface-dark-alt/50 shadow-sm">
            
            {{-- Aperçu Image ou Icône SVG dynamique selon le type --}}
            <template x-if="previewUrl && fileType === 'image'">
                <img :src="previewUrl" alt="Aperçu" class="h-14 w-14 object-cover rounded-md border border-outline dark:border-outline-dark shrink-0">
            </template>

            <template x-if="!previewUrl || fileType !== 'image'">
                <div class="flex h-14 w-14 items-center justify-center rounded-md border border-outline bg-surface dark:border-outline-dark dark:bg-surface-dark shrink-0 text-primary dark:text-primary-dark">
                    {{-- SVG Vidéo --}}
                    <svg x-show="fileType === 'video'" class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m15 10 4.553-2.276A1 1 0 0 1 21 8.618v6.764a1 1 0 0 1-1.447.894L15 14M5 18h8a2 2 0 0 0 2-2V8a2 2 0 0 0-2-2H5a2 2 0 0 0-2 2v8a2 2 0 0 0 2 2Z" />
                    </svg>
                    {{-- SVG Audio (MP3) --}}
                    <svg x-show="fileType === 'audio'" class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m9 9 10-3v3m-10 4 10-3v3M5 16v-4a2 2 0 0 1 2-2h1.586a1 1 0 0 0 .707-.293l2.414-2.414a1 1 0 0 1 1.707.707V18a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2Z" />
                    </svg>
                    {{-- SVG Document / Doc --}}
                    <svg x-show="fileType === 'document'" class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m2.25 0H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z" />
                    </svg>
                    {{-- SVG Fichier Générique --}}
                    <svg x-show="fileType === 'file'" class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m6.75 12H9m1.5-3H9m4.5-3H9m6-7.5v3.75a.375.375 0 0 1-.375.375H12" />
                    </svg>
                </div>
            </template>

            {{-- Nom et taille/infos du fichier --}}
            <div class="flex flex-col flex-1 truncate">
                <span class="text-xs font-semibold text-on-surface-strong dark:text-on-surface-dark-strong truncate" x-text="fileName"></span>
                <span class="text-xs text-on-surface/70 dark:text-on-surface-dark/70" x-text="fileSize || 'Fichier chargé'"></span>
            </div>

            {{-- Actions : Remplacer ou Supprimer --}}
            <div class="flex items-center gap-2 shrink-0">
                <button type="button" x-on:click="$refs.fileInput.click()" class="px-3 py-1.5 text-xs font-medium rounded-radius bg-primary text-on-primary hover:opacity-90 dark:bg-primary-dark dark:text-on-primary-dark transition-opacity">
                    Changer
                </button>
                <button type="button" x-on:click="resetFile()" class="p-1.5 rounded-radius text-danger hover:bg-danger/10 transition-colors" title="Supprimer">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </template>

    {{-- Input file natif (masqué si un fichier est sélectionné ou initialisé) --}}
    <input 
        x-ref="fileInput"
        id="{{ $name }}" 
        name="{{ $name }}" 
        type="file" 
        @if($accept !== '') accept="{{ $accept }}" @endif
        x-on:change="handleFileChange($event)"
        class="{{ $fileClasses }}"
        :class="(fileName || previewUrl) ? 'hidden' : ''"
        {{ $required ? 'required' : '' }}
    >

    {{-- Texte d'aide et formats recommandés --}}
    @if ($recommended !== '')
        <span class="text-xs text-on-surface/60 dark:text-on-surface-dark/60 pl-0.5">{{ $recommended }}</span>
    @endif
</div>