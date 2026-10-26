@php
    /**
     * Notifications Container component — Juksgraphic / Penguin UI
     *
     * Global stack manager for dynamic toast notifications built with Tailwind CSS and Alpine.js.
     * Works with Laravel Blade and BladeOne.
     *
     * External Triggers (Commands):
     * - Push notification: window.dispatchEvent(new CustomEvent('jkc-notify', { detail: { variant: 'success', title: 'Saved', message: 'Changes applied.' } }))
     * - Show toast:        window.dispatchEvent(new CustomEvent('jkc-toast-show', { detail: { type: 'info', message: 'Title', description: 'Description text' } }))
     * - Set position:      window.dispatchEvent(new CustomEvent('jkc-toast-position', { detail: { position: 'bottom-right' } }))
     *
     * Emitted Lifecycle Events (Notifications):
     * - On Notify:     jkc-on-notify     (dispatched with detail: { notification })
     * - On Toast Show: jkc-on-toast-show (dispatched with detail: { notification })
     */
@endphp

<div id="global-notifications" class="pointer-events-none fixed inset-0 z-[9999] overflow-hidden">
    <div x-data="{
        notifications: [],
        max: 20,
        displayDuration: 8000,
        leaveDuration: 400,
        soundEffect: false,
        soundUrl: 'https://res.cloudinary.com/ds8pgw1pf/video/upload/v1728571480/penguinui/component-assets/sounds/ding.mp3',
    
        push(detail, type = 'notify') {
        
            let data = detail && detail.notify ? detail.notify : detail;
    
            if (typeof data === 'string') {
                data = { message: data, variant: 'success', title: 'Notification' };
            }
    
            if (this.notifications.length >= this.max) {
                this.notifications.splice(0, this.notifications.length - (this.max - 1));
            }
    
            const newNotification = {
                id: Date.now() + '-' + Math.random().toString(36).slice(2, 7),
                variant: (data && data.variant) || 'info',
                sender:  (data && data.sender)  || null,
                title:   (data && data.title)   || null,
                message: (data && data.message) || ''
            };
    
            this.notifications.push(newNotification);
    
            if (type === 'toast-show') {
                this.$dispatch('jkc-on-toast-show', { notification: newNotification });
            } else {
                this.$dispatch('jkc-on-notify', { notification: newNotification });
            }
    
            if (this.soundEffect) new Audio(this.soundUrl).play().catch(() => {});
        },
    
        remove(id) {
            this.notifications = this.notifications.filter(n => n.id !== id);
        }
    }" @jkc-notify.window="push($event.detail, 'notify')"
        @jkc-toast-show.window="push({
        variant: $event.detail.type || 'info',
        title: $event.detail.message,
        message: $event.detail.description || ''
    }, 'toast-show')"
        class="relative size-full">

        {{-- Position Container --}}
        <div x-data="{ position: 'top-right' }" @jkc-toast-position.window="position = $event.detail.position || position"
            class="pointer-events-none fixed z-[9999] flex w-full max-w-[280px] md:max-w-[400px] flex-col gap-2 md:gap-3 px-4 py-4 md:px-6 md:py-6 transition-all duration-300"
            :class="{
                'top-0 left-0 items-start': position === 'top-left',
                'top-0 left-1/2 -translate-x-1/2 items-center': position === 'top-center',
                'top-0 right-0 items-end': position === 'top-right',
                'bottom-0 left-0 items-start': position === 'bottom-left',
                'bottom-0 left-1/2 -translate-x-1/2 items-center': position === 'bottom-center',
                'bottom-0 right-0 items-end': position === 'bottom-right'
            }">

            <template x-for="notification in notifications" :key="notification.id">
                <div x-data="{
                    visible: false,
                    timeout: null,
                
                    start() {
                        clearTimeout(this.timeout);
                        this.timeout = setTimeout(() => this.close(), this.displayDuration);
                    },
                
                    pause() {
                        clearTimeout(this.timeout);
                    },
                
                    resume() {
                        this.start();
                    },
                
                    close() {
                        clearTimeout(this.timeout);
                        this.visible = false;
                        setTimeout(() => this.remove(notification.id), this.leaveDuration);
                    }
                }" x-init="$nextTick(() => {
                    visible = true;
                    start();
                })" @mouseenter="pause()" @mouseleave="resume()"
                    class="w-full">

                    {{-- Info / Default / Primary --}}
                    <template
                        x-if="notification.variant === 'info' || notification.variant === 'default' || notification.variant === 'primary'">
                        <div x-show="visible" x-cloak role="status" aria-live="polite" aria-atomic="true"
                            class="pointer-events-auto relative w-full overflow-hidden rounded-radius border border-info/50 bg-surface shadow-xl dark:bg-surface-dark"
                            x-transition:enter="transition duration-300 ease-out"
                            x-transition:enter-start="translate-x-full opacity-0"
                            x-transition:enter-end="translate-x-0 opacity-100"
                            x-transition:leave="transition duration-300 ease-in"
                            x-transition:leave-start="opacity-100 translate-x-0"
                            x-transition:leave-end="opacity-0 translate-x-12">
                            <div class="flex items-center gap-2.5 p-3 md:p-4 bg-info/5">
                                <div class="rounded-full bg-info/15 p-0.5 text-info shrink-0" aria-hidden="true">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"
                                        class="size-5">
                                        <path fill-rule="evenodd"
                                            d="M18 10a8 8 0 1 1-16 0 8 8 0 0 1 16 0Zm-7-4a1 1 0 1 1-2 0 1 1 0 0 1 2 0ZM9 9a.75.75 0 0 0 0 1.5h.253a.25.25 0 0 1 .244.304l-.459 2.066A1.75 1.75 0 0 0 10.747 15H11a.75.75 0 0 0 0-1.5h-.253a.25.25 0 0 1-.244-.304l.459-2.066A1.75 1.75 0 0 0 9.253 9H9Z"
                                            clip-rule="evenodd" />
                                    </svg>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <h3 x-show="notification.title"
                                        class="text-xs md:text-sm font-semibold text-info truncate"
                                        x-text="notification.title"></h3>
                                    <p x-show="notification.message"
                                        class="text-[11px] md:text-xs text-on-surface/70 dark:text-on-surface-dark/70 mt-0.5 leading-tight"
                                        x-text="notification.message"></p>
                                </div>
                                <button type="button"
                                    class="ml-auto text-on-surface/50 hover:text-on-surface dark:hover:text-white transition-colors focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-current"
                                    aria-label="Close notification" @click="close()">
                                    <svg class="size-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                        aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </template>

                    {{-- Success --}}
                    <template x-if="notification.variant === 'success'">
                        <div x-show="visible" x-cloak role="status" aria-live="polite" aria-atomic="true"
                            class="pointer-events-auto relative w-full overflow-hidden rounded-radius border border-success/50 bg-surface shadow-xl dark:bg-surface-dark"
                            x-transition:enter="transition duration-300 ease-out"
                            x-transition:enter-start="translate-x-full opacity-0"
                            x-transition:enter-end="translate-x-0 opacity-100"
                            x-transition:leave="transition duration-300 ease-in"
                            x-transition:leave-start="opacity-100 translate-x-0"
                            x-transition:leave-end="opacity-0 translate-x-12">
                            <div class="flex items-center gap-2.5 p-3 md:p-4 bg-success/5">
                                <div class="rounded-full bg-success/15 p-0.5 text-success shrink-0" aria-hidden="true">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"
                                        class="size-5">
                                        <path fill-rule="evenodd"
                                            d="M10 18a8 8 0 1 0 0-16 8 8 0 0 0 0 16Zm3.857-9.809a.75.75 0 0 0-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 1 0-1.06 1.061l2.5 2.5a.75.75 0 0 0 1.137-.089l4-5.5Z"
                                            clip-rule="evenodd" />
                                    </svg>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <h3 x-show="notification.title"
                                        class="text-xs md:text-sm font-semibold text-success truncate"
                                        x-text="notification.title"></h3>
                                    <p x-show="notification.message"
                                        class="text-[11px] md:text-xs text-on-surface/70 dark:text-on-surface-dark/70 mt-0.5 leading-tight"
                                        x-text="notification.message"></p>
                                </div>
                                <button type="button"
                                    class="ml-auto text-on-surface/50 hover:text-on-surface dark:hover:text-white transition-colors focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-current"
                                    aria-label="Close notification" @click="close()">
                                    <svg class="size-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                        aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </template>

                    {{-- Warning --}}
                    <template x-if="notification.variant === 'warning'">
                        <div x-show="visible" x-cloak role="status" aria-live="polite" aria-atomic="true"
                            class="pointer-events-auto relative w-full overflow-hidden rounded-radius border border-warning/50 bg-surface shadow-xl dark:bg-surface-dark"
                            x-transition:enter="transition duration-300 ease-out"
                            x-transition:enter-start="translate-x-full opacity-0"
                            x-transition:enter-end="translate-x-0 opacity-100"
                            x-transition:leave="transition duration-300 ease-in"
                            x-transition:leave-start="opacity-100 translate-x-0"
                            x-transition:leave-end="opacity-0 translate-x-12">
                            <div class="flex items-center gap-2.5 p-3 md:p-4 bg-warning/5">
                                <div class="rounded-full bg-warning/15 p-0.5 text-warning shrink-0"
                                    aria-hidden="true">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"
                                        class="size-5">
                                        <path fill-rule="evenodd"
                                            d="M8.485 2.495c.673-1.167 2.357-1.167 3.03 0l6.28 10.875c.673 1.167-.17 2.625-1.516 2.625H3.72c-1.347 0-2.189-1.458-1.515-2.625L8.485 2.495ZM10 5a.75.75 0 0 1 .75.75v3.5a.75.75 0 0 1-1.5 0v-3.5A.75.75 0 0 1 10 5Zm0 9a1 1 0 1 0 0-2 1 1 0 0 0 0 2Z"
                                            clip-rule="evenodd" />
                                    </svg>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <h3 x-show="notification.title"
                                        class="text-xs md:text-sm font-semibold text-warning truncate"
                                        x-text="notification.title"></h3>
                                    <p x-show="notification.message"
                                        class="text-[11px] md:text-xs text-on-surface/70 dark:text-on-surface-dark/70 mt-0.5 leading-tight"
                                        x-text="notification.message"></p>
                                </div>
                                <button type="button"
                                    class="ml-auto text-on-surface/50 hover:text-on-surface dark:hover:text-white transition-colors focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-current"
                                    aria-label="Close notification" @click="close()">
                                    <svg class="size-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                        aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </template>

                    {{-- Danger / Error --}}
                    <template x-if="notification.variant === 'danger' || notification.variant === 'error'">
                        <div x-show="visible" x-cloak role="status" aria-live="polite" aria-atomic="true"
                            class="pointer-events-auto relative w-full overflow-hidden rounded-radius border border-danger/50 bg-surface shadow-xl dark:bg-surface-dark"
                            x-transition:enter="transition duration-300 ease-out"
                            x-transition:enter-start="translate-x-full opacity-0"
                            x-transition:enter-end="translate-x-0 opacity-100"
                            x-transition:leave="transition duration-300 ease-in"
                            x-transition:leave-start="opacity-100 translate-x-0"
                            x-transition:leave-end="opacity-0 translate-x-12">
                            <div class="flex items-center gap-2.5 p-3 md:p-4 bg-danger/5">
                                <div class="rounded-full bg-danger/15 p-0.5 text-danger shrink-0" aria-hidden="true">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"
                                        class="size-5">
                                        <path fill-rule="evenodd"
                                            d="M10 18a8 8 0 1 0 0-16 8 8 0 0 0 0 16ZM8.28 7.22a.75.75 0 0 0-1.06 1.06L8.94 10l-1.72 1.72a.75.75 0 1 0 1.06 1.06L10 11.06l1.72 1.72a.75.75 0 1 0 1.06-1.06L11.06 10l1.72-1.72a.75.75 0 0 0-1.06-1.06L10 8.94 8.28 7.22Z"
                                            clip-rule="evenodd" />
                                    </svg>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <h3 x-show="notification.title"
                                        class="text-xs md:text-sm font-semibold text-danger truncate"
                                        x-text="notification.title"></h3>
                                    <p x-show="notification.message"
                                        class="text-[11px] md:text-xs text-on-surface/70 dark:text-on-surface-dark/70 mt-0.5 leading-tight"
                                        x-text="notification.message"></p>
                                </div>
                                <button type="button"
                                    class="ml-auto text-on-surface/50 hover:text-on-surface dark:hover:text-white transition-colors focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-current"
                                    aria-label="Close notification" @click="close()">
                                    <svg class="size-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                        aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </template>

                    {{-- Message (with avatar) --}}
                    <template x-if="notification.variant === 'message'">
                        <div x-show="visible" x-cloak role="status" aria-live="polite" aria-atomic="true"
                            class="pointer-events-auto relative w-full overflow-hidden rounded-radius border border-outline bg-surface shadow-xl dark:border-outline-dark dark:bg-surface-dark"
                            x-transition:enter="transition duration-300 ease-out"
                            x-transition:enter-start="translate-x-full opacity-0"
                            x-transition:enter-end="translate-x-0 opacity-100"
                            x-transition:leave="transition duration-300 ease-in"
                            x-transition:leave-start="opacity-100 translate-x-0"
                            x-transition:leave-end="opacity-0 translate-x-12">
                            <div class="flex items-start gap-3 p-3 md:p-4 bg-surface-alt dark:bg-surface-dark-alt">
                                <img x-show="notification.sender && notification.sender.avatar"
                                    :src="notification.sender ? notification.sender.avatar : ''"
                                    class="size-8 md:size-10 rounded-full object-cover border border-outline shadow-sm shrink-0"
                                    alt="">
                                <div class="flex-1 min-w-0">
                                    <h3 x-show="notification.sender && notification.sender.name"
                                        class="text-xs md:text-sm font-semibold text-on-surface-strong dark:text-on-surface-dark-strong truncate"
                                        x-text="notification.sender ? notification.sender.name : ''"></h3>
                                    <p x-show="notification.message"
                                        class="text-[11px] md:text-xs text-on-surface/70 dark:text-on-surface-dark/70 mt-0.5 leading-tight"
                                        x-text="notification.message"></p>
                                </div>
                                <button type="button"
                                    class="ml-auto text-on-surface/50 hover:text-on-surface dark:hover:text-white transition-colors focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-current"
                                    aria-label="Close notification" @click="close()">
                                    <svg class="size-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                        aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </template>

                </div>
            </template>
        </div>
    </div>
</div>
