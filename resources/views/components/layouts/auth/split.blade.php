<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
<head>
    @include('partials.head')
</head>
<body class="min-h-screen bg-white antialiased dark:bg-linear-to-b dark:from-neutral-950 dark:to-neutral-900">
<div class="relative grid h-dvh flex-col items-center justify-center px-8 sm:px-0 lg:max-w-none lg:grid-cols-2 lg:px-0">
    <div
        class="bg-muted relative hidden h-full flex-col p-10 text-white lg:flex dark:border-e dark:border-neutral-800">
        <div class="absolute inset-0 bg-neutral-900">
            <img src="{{ Vite::asset('public/images/man-calculating-finance.webp') }}"
                 alt="Man reviewing financial documents with a magnifying glass and calculator on a desk"
                 loading="lazy"
                 class="w-[770px] h-full object-cover"
            />
            <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/60 to-transparent"></div>
        </div>
        <a href="{{ route('home') }}" class="relative z-20 flex items-center text-lg font-bold text-neutral-950"
           wire:navigate>
                    <span class="flex h-10 w-10 items-center justify-center rounded-md">
                        <x-app-logo-icon class="me-2 h-7 fill-current text-white"/>
                    </span>
            {{ config('app.name', 'Control Financiero') }}
        </a>

        <div class="relative z-20 mt-auto">
            <blockquote class="space-y-2">
                <flux:heading size="lg" class="text-white">
                    No ahorres lo que queda después de gastar, gasta lo que queda después de ahorrar
                </flux:heading>
                <footer>
                    <flux:heading size="lg" class="text-white">"Warren Buffett"</flux:heading>
                </footer>
            </blockquote>
        </div>
    </div>
    <div class="w-full lg:p-8">
        <div class="mx-auto flex w-full flex-col justify-center space-y-6 sm:w-[350px]">
            <a href="{{ route('home') }}" class="z-20 flex flex-col items-center gap-2 font-medium lg:hidden"
               wire:navigate>
                        <span class="flex h-9 w-9 items-center justify-center rounded-md">
                            <x-app-logo-icon class="size-9 fill-current text-black dark:text-white"/>
                        </span>

                <span class="sr-only">{{ config('app.name', 'Control Financiero') }}</span>
            </a>
            {{ $slot }}
        </div>
    </div>
</div>
@fluxScripts
</body>
</html>
