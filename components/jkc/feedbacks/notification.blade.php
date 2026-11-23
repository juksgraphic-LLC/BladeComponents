@php
    use Juksgraphic\BladeComponents\Components\Feedbacks\Notification;
    use Juksgraphic\BladeComponents\Components\Feedbacks\NotificationStyles;

    
    $manager = new Notification([
        'id'              => $id ?? 'global-notifications',
        'position'        => $position ?? 'top-right',
        'max'             => $max      ?? 20,
        'displayDuration' => $displayDuration ?? 8000,
        'leaveDuration'   => $leaveDuration ?? 400,
        'soundEffect'     => $soundEffect   ?? false,
        'soundUrl'        => $soundUrl      ?? Notification::DEFAULT_SOUND_URL,
    ]);

    
    $styles = new NotificationStyles();

    $semanticVariants = [
        'info'    => $styles->semanticVariant('info'),
        'success' => $styles->semanticVariant('success'),
        'warning' => $styles->semanticVariant('warning'),
        'danger'  => $styles->semanticVariant('danger'),
    ];
@endphp

<!-- Full-viewport fixed overlay for dynamic notifications -->
<div
    id="{{ $manager->id() }}"
    class="{{ $styles->overlay() }}"
>

    <!-- Alpine.js notification queue and event listener controller -->
    <div
        x-data="{
            ...{{ $manager->toJson() }},

            // Normalize payload, enforce queue cap, append item, and emit lifecycle event
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

                if (this.soundEffect) {
                    new Audio(this.soundUrl).play().catch(() => {});
                }
            },

            // Remove a notification entry by ID after exit animation
            remove(id) {
                this.notifications = this.notifications.filter(n => n.id !== id);
            }
        }"
        @jkc-notify.window="push($event.detail, 'notify')"
        @jkc-toast-show.window="push({
            variant: $event.detail.type || 'info',
            title: $event.detail.message,
            message: $event.detail.description || ''
        }, 'toast-show')"
        class="{{ $styles->stage() }}"
    >

        <!-- Dynamic corner/edge position container -->
        <div
            x-data="{ position: '{{ $manager->position() }}' }"
            @jkc-toast-position.window="position = $event.detail.position || position"
            class="{{ $styles->stackContainer() }}"
            :class="{!! $styles->positionBinding() !!}"
        >

            <!-- Individual notification item with auto-dismiss and hover pause -->
            <template
                x-for="notification in notifications"
                :key="notification.id"
            >
                <div
                    x-data="{
                        visible: false,
                        timeout: null,

                        // Start or reset the auto-dismiss timer
                        start() {
                            clearTimeout(this.timeout);
                            if (this.displayDuration > 0) {
                                this.timeout = setTimeout(() => this.close(), this.displayDuration);
                            }
                        },

                        // Pause auto-dismiss while pointer hovers over notification
                        pause() {
                            clearTimeout(this.timeout);
                        },

                        // Resume auto-dismiss when pointer leaves notification
                        resume() {
                            this.start();
                        },

                        // Trigger exit transition and schedule removal from stack
                        close() {
                            clearTimeout(this.timeout);
                            this.visible = false;
                            setTimeout(() => this.remove(notification.id), this.leaveDuration);
                        }
                    }"
                    x-init="$nextTick(() => {
                        visible = true;
                        start();
                    })"
                    @mouseenter="pause()"
                    @mouseleave="resume()"
                    class="w-full"
                >

                    <!-- Info / Default / Primary notification card -->
                    <template x-if="notification.variant === 'info' || notification.variant === 'default' || notification.variant === 'primary'">
                        <div
                            x-show="visible"
                            x-cloak
                            role="status"
                            aria-live="polite"
                            aria-atomic="true"
                            class="{{ $semanticVariants['info']['card'] }}"
                            x-transition:enter="transition duration-300 ease-out"
                            x-transition:enter-start="translate-x-full opacity-0"
                            x-transition:enter-end="translate-x-0 opacity-100"
                            x-transition:leave="transition duration-300 ease-in"
                            x-transition:leave-start="opacity-100 translate-x-0"
                            x-transition:leave-end="opacity-0 translate-x-12"
                        >
                            <div class="{{ $semanticVariants['info']['inner'] }}">
                                <div class="{{ $semanticVariants['info']['icon'] }}" aria-hidden="true">
                                    {!! $semanticVariants['info']['svg'] !!}
                                </div>

                                <div class="flex-1 min-w-0">
                                    <h3
                                        x-show="notification.title"
                                        class="{{ $semanticVariants['info']['title'] }}"
                                        x-text="notification.title"
                                    ></h3>
                                    <p
                                        x-show="notification.message"
                                        class="{{ $styles->messageBody() }}"
                                        x-text="notification.message"
                                    ></p>
                                </div>

                                <button
                                    type="button"
                                    class="{{ $styles->closeButton() }}"
                                    aria-label="Close notification"
                                    @click="close()"
                                >
                                    <svg class="size-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </template>

                    <!-- Success notification card -->
                    <template x-if="notification.variant === 'success'">
                        <div
                            x-show="visible"
                            x-cloak
                            role="status"
                            aria-live="polite"
                            aria-atomic="true"
                            class="{{ $semanticVariants['success']['card'] }}"
                            x-transition:enter="transition duration-300 ease-out"
                            x-transition:enter-start="translate-x-full opacity-0"
                            x-transition:enter-end="translate-x-0 opacity-100"
                            x-transition:leave="transition duration-300 ease-in"
                            x-transition:leave-start="opacity-100 translate-x-0"
                            x-transition:leave-end="opacity-0 translate-x-12"
                        >
                            <div class="{{ $semanticVariants['success']['inner'] }}">
                                <div class="{{ $semanticVariants['success']['icon'] }}" aria-hidden="true">
                                    {!! $semanticVariants['success']['svg'] !!}
                                </div>

                                <div class="flex-1 min-w-0">
                                    <h3
                                        x-show="notification.title"
                                        class="{{ $semanticVariants['success']['title'] }}"
                                        x-text="notification.title"
                                    ></h3>
                                    <p
                                        x-show="notification.message"
                                        class="{{ $styles->messageBody() }}"
                                        x-text="notification.message"
                                    ></p>
                                </div>

                                <button
                                    type="button"
                                    class="{{ $styles->closeButton() }}"
                                    aria-label="Close notification"
                                    @click="close()"
                                >
                                    <svg class="size-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </template>

                    <!-- Warning notification card -->
                    <template x-if="notification.variant === 'warning'">
                        <div
                            x-show="visible"
                            x-cloak
                            role="status"
                            aria-live="polite"
                            aria-atomic="true"
                            class="{{ $semanticVariants['warning']['card'] }}"
                            x-transition:enter="transition duration-300 ease-out"
                            x-transition:enter-start="translate-x-full opacity-0"
                            x-transition:enter-end="translate-x-0 opacity-100"
                            x-transition:leave="transition duration-300 ease-in"
                            x-transition:leave-start="opacity-100 translate-x-0"
                            x-transition:leave-end="opacity-0 translate-x-12"
                        >
                            <div class="{{ $semanticVariants['warning']['inner'] }}">
                                <div class="{{ $semanticVariants['warning']['icon'] }}" aria-hidden="true">
                                    {!! $semanticVariants['warning']['svg'] !!}
                                </div>

                                <div class="flex-1 min-w-0">
                                    <h3
                                        x-show="notification.title"
                                        class="{{ $semanticVariants['warning']['title'] }}"
                                        x-text="notification.title"
                                    ></h3>
                                    <p
                                        x-show="notification.message"
                                        class="{{ $styles->messageBody() }}"
                                        x-text="notification.message"
                                    ></p>
                                </div>

                                <button
                                    type="button"
                                    class="{{ $styles->closeButton() }}"
                                    aria-label="Close notification"
                                    @click="close()"
                                >
                                    <svg class="size-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </template>

                    <!-- Danger / Error notification card -->
                    <template x-if="notification.variant === 'danger' || notification.variant === 'error'">
                        <div
                            x-show="visible"
                            x-cloak
                            role="status"
                            aria-live="polite"
                            aria-atomic="true"
                            class="{{ $semanticVariants['danger']['card'] }}"
                            x-transition:enter="transition duration-300 ease-out"
                            x-transition:enter-start="translate-x-full opacity-0"
                            x-transition:enter-end="translate-x-0 opacity-100"
                            x-transition:leave="transition duration-300 ease-in"
                            x-transition:leave-start="opacity-100 translate-x-0"
                            x-transition:leave-end="opacity-0 translate-x-12"
                        >
                            <div class="{{ $semanticVariants['danger']['inner'] }}">
                                <div class="{{ $semanticVariants['danger']['icon'] }}" aria-hidden="true">
                                    {!! $semanticVariants['danger']['svg'] !!}
                                </div>

                                <div class="flex-1 min-w-0">
                                    <h3
                                        x-show="notification.title"
                                        class="{{ $semanticVariants['danger']['title'] }}"
                                        x-text="notification.title"
                                    ></h3>
                                    <p
                                        x-show="notification.message"
                                        class="{{ $styles->messageBody() }}"
                                        x-text="notification.message"
                                    ></p>
                                </div>

                                <button
                                    type="button"
                                    class="{{ $styles->closeButton() }}"
                                    aria-label="Close notification"
                                    @click="close()"
                                >
                                    <svg class="size-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </template>

                    <!-- Message notification card with optional sender avatar -->
                    <template x-if="notification.variant === 'message'">
                        <div
                            x-show="visible"
                            x-cloak
                            role="status"
                            aria-live="polite"
                            aria-atomic="true"
                            class="{{ $styles->messageCard() }}"
                            x-transition:enter="transition duration-300 ease-out"
                            x-transition:enter-start="translate-x-full opacity-0"
                            x-transition:enter-end="translate-x-0 opacity-100"
                            x-transition:leave="transition duration-300 ease-in"
                            x-transition:leave-start="opacity-100 translate-x-0"
                            x-transition:leave-end="opacity-0 translate-x-12"
                        >
                            <div class="{{ $styles->messageInner() }}">
                                <img
                                    x-show="notification.sender && notification.sender.avatar"
                                    :src="notification.sender ? notification.sender.avatar : ''"
                                    class="{{ $styles->avatar() }}"
                                    alt=""
                                >

                                <div class="flex-1 min-w-0">
                                    <h3
                                        x-show="notification.sender && notification.sender.name"
                                        class="{{ $styles->senderName() }}"
                                        x-text="notification.sender ? notification.sender.name : ''"
                                    ></h3>
                                    <p
                                        x-show="notification.message"
                                        class="{{ $styles->messageBody() }}"
                                        x-text="notification.message"
                                    ></p>
                                </div>

                                <button
                                    type="button"
                                    class="{{ $styles->closeButton() }}"
                                    aria-label="Close notification"
                                    @click="close()"
                                >
                                    <svg class="size-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
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