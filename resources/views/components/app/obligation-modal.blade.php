@props([
    'obligation'
])

<div x-cloak x-show="obligationModalIsOpen"
     x-transition.opacity.duration.200ms
     x-trap.inert.noscroll="obligationModalIsOpen"
     x-on:keydown.esc.window="$wire.closeModal"
     x-on:click.self="$wire.closeModal"
     class="fixed inset-0 z-30 flex items-start justify-center bg-black/20 p-4 pb-8 backdrop-blur-sm sm:items-center lg:p-8"
     role="dialog"
     aria-modal="true"
     aria-labelledby="defaultModalTitle">
    <!-- Modal Dialog -->
    <div x-show="obligationModalIsOpen"
         x-transition:enter="transition ease-out duration-200 delay-100 motion-reduce:transition-opacity"
         x-transition:enter-start="opacity-0 scale-y-0"
         x-transition:enter-end="opacity-100 scale-y-100"
         class="flex max-w-lg flex-col gap-4 overflow-hidden rounded-radius border border-outline bg-surface text-on-surface dark:border-outline-dark dark:bg-surface-dark-alt dark:text-on-surface-dark">
        <!-- Dialog Header -->
        <div
            class="flex items-center justify-between border-b border-outline bg-surface-alt/60 p-4 dark:border-outline-dark dark:bg-surface-dark/20">
            <h3 id="defaultModalTitle"
                class="font-semibold tracking-wide text-on-surface-strong dark:text-on-surface-dark-strong">
                Crear Nueva Obligación
            </h3>
            <button x-on:click="$wire.closeModal" aria-label="close modal">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" aria-hidden="true" stroke="currentColor"
                     fill="none" stroke-width="1.4" class="w-5 h-5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>
        <!-- Dialog Body -->
        <div class=" px-4 py-4 w-80">
            <form class="flex flex-col gap-4">
                <div class="flex w-full max-w-xs flex-col gap-1 text-on-surface dark:text-on-surface-dark">
                    <label for="name" class="w-fit pl-0.5 text-sm">
                        Título
                    </label>
                    <input id="name" type="text"
                           class="w-full rounded-radius border border-outline bg-surface-alt px-2 py-2 text-sm focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary disabled:cursor-not-allowed disabled:opacity-75 dark:border-outline-dark dark:bg-surface-dark-alt/50 dark:focus-visible:outline-primary-dark"
                           name="name"
                           wire:model="form.name"
                           placeholder="Alquiler apartamento, Seguro de Auto..."
                           autocomplete="name"/>
                    <div>
                        @error('form.name') <span class="error">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div class="flex w-full max-w-md flex-col gap-1 text-on-surface dark:text-on-surface-dark">
                    <label for="description" class="w-fit pl-0.5 text-sm">
                        Descripción
                    </label>
                    <textarea id="description"
                              class="w-full rounded-radius border border-outline bg-surface-alt px-2.5 py-2 text-sm focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary disabled:cursor-not-allowed disabled:opacity-75 dark:border-outline-dark dark:bg-surface-dark-alt/50 dark:focus-visible:outline-primary-dark"
                              rows="3"
                              wire:model="form.description"
                              placeholder="Pago mensual de alquiler de apartamento..."></textarea>
                    <div>
                        @error('form.description') <span class="error">{{ $message }}</span> @enderror
                    </div>
                </div>
                <div class="flex items-center justify-between gap-3">
                    <div class="flex w-full max-w-xs flex-col gap-1 text-on-surface dark:text-on-surface-dark">
                        <label for="amount"
                               class="w-fit pl-0.5 text-sm">
                            Monto
                        </label>
                        <input
                            type="number"
                            id="amount"
                            step="0.01"
                            placeholder="0.00"
                            name="amount"
                            autocomplete="amount"
                            required
                            wire:model="form.amount"
                            class="w-full rounded-radius border border-outline bg-surface-alt px-2 py-2 text-sm focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary disabled:cursor-not-allowed disabled:opacity-75 dark:border-outline-dark dark:bg-surface-dark-alt/50 dark:focus-visible:outline-primary-dark"
                        />
                        <div>
                            @error('form.amount') <span class="error">{{ $message }}</span> @enderror
                        </div>
                    </div>
                    <div class="flex w-full max-w-xs flex-col gap-1 text-on-surface dark:text-on-surface-dark">
                        <label for="limitDay"
                               class="w-fit pl-0.5 text-sm">
                            Día de Vencimiento
                        </label>
                        <input
                            type="number"
                            id="limitDay"
                            placeholder="5"
                            name="limitDay"
                            autocomplete="limitDay"
                            required
                            wire:model="form.limit_day"
                            class="w-full rounded-radius border border-outline bg-surface-alt px-2 py-2 text-sm focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary disabled:cursor-not-allowed disabled:opacity-75 dark:border-outline-dark dark:bg-surface-dark-alt/50 dark:focus-visible:outline-primary-dark"
                        />
                        <div>
                            @error('form.limit_day') <span class="error">{{ $message }}</span> @enderror
                        </div>
                    </div>
                </div>
                <div class="flex w-full max-w-xs flex-col gap-1 text-on-surface dark:text-on-surface-dark">
                    <label for="category" class="w-fit pl-0.5 text-sm">Categoría</label>
                    <input id="category"
                           type="text"
                           required
                           class="w-full rounded-radius border border-outline bg-surface-alt px-2 py-2 text-sm focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary disabled:cursor-not-allowed disabled:opacity-75 dark:border-outline-dark dark:bg-surface-dark-alt/50 dark:focus-visible:outline-primary-dark"
                           name="category"
                           placeholder="Vivienda, Servicios, Seguros, etc."
                           autocomplete="category"
                           wire:model="form.category"
                    />
                    <div>
                        @error('form.category') <span class="error">{{ $message }}</span> @enderror
                    </div>
                </div>
            </form>
        </div>
        <!-- Dialog Footer -->
        <div
            class="flex flex-col-reverse justify-between gap-2 border-t border-outline bg-surface-alt/60 p-4 dark:border-outline-dark dark:bg-surface-dark/20 sm:flex-row sm:items-center md:justify-end">
            <button x-on:click="$wire.closeModal" type="button"
                    class="whitespace-nowrap rounded-radius px-4 py-2 text-center text-sm font-medium tracking-wide text-on-surface transition hover:opacity-75 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary active:opacity-100 active:outline-offset-0 dark:text-on-surface-dark dark:focus-visible:outline-primary-dark">
                Cancelar
            </button>
            <button type="button" wire:click="{{ $obligation ? 'edit' : 'save'}}"
                    class="whitespace-nowrap rounded-radius border border-primary dark:border-primary-dark bg-primary px-4 py-2 text-center text-sm font-medium tracking-wide text-on-primary transition hover:opacity-75 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary active:opacity-100 active:outline-offset-0 dark:bg-primary-dark dark:text-on-primary-dark dark:focus-visible:outline-primary-dark">
                {{ $obligation ? 'Actualizar' : 'Crear Obligación'}}
            </button>
        </div>
    </div>
</div>
