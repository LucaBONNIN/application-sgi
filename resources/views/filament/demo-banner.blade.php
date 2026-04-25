<div
    class="w-full bg-amber-400 dark:bg-amber-600 text-amber-950 dark:text-amber-50 text-center text-sm py-2 px-4"
    x-data="{
        seconds: 0,
        init() {
            this.calculateSeconds();
            setInterval(() => this.calculateSeconds(), 1000);
        },
        calculateSeconds() {
            const now = new Date();
            const minutes = now.getMinutes();
            const secs = now.getSeconds();
            const remaining = minutes < 30
                ? (30 - minutes) * 60 - secs
                : (60 - minutes) * 60 - secs;
            if (remaining <= 0 && this.seconds > 0) {
                window.location.reload();
                return;
            }
            this.seconds = Math.max(0, remaining);
        },
        get formatted() {
            const m = Math.floor(this.seconds / 60).toString().padStart(2, '0');
            const s = (this.seconds % 60).toString().padStart(2, '0');
            return m + ':' + s;
        }
    }"
    x-init="init()"
>
    <strong>{{ __('filament/demo.banner.title') }}</strong>
    &mdash;
    {{ __('filament/demo.banner.message') }}
    {{ __('filament/demo.banner.next_reset') }} <strong x-text="formatted">--:--</strong>.
</div>
