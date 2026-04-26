<div
    x-data="{
        seconds: 0,
        resetting: false,
        init() {
            this.calculateSeconds();
            setInterval(() => this.calculateSeconds(), 1000);
        },
        calculateSeconds() {
            if (this.resetting) return;
            const now = new Date();
            const minutes = now.getMinutes();
            const secs = now.getSeconds();
            const remaining = minutes < 30
                ? (30 - minutes) * 60 - secs
                : (60 - minutes) * 60 - secs;
            if (remaining <= 0 && this.seconds > 0) {
                this.resetting = true;
                this.waitForReset();
                return;
            }
            this.seconds = Math.max(0, remaining);
        },
        get formatted() {
            const m = Math.floor(this.seconds / 60).toString().padStart(2, '0');
            const s = (this.seconds % 60).toString().padStart(2, '0');
            return m + ':' + s;
        },
        waitForReset() {
            let seenMaintenance = false;
            const poll = () => {
                fetch('/up', { cache: 'no-store' })
                    .then(r => {
                        if (r.status === 503) {
                            seenMaintenance = true;
                            setTimeout(poll, 1000);
                        } else if (r.ok && seenMaintenance) {
                            window.location.href = '/';
                        } else {
                            setTimeout(poll, 1000);
                        }
                    })
                    .catch(() => setTimeout(poll, 1000));
            };
            poll();
        }
    }"
    x-init="init()"
>
    {{-- Full-screen overlay shown while the reset is in progress --}}
    <div
        x-show="resetting"
        style="display: none;"
        class="fixed inset-0 z-[100] flex items-center justify-center bg-white/90 dark:bg-gray-900/90 backdrop-blur-sm"
    >
        <p class="text-base font-medium text-gray-800 dark:text-gray-200">
            {{ __('filament/demo.banner.resetting') }}
        </p>
    </div>

    {{-- Top banner bar --}}
    <div
        x-show="!resetting"
        class="w-full bg-amber-400 dark:bg-amber-600 text-amber-950 dark:text-amber-50 text-center text-sm py-2 px-4"
    >
        <strong>{{ __('filament/demo.banner.title') }}</strong>
        &mdash;
        {{ __('filament/demo.banner.message') }}
        {{ __('filament/demo.banner.next_reset') }} <strong x-text="formatted">--:--</strong>.
    </div>
</div>
