<div
    x-data="{
        seconds: 0,
        resetting: false,
        period: null,
        resetToken: {!! json_encode(\App\Models\Order::orderByDesc('id')->value('created_at')?->timestamp) !!},
        init() {
            this.period = this.currentPeriod();
            this.calculateSeconds();
            setInterval(() => this.calculateSeconds(), 1000);
        },
        currentPeriod() {
            const now = new Date();
            return now.getHours() * 2 + (now.getMinutes() >= 30 ? 1 : 0);
        },
        calculateSeconds() {
            if (this.resetting) return;
            const newPeriod = this.currentPeriod();
            if (newPeriod !== this.period) {
                this.period = newPeriod;
                this.resetting = true;
                this.waitForReset();
                return;
            }
            const now = new Date();
            const minutes = now.getMinutes();
            const secs = now.getSeconds();
            const remaining = minutes < 30
                ? (30 - minutes) * 60 - secs
                : (60 - minutes) * 60 - secs;
            this.seconds = Math.max(0, remaining);
        },
        get formatted() {
            const m = Math.floor(this.seconds / 60).toString().padStart(2, '0');
            const s = (this.seconds % 60).toString().padStart(2, '0');
            return m + ':' + s;
        },
        waitForReset() {
            const poll = () => {
                fetch('/demo-reset-token', { cache: 'no-store' })
                    .then(r => r.json())
                    .then(data => {
                        if (data.token !== null && data.token !== this.resetToken) {
                            window.location.href = '/';
                        } else {
                            setTimeout(poll, 2000);
                        }
                    })
                    .catch(() => setTimeout(poll, 2000));
            };
            setTimeout(poll, 2000);
        }
    }"
    x-init="init()"
>
    {{-- Full-screen overlay shown while the reset is in progress --}}
    <div
        x-show="resetting"
        x-cloak
        class="fixed inset-0 z-[9999] flex items-center justify-center bg-white/95 dark:bg-gray-900/95 backdrop-blur-sm"
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
