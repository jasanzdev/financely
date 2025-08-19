@props([
    'message'
])

<div class="fixed bottom-0 right-0" x-cloak>
    <div x-data="{ alertIsVisible: true }"
         x-show="alertIsVisible"
         x-init="setTimeout(() => alertIsVisible = false, 3000)"
         class="relative w-full overflow-hidden rounded-radius border border-success bg-surface text-on-surface dark:bg-surface-dark dark:text-on-surface-dark"
         role="alert"
         x-transition:leave="transition ease-in duration-300"
         x-transition:leave-start="opacity-100 scale-100"
         x-transition:leave-end="opacity-0 scale-90">

        <div class="flex w-full items-center gap-2 bg-success/10 p-4">
            <div class="bg-success/15 text-success rounded-full p-1" aria-hidden="true">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"
                     class="size-6" aria-hidden="true">
                    <path fill-rule="evenodd"
                          d="M10 18a8 8 0 1 0 0-16 8 8 0 0 0 0 16Zm3.857-9.809a.75.75 0 0 0-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 1 0-1.06 1.061l2.5 2.5a.75.75 0 0 0 1.137-.089l4-5.5Z"
                          clip-rule="evenodd"/>
                </svg>
            </div>
            <div class="ml-2">
                <h3 class="text-sm font-semibold text-success">Operación Exitosa</h3>
                <p class="text-xs font-medium sm:text-sm">{{ $message }}</p>
            </div>
            <button type="button" @click="alertIsVisible = false" class="ml-auto"
                    aria-label="dismiss alert">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" aria-hidden="true"
                     stroke="currentColor" fill="none" stroke-width="2.5" class="w-4 h-4 shrink-0">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>
    </div>
</div>
