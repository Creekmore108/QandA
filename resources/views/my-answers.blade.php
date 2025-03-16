<x-layouts.app.sidebar :title="$title ?? null">
    <flux:main>
<x-slot name="header">
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        {{ __('My Answers') }}
    </h2>
</x-slot>

<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <livewire:my-answers />
    </div>
</div>
    </flux:main>
</x-layouts.app.sidebar>
