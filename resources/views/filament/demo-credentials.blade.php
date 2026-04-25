<div class="mt-6 rounded-xl border border-amber-300 dark:border-amber-700 bg-amber-50 dark:bg-amber-950/40 p-4 text-sm">
    <p class="font-semibold text-amber-800 dark:text-amber-300 mb-3">{{ __('filament/demo.credentials.title') }}</p>

    <div class="grid grid-cols-1 gap-3">
        <div>
            <p class="text-xs font-medium uppercase tracking-wide text-amber-600 dark:text-amber-400 mb-1">
                {{ __('filament/demo.credentials.administrator') }}
            </p>
            <p class="font-mono text-amber-900 dark:text-amber-100">admin.intendance.1@127011.xyz</p>
            <p class="font-mono text-amber-900 dark:text-amber-100">password</p>
        </div>

        @if ($firstUser)
            <div>
                <p class="text-xs font-medium uppercase tracking-wide text-amber-600 dark:text-amber-400 mb-1">
                    {{ __('filament/demo.credentials.user') }}
                </p>
                <p class="font-mono text-amber-900 dark:text-amber-100">{{ $firstUser->email }}</p>
                <p class="font-mono text-amber-900 dark:text-amber-100">password</p>
            </div>
        @endif
    </div>
</div>
