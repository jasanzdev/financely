<div x-cloak x-transition.opacity.duration.200ms
     class="fixed inset-0 z-30 flex items-end justify-center bg-black/70 p-4 pb-8 backdrop-blur-xs sm:items-center lg:p-8"
     role="dialog" aria-modal="true" aria-labelledby="defaultModalTitle">
    <!-- Modal Dialog -->
    <div
        x-transition:enter="transition ease-out duration-200 delay-100 motion-reduce:transition-opacity"
        x-transition:enter-start="scale-0" x-transition:enter-end="scale-100"
        class="flex max-w-lg flex-col gap-4 overflow-hidden rounded-radius border border-outline bg-surface text-on-surface dark:border-outline-dark dark:bg-surface-dark-alt dark:text-on-surface-dark">
        <!-- Dialog Header -->
        <div
            class="flex items-center justify-between border-b border-outline bg-surface-alt/60 p-4 dark:border-outline-dark dark:bg-surface-dark/20">
            <h3 id="defaultModalTitle"
                class="font-semibold tracking-wide text-on-surface-strong dark:text-on-surface-dark-strong">Modificar
                Categoría</h3>
            <a href="{{ route('category.index') }}" wire:navigate>
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" aria-hidden="true" stroke="currentColor"
                     fill="none" stroke-width="1.4" class="w-5 h-5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </a>
        </div>
        <!-- Dialog Body -->
        <div class="flex flex-col gap-4 px-4 py-8 w-80">
            <div class="flex w-full max-w-xs flex-col gap-1 text-on-surface dark:text-on-surface-dark">
                <label for="textInputDefault" class="w-fit pl-0.5 text-sm">Categoría*</label>
                <input id="textInputDefault" type="text" disabled
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
            <button type="button" wire:click="edit"
                    class="bg-gray-950 p-3 rounded-lg text-neutral-50 text-center sm:text-sm hover:bg-gray-700 transition-colors ease-in-out">
                Actualizar
            </button>
            <a href="{{ route('category.index') }}" wire:navigate
               class="bg-gray-100 p-2 px-6 text-center rounded-lg shadow border border-gray-300 text-gray-900 text-sm focus:ring-neutral-500
                        focus:border-neutral-500 hover:bg-gray-400 transition-colors ease-in-out ">
                Cancelar
            </a>
        </div>
    </div>
</div>
