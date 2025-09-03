<div x-cloak x-show="modalIsOpen" x-transition.opacity.duration.200ms x-trap.inert.noscroll="modalIsOpen"
     x-on:keydown.esc.window="modalIsOpen = false" x-on:click.self="modalIsOpen = false"
     class="fixed inset-0 z-30 flex items-start justify-center bg-black/70 p-4 pb-8 backdrop-blur-xs sm:items-center lg:p-8"
     role="dialog" aria-modal="true" aria-labelledby="defaultModalTitle">
    <!-- Modal Dialog -->
    <div x-show="modalIsOpen"
         x-transition:enter="transition ease-out duration-200 delay-100 motion-reduce:transition-opacity"
         x-transition:enter-start="scale-0" x-transition:enter-end="scale-100"
         class="flex max-w-lg flex-col gap-4 overflow-hidden rounded-radius border border-outline bg-surface text-on-surface dark:border-outline-dark dark:bg-surface-dark-alt dark:text-on-surface-dark">
        <!-- Dialog Header -->
        <div
            class="flex items-center justify-between border-b border-outline bg-surface-alt/60 p-4 dark:border-outline-dark dark:bg-surface-dark/20">
            <h3 id="defaultModalTitle"
                class="font-semibold tracking-wide text-on-surface-strong dark:text-on-surface-dark-strong">Crear
                Categoría</h3>
            <button x-on:click="modalIsOpen = false" aria-label="close modal">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" aria-hidden="true" stroke="currentColor"
                     fill="none" stroke-width="1.4" class="w-5 h-5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>
        <!-- Dialog Body -->
        <div class="flex flex-col gap-4 px-4 py-8 w-80">
            <div class="flex w-full max-w-xs flex-col gap-1 text-on-surface dark:text-on-surface-dark">
                <label for="textInputDefault" class="w-fit pl-0.5 text-sm">Categoría*</label>
                <input id="textInputDefault" type="text"
                       class="w-full rounded-radius border border-outline bg-surface-alt px-2 py-2 text-sm focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary disabled:cursor-not-allowed disabled:opacity-75 dark:border-outline-dark dark:bg-surface-dark-alt/50 dark:focus-visible:outline-primary-dark"
                       wire:model="form.category" placeholder="Food, Monthly Bills, Tobacco, Business ..."
                       autocomplete="category"/>
                <div>
                    @error('form.category') <span class="error">{{ $message }}</span> @enderror
                </div>
            </div>
            <div class="flex w-full max-w-md flex-col gap-1 text-on-surface dark:text-on-surface-dark">
                <label for="textArea" class="w-fit pl-0.5 text-sm">Descripción</label>
                <textarea id="textArea" wire:model="form.description"
                          class="w-full rounded-radius border border-outline bg-surface-alt px-2.5 py-2 text-sm focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary disabled:cursor-not-allowed disabled:opacity-75 dark:border-outline-dark dark:bg-surface-dark-alt/50 dark:focus-visible:outline-primary-dark"
                          rows="3" placeholder="Purchase of cigarettes, earnings of business ..."></textarea>
                <div>
                    @error('form.description') <span class="error">{{ $message }}</span> @enderror
                </div>
            </div>
        </div>
        <!-- Dialog Footer -->
        <div
            class="flex flex-col-reverse justify-between gap-2 border-t border-outline bg-surface-alt/60 p-4 dark:border-outline-dark dark:bg-surface-dark/20 sm:flex-row sm:items-center md:justify-end">
            <button type="button" wire:click="save"
                    class="whitespace-nowrap rounded-radius border border-primary dark:border-primary-dark bg-primary px-4 py-2 text-center text-sm font-medium tracking-wide text-on-primary transition hover:opacity-75 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary active:opacity-100 active:outline-offset-0 dark:bg-primary-dark dark:text-on-primary-dark dark:focus-visible:outline-primary-dark">
                Crear
            </button>
        </div>
    </div>
</div>
