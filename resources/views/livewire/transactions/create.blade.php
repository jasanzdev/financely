<div>
    <div class="flex flex-col p-3 mx-4 space-y-1">
        <h1 class="font-bold text-xl"> Agregar nueva transacción </h1>
        <p class="text-gray-700 dark:text-gray-300">
            Registra un nuevo gasto o ingreso
        </p>

        <form wire:submit="save" class="space-y-6 mt-4">
            <div class="space-y-6">
                <div class="flex flex-col gap-2">
                    <label
                        class="block font-semibold text-gray-700 dark:text-gray-200">Tipo de Transacción*</label>
                    <div
                        class="flex items-center justify-start gap-2 font-medium text-on-surface has-disabled:opacity-75 dark:text-on-surface-dark">
                        <input id="radioIncome"
                               type="radio"
                               class="before:content[''] relative h-4 w-4 appearance-none rounded-full border border-outline bg-surface-alt
                               before:invisible before:absolute before:left-1/2 before:top-1/2 before:h-1.5 before:w-1.5 before:-translate-x-1/2
                               before:-translate-y-1/2 before:rounded-full before:bg-on-primary checked:border-primary checked:bg-primary
                               checked:before:visible focus:outline-2 focus:outline-offset-2 focus:outline-outline-strong checked:focus:outline-primary
                               disabled:cursor-not-allowed dark:border-outline-dark dark:bg-surface-dark-alt dark:before:bg-on-primary-dark
                               dark:checked:border-primary-dark dark:checked:bg-primary-dark dark:focus:outline-outline-dark-strong
                               dark:checked:focus:outline-primary-dark"
                               name="type"
                               value="income"
                               wire:model="form.type"
                        >
                        <label for="radioIncome" class="text-sm text-success">Ingreso</label>
                    </div>
                    <div
                        class="flex items-center justify-start gap-2 font-medium text-on-surface has-disabled:opacity-75 dark:text-on-surface-dark">
                        <input id="radioExpense"
                               type="radio"
                               class="before:content[''] relative h-4 w-4 appearance-none rounded-full border border-outline bg-surface-alt before:invisible before:absolute before:left-1/2 before:top-1/2 before:h-1.5 before:w-1.5 before:-translate-x-1/2 before:-translate-y-1/2 before:rounded-full before:bg-on-primary checked:border-primary checked:bg-primary checked:before:visible focus:outline-2 focus:outline-offset-2 focus:outline-outline-strong checked:focus:outline-primary disabled:cursor-not-allowed dark:border-outline-dark dark:bg-surface-dark-alt dark:before:bg-on-primary-dark dark:checked:border-primary-dark dark:checked:bg-primary-dark dark:focus:outline-outline-dark-strong dark:checked:focus:outline-primary-dark"
                               name="type"
                               value="expense"
                               wire:model="form.type"
                        >
                        <label for="radioExpense" class="text-sm text-danger">Gasto</label>
                    </div>
                    <div>
                        @error('form.type') <span class="error">{{ $message }}</span> @enderror
                    </div>
                </div>
                <div>
                    <label for="amount" class="w-fit pl-0.5 text-sm">Monto*</label>
                    <div
                        class="relative flex w-full mt-2 max-w-xs flex-col gap-1 text-on-surface dark:text-on-surface-dark">
                        <span
                            class="absolute top-1/2 left-3 text-sm transform -translate-y-1/2 pointer-events-none">$</span>
                        <input
                            type="number"
                            id="amount"
                            step="0.01"
                            placeholder="0.00"
                            name="amount"
                            class="w-full text-gray-700 dark:text-gray-200 rounded-radius border border-outline bg-surface-alt px-7 py-2 text-sm focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary disabled:cursor-not-allowed disabled:opacity-75 dark:border-outline-dark dark:bg-surface-dark-alt/50 dark:focus-visible:outline-primary-dark"
                            autocomplete="amount"
                            required
                            wire:model="form.amount"
                        />
                    </div>
                    <div>
                        @error('form.amount') <span class="error">{{ $message }}</span> @enderror
                    </div>
                </div>
                <div class="flex w-full max-w-md flex-col gap-1 text-on-surface dark:text-on-surface-dark">
                    <label for="description" class="w-fit pl-0.5 text-sm">Descripción*</label>
                    <textarea
                        id="description"
                        class="w-full text-gray-700 dark:text-gray-200 rounded-radius border border-outline bg-surface-alt px-2.5 py-2 text-sm focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary disabled:cursor-not-allowed disabled:opacity-75 dark:border-outline-dark dark:bg-surface-dark-alt/50 dark:focus-visible:outline-primary-dark"
                        rows="3"
                        required
                        name="description"
                        placeholder="Pago de renta, Compra de cigarros..."
                        wire:model="form.description"
                    >

                            </textarea>
                    <div>
                        @error('form.description') <span class="error">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div x-data="{modalIsOpen: false}">
                    <div class="mb-1">
                        <label for="categories"
                               class="pl-0.5 text-sm text-on-surface dark:text-on-surface-dark">
                            Categoría*
                        </label>
                    </div>

                    <div
                        class="flex gap-3 text-on-surface dark:text-on-surface-dark top-2">
                        <div
                            class="relative w-full max-w-xs gap-1">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"
                                 class="absolute pointer-events-none right-3 self-center size-5">
                                <path fill-rule="evenodd"
                                      d="M5.22 8.22a.75.75 0 0 1 1.06 0L10 11.94l3.72-3.72a.75.75 0 1 1 1.06 1.06l-4.25 4.25a.75.75 0 0 1-1.06 0L5.22 9.28a.75.75 0 0 1 0-1.06Z"
                                      clip-rule="evenodd"/>
                            </svg>
                            <select id="categories" name="category" wire:model="form.category_id"
                                    class="w-full appearance-none rounded-radius border border-outline bg-surface-alt px-4 py-2 text-sm focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary disabled:cursor-not-allowed disabled:opacity-75 dark:border-outline-dark dark:bg-surface-dark-alt/50 dark:focus-visible:outline-primary-dark">
                                <option selected>Categorías</option>
                                @foreach($categories as $category)
                                    <option value="{{$category->id}}">{{$category->category}}</option>
                                @endforeach
                            </select>
                        </div>
                        <button x-on:click="modalIsOpen=true" type="button"
                                class="flex items-center justify-center whitespace-nowrap rounded-radius border border-primary dark:border-primary-dark bg-primary w-1/6 text-center text-sm font-medium tracking-wide text-on-primary transition hover:opacity-75 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary active:opacity-100 active:outline-offset-0 dark:bg-primary-dark dark:text-on-primary-dark dark:focus-visible:outline-primary-dark">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                 stroke-width="1.5" stroke="currentColor" class="size-6">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/>
                            </svg>
                        </button>
                        <livewire:category.create/>
                    </div>
                    <div>
                        @error('form.category_id') <span class="error">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div class="mt-6">
                    <label for="datepicker"
                           class="block mb-2 font-medium text-gray-700 dark:text-gray-200">Fecha*</label>
                    <div class="custom-datepicker relative w-full">
                        <div class="absolute inset-y-0 start-0 flex items-center ps-3 pointer-events-none">
                            <svg class="w-4 h-4 text-gray-700 dark:text-gray-200" aria-hidden="true"
                                 xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                                <path
                                    d="M20 4a2 2 0 0 0-2-2h-2V1a1 1 0 0 0-2 0v1h-3V1a1 1 0 0 0-2 0v1H6V1a1 1 0 0 0-2 0v1H2a2 2 0 0 0-2 2v2h20V4ZM0 18a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V8H0v10Zm5-8h10a1 1 0 0 1 0 2H5a1 1 0 0 1 0-2Z"/>
                            </svg>
                        </div>

                        <div
                            wire:ignore
                            x-data
                            x-init="
                                flatpickr($refs.datePick, {
                                dateFormat: 'Y-m-d',
                                maxDate: 'today',
                                defaultDate: @entangle('form.date').defer
                                });
                            "
                        >
                            <input
                                x-ref="datePick"
                                type="text"
                                wire:model="form.date"
                                placeholder="Selecciona la fecha"
                                class="w-full text-gray-700 dark:text-gray-200 rounded-radius border border-outline bg-surface-alt px-9 py-2 text-sm focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary disabled:cursor-not-allowed disabled:opacity-75 dark:border-outline-dark dark:bg-surface-dark-alt/50 dark:focus-visible:outline-primary-dark"
                            >
                        </div>
                    </div>
                    <div>
                        @error('form.date') <span class="error">{{ $message }}</span> @enderror
                    </div>

                </div>

                <div class="mt-6">
                    <div class="flex justify-center items-center gap-3">
                        <button type="submit"
                                class="bg-gray-950 p-3 rounded-lg text-neutral-50 text-center sm:text-sm hover:bg-gray-700 transition-colors ease-in-out">
                            Guardar
                            Transacción
                        </button>
                        <button type="reset"
                                class="bg-gray-100 p-2.5 px-6 text-center rounded-lg shadow border border-gray-300 text-gray-900 text-sm focus:ring-neutral-500
                           focus:border-neutral-500 hover:bg-gray-400 transition-colors ease-in-out ">
                            Cancelar
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>

</div>
