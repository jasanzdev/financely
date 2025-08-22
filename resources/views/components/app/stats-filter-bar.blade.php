@props([
    'categories',
    'incomes',
    'expenses',
])

<div class="flex w-full justify-between items-center md:px-4">
    <div class="flex justify-start gap-3">
        @if($incomes > 0)
            <div
                class="flex justify-center gap-3 px-3 py-2 rounded-lg shadow shadow-neutral-300 dark:shadow-neutral-700 font-serif text-sm sm:text-base md:text-lg w-full sm:w-auto line-clamp-1">
                <span class="hidden sm:inline">Ingresos: </span>
                <p class="text-success">{{ $incomes }}</p>
            </div>
        @endif
        @if($expenses > 0)
            <div
                class="flex justify-center gap-3 px-3 py-2 rounded-lg shadow shadow-neutral-300 dark:shadow-neutral-700 font-serif text-sm sm:text-base md:text-lg w-full sm:w-auto line-clamp-1">
                <span class="hidden sm:inline">Gastos: </span>
                <p class="text-danger">{{ $expenses }}</p>
            </div>
        @endif
    </div>

    <div x-data="{modalIsOpen: false}">
        <button x-on:click="modalIsOpen = true" type="button"
                class="whitespace-nowrap rounded-radius bg-surface-alt border border-surface-alt px-4 py-2
                text-sm font-medium tracking-wide text-on-surface-strong transition hover:opacity-75 text-center
                focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-surface-alt
                active:opacity-100 active:outline-offset-0 disabled:opacity-75 disabled:cursor-not-allowed
                dark:bg-surface-dark-alt/20 dark:border-surface-dark-alt dark:text-on-surface-dark-strong
                dark:focus-visible:outline-surface-dark-alt">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-6">
                <path fill-rule="evenodd"
                      d="M3.792 2.938A49.069 49.069 0 0 1 12 2.25c2.797 0 5.54.236 8.209.688a1.857 1.857 0 0 1 1.541 1.836v1.044a3 3 0 0 1-.879 2.121l-6.182 6.182a1.5 1.5 0 0 0-.439 1.061v2.927a3 3 0 0 1-1.658 2.684l-1.757.878A.75.75 0 0 1 9.75 21v-5.818a1.5 1.5 0 0 0-.44-1.06L3.13 7.938a3 3 0 0 1-.879-2.121V4.774c0-.897.64-1.683 1.542-1.836Z"
                      clip-rule="evenodd"/>
            </svg>
        </button>
        <div x-cloak x-show="modalIsOpen" x-transition.opacity.duration.200ms x-trap.inert.noscroll="modalIsOpen"
             x-on:keydown.esc.window="modalIsOpen = false" x-on:click.self="modalIsOpen = false"
             class="fixed inset-0 z-30 flex items-start justify-center bg-black/20 p-4 pb-8 backdrop-blur-lg sm:items-center lg:p-8"
             role="dialog" aria-modal="true" aria-labelledby="defaultModalTitle">
            <!-- Modal Dialog -->
            <div x-show="modalIsOpen"
                 x-transition:enter="transition ease-out duration-200 delay-100 motion-reduce:transition-opacity"
                 x-transition:enter-start="opacity-0 scale-50" x-transition:enter-end="opacity-100 scale-100"
                 class="flex w-1/2 max-w-lg flex-col gap-4 overflow-hidden rounded-radius border border-outline bg-surface text-on-surface dark:border-outline-dark dark:bg-surface-dark-alt dark:text-on-surface-dark">
                <!-- Dialog Header -->
                <div
                    class="flex items-center justify-between border-b border-outline bg-surface-alt/60 p-4 dark:border-outline-dark dark:bg-surface-dark/20">
                    <h3 id="defaultModalTitle"
                        class="font-semibold tracking-wide text-on-surface-strong dark:text-on-surface-dark-strong">
                        Filtros</h3>
                    <button x-on:click="modalIsOpen = false" aria-label="close modal">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" aria-hidden="true"
                             stroke="currentColor" fill="none" stroke-width="1.4" class="w-5 h-5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
                <!-- Dialog Body -->
                <div class="px-4 py-8 space-y-6">
                    <div
                        class="relative flex items-center w-full max-w-xs flex-col gap-1 text-on-surface dark:text-on-surface-dark">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"
                             class="absolute pointer-events-none right-2 top-2 size-6">
                            <path fill-rule="evenodd"
                                  d="M3.792 2.938A49.069 49.069 0 0 1 12 2.25c2.797 0 5.54.236 8.209.688a1.857 1.857 0 0 1 1.541 1.836v1.044a3 3 0 0 1-.879 2.121l-6.182 6.182a1.5 1.5 0 0 0-.439 1.061v2.927a3 3 0 0 1-1.658 2.684l-1.757.878A.75.75 0 0 1 9.75 21v-5.818a1.5 1.5 0 0 0-.44-1.06L3.13 7.938a3 3 0 0 1-.879-2.121V4.774c0-.897.64-1.683 1.542-1.836Z"
                                  clip-rule="evenodd"/>
                        </svg>

                        <select id="type"
                                name="type"
                                wire:model="showType"
                                class="w-full appearance-none rounded-radius border border-outline bg-surface-alt px-4 py-2 text-sm focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary disabled:cursor-not-allowed disabled:opacity-75 dark:border-outline-dark dark:bg-surface-dark-alt/50 dark:focus-visible:outline-primary-dark">
                            <option value="all" selected>Todos</option>
                            <option value="income">Ingresos</option>
                            <option value="expense">Gastos</option>
                        </select>
                    </div>

                    <div class="relative flex w-full max-w-xs flex-col gap-1 text-on-surface dark:text-on-surface-dark">
                        <label for="os" class="w-fit pl-0.5 text-sm">Categorías</label>
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"
                             class="absolute pointer-events-none right-4 top-8 size-5">
                            <path fill-rule="evenodd"
                                  d="M5.22 8.22a.75.75 0 0 1 1.06 0L10 11.94l3.72-3.72a.75.75 0 1 1 1.06 1.06l-4.25 4.25a.75.75 0 0 1-1.06 0L5.22 9.28a.75.75 0 0 1 0-1.06Z"
                                  clip-rule="evenodd"/>
                        </svg>
                        <select id="category" name="selectedCategory" wire:model="selectedCategory"
                                class="w-full appearance-none rounded-radius border border-outline bg-surface-alt px-4 py-2 text-sm focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary disabled:cursor-not-allowed disabled:opacity-75 dark:border-outline-dark dark:bg-surface-dark-alt/50 dark:focus-visible:outline-primary-dark">
                            <option selected value="all">Limpiar filtro</option>
                            @foreach($categories as $category)
                                <option value="{{$category->id}}">{{ $category->category }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <!-- Dialog Footer -->
                <div
                    class="flex flex-col-reverse justify-between gap-2 border-t border-outline bg-surface-alt/60 p-4 dark:border-outline-dark dark:bg-surface-dark/20 sm:flex-row sm:items-center md:justify-end">
                    <button x-on:click="modalIsOpen = false" wire:click="applyFilters" type="button"
                            class="whitespace-nowrap rounded-radius px-4 py-2 text-center text-sm font-medium tracking-wide text-on-surface transition hover:opacity-75 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary active:opacity-100 active:outline-offset-0 dark:text-on-surface-dark dark:focus-visible:outline-primary-dark">
                        Aplicar
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
