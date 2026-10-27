<div class="mb-6 rounded-2xl border border-white/20 bg-white/10 p-5 text-white backdrop-blur-md">
    <div class="flex items-center gap-3">
        <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-white/20 text-2xl">
            🏢
        </div>
        <div>
            <p class="text-sm text-white/70">
                الدخول إلى
            </p>
            <h3 class="text-lg font-bold">
                {{ $context->name ?? 'النظام' }}
            </h3>
        </div>
    </div>
    <div class="mt-4 flex items-center justify-between">
        <span class="rounded-full bg-white/20 px-3 py-1 text-sm">
            {{ $context->context_type ?? 'SYSTEM' }}
        </span>
        <span class="flex items-center gap-2 text-sm text-green-300">
            <span class="h-2 w-2 rounded-full bg-green-400"></span>
            متاح
        </span>
    </div>
</div>