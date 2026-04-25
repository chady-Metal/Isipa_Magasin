@props([
    'title',
    'description',
])

<div class="flex w-full flex-col gap-2 text-center">
    <h1 class="text-2xl font-black tracking-tight text-[var(--isipa-ink)]">{{ $title }}</h1>
    <p class="text-center text-sm text-slate-600">{{ $description }}</p>
</div>
