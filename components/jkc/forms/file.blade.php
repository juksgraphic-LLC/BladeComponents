@php

    use Juksgraphic\BladeComponents\Components\Forms\FileInput;
    use Juksgraphic\BladeComponents\Components\Forms\FileInputStyles;

    $fileInput = new FileInput([
        'label'       => $label ?? '',
        'name'        => $name ?? 'file',
        'required'    => $required ?? false,
        'accept'      => $accept ?? '',
        'recommended' => $recommended ?? '',
        'model'       => $model ?? 'formData',
        'initialData' => $initialData ?? [],
        'class'       => $class ?? '',
    ]);
    
    $styles = new FileInputStyles($fileInput);
@endphp

<!-- Root file input container with Alpine.js preview state -->
<div
    class="{{ $styles->wrapper() }}"
    x-data="{
        fileName: '',
        fileSize: '',
        fileType: 'image',
        previewUrl: {{ $fileInput->encodeJson($fileInput->initialFileUrl()) }},

        
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
            if (type.includes('pdf') ||
            type.includes('document') ||
            type.includes('text') ||
            type.includes('sheet')) return 'document';
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

    <!-- Optional field label -->
    @if ($fileInput->hasLabel())
        <label
            for="{{ $fileInput->name() }}"
            class="{{ $styles->label() }}"
        >
            {{ $fileInput->label() }}
            @if ($fileInput->isRequired())
                <span class="{{ $styles->requiredMarker() }}">*</span>
            @endif
        </label>
    @endif

    <!-- Selected or initial file preview card -->
    <template x-if="fileName || previewUrl">
        <div class="{{ $styles->previewCard() }}">

            <!-- Image thumbnail preview -->
            <template x-if="previewUrl && fileType === 'image'">
                <img
                    :src="previewUrl"
                    alt="Aperçu"
                    class="{{ $styles->imagePreview() }}"
                >
            </template>

            <!-- Type-specific SVG icon fallback for non-image files -->
            <template x-if="!previewUrl || fileType !== 'image'">
                <div class="{{ $styles->iconBox() }}">
                    <svg x-show="fileType === 'video'" class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m15 10 4.553-2.276A1 1 0 0 1 21 8.618v6.764a1 1 0 0 1-1.447.894L15 14M5 18h8a2 2 0 0 0 2-2V8a2 2 0 0 0-2-2H5a2 2 0 0 0-2 2v8a2 2 0 0 0 2 2Z" />
                    </svg>

                    <svg x-show="fileType === 'audio'" class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m9 9 10-3v3m-10 4 10-3v3M5 16v-4a2 2 0 0 1 2-2h1.586a1 1 0 0 0 .707-.293l2.414-2.414a1 1 0 0 1 1.707.707V18a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2Z" />
                    </svg>

                    <svg x-show="fileType === 'document'" class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m2.25 0H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z" />
                    </svg>

                    <svg x-show="fileType === 'file'" class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m6.75 12H9m1.5-3H9m4.5-3H9m6-7.5v3.75a.375.375 0 0 1-.375.375H12" />
                    </svg>
                </div>
            </template>

            <!-- File name and formatted size -->
            <div class="flex flex-col flex-1 truncate">
                <span class="{{ $styles->fileName() }}" x-text="fileName"></span>
                <span class="{{ $styles->fileMeta() }}" x-text="fileSize || 'Fichier chargé'"></span>
            </div>

            <!-- Replace and clear actions -->
            <div class="flex items-center gap-2 shrink-0">
                <button
                    type="button"
                    x-on:click="$refs.fileInput.click()"
                    class="{{ $styles->changeButton() }}"
                >
                    Changer
                </button>

                <button
                    type="button"
                    x-on:click="resetFile()"
                    class="{{ $styles->removeButton() }}"
                    title="Supprimer"
                >
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

        </div>
    </template>

    <!-- Native file input hidden while a file preview card is active -->
    <input
        x-ref="fileInput"
        id="{{ $fileInput->name() }}"
        name="{{ $fileInput->name() }}"
        type="file"
        @if ($fileInput->accept() !== '') accept="{{ $fileInput->accept() }}" @endif
        x-on:change="handleFileChange($event)"
        class="{{ $styles->control() }}"
        :class="(fileName || previewUrl) ? 'hidden' : ''"
        {{ $fileInput->isRequired() ? 'required' : '' }}
    >

    <!-- Optional format/size helper text -->
    @if ($fileInput->recommended() !== '')
        <span class="{{ $styles->recommendedText() }}">{{ $fileInput->recommended() }}</span>
    @endif

</div>