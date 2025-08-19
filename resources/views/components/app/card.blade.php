@props([
    'title' => 'Default Title',
    'amount' => '0',
    'description' => 'Default description',
    'icon' => 'default-icon',
    'amountColor' => 'text-green-700',
    'iconColor' => 'text-green-700',
])

<div
    class="relative h-full w-full rounded-xl border border-neutral-200 dark:border-neutral-700 shadow-lg overflow-hidden">
    <div class="flex flex-col h-full p-4 md:p-5">
        <div class="flex justify-between items-start mb-2 md:mb-3">
            <h1 class="font-serif text-base md:text-lg lg:text-xl dark:text-neutral-100 line-clamp-1">{{ $title }}</h1>
            <span class="{{ $iconColor }} ml-2">
                @if($icon === 'default-icon')
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"
                         class="w-5 h-5 md:w-6 md:h-6">
                        <path
                            d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 18c-4.41 0-8-3.59-8-8s3.59-8 8-8 8 3.59 8 8-3.59 8-8 8z"/>
                    </svg>
                @else
                    <div class="w-5 h-5 md:w-6 md:h-6">
                        {!! $icon !!}
                    </div>
                @endif
            </span>
        </div>
        <div class="mb-2 md:mb-3">
            <h2 class="{{ $amountColor }} font-bold text-2xl md:text-3xl lg:text-4xl line-clamp-1">{{ $amount }}</h2>
        </div>
        <div class="mt-auto">
            <span
                class="text-sm md:text-base text-neutral-600 dark:text-neutral-400 line-clamp-2">{{ $description }}</span>
        </div>
    </div>
</div>
