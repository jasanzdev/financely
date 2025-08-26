<div x-cloak class="flex justify-center items-center h-full w-full">
    <article
        class="group flex w-3/4 rounded-radius flex-col overflow-hidden border border-outline bg-surface-alt text-on-surface dark:border-outline-dark dark:bg-surface-dark-alt dark:text-on-surface-dark">
        <div class="flex flex-col gap-4 p-6 ">
            <h1 class="font-bold text-2xl"> Modificar transacción </h1>

            <form wire:submit="edit" class="space-y-6 mt-4">
                <div class="space-y-6">
                    <div class="flex flex-col gap-2">
                        <label
                            class="block font-semibold text-gray-700 dark:text-gray-200">Tipo</label>
                        <div
                            class="flex items-center justify-start gap-2 font-medium text-on-surface has-disabled:opacity-75 dark:text-on-surface-dark">
                            <input id="radioIncome"
                                   type="radio"
                                   class="before:content[''] relative h-4 w-4 appearance-none rounded-full border border-outline bg-surface-alt before:invisible before:absolute before:left-1/2 before:top-1/2 before:h-1.5 before:w-1.5 before:-translate-x-1/2 before:-translate-y-1/2 before:rounded-full before:bg-on-primary checked:border-primary checked:bg-primary checked:before:visible focus:outline-2 focus:outline-offset-2 focus:outline-outline-strong checked:focus:outline-primary disabled:cursor-not-allowed dark:border-outline-dark dark:bg-surface-dark-alt dark:before:bg-on-primary-dark dark:checked:border-primary-dark dark:checked:bg-primary-dark dark:focus:outline-outline-dark-strong dark:checked:focus:outline-primary-dark"
                                   name="type"
                                   value="income"
                                   disabled
                                   wire:model="form.type"
                                {{ $form->type === 'income' ? 'checked' : '' }}
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
                                   disabled
                                   wire:model="form.type"
                                {{ $form->type === 'expense' ? 'checked' : '' }}
                            >
                            <label for="radioExpense" class="text-sm text-danger">Gasto</label>
                        </div>
                        <div>
                            @error('form.type') <span class="error">{{ $message }}</span> @enderror
                        </div>
                    </div>
                    <div>
                        <label for="defaultToggle" class="inline-flex items-center gap-3">
                            <input id="defaultToggle" type="checkbox" class="peer sr-only" role="switch"
                                   wire:model.live="form.state"
                                   checked/>
                            <span
                                class="trancking-wide text-sm font-medium text-on-surface peer-checked:text-on-surface-strong peer-disabled:cursor-not-allowed peer-disabled:opacity-70 dark:text-on-surface-dark dark:peer-checked:text-on-surface-dark-strong">
                            Pagado
                        </span>
                            <div
                                class="relative h-6 w-11 after:h-5 after:w-5 peer-checked:after:translate-x-5 rounded-full border border-outline bg-surface-alt after:absolute after:bottom-0 after:left-[0.0625rem] after:top-0 after:my-auto after:rounded-full after:bg-on-surface after:transition-all after:content-[''] peer-checked:bg-primary peer-checked:after:bg-on-primary peer-focus:outline-2 peer-focus:outline-offset-2 peer-focus:outline-outline-strong peer-focus:peer-checked:outline-primary peer-active:outline-offset-0 peer-disabled:cursor-not-allowed peer-disabled:opacity-70 dark:border-outline-dark dark:bg-surface-dark-alt dark:after:bg-on-surface-dark dark:peer-checked:bg-primary-dark dark:peer-checked:after:bg-on-primary-dark dark:peer-focus:outline-outline-dark-strong dark:peer-focus:peer-checked:outline-primary-dark"
                                aria-hidden="true"></div>
                        </label>

                        <div>
                            @error('form.state') <span class="error">{{ $message }}</span> @enderror
                        </div>
                    </div>
                    <div class="mt-6" x-data="{ form: { state: @entangle('form.state').live } }" x-show="!form.state">
                        <label for="datepicker"
                               class="block mb-2 font-medium text-gray-700 dark:text-gray-200">Fecha de pago*</label>
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
                                defaultDate: @entangle('form.expected_payment_date').defer
                                });
                            "
                            >
                                <input
                                    x-ref="datePick"
                                    type="text"
                                    wire:model="form.expected_payment_date"
                                    placeholder="Selecciona la fecha"
                                    class="w-1/2 text-gray-700 dark:text-gray-200 rounded-radius border border-outline bg-surface-alt px-9 py-2 text-sm focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary disabled:cursor-not-allowed disabled:opacity-75 dark:border-outline-dark dark:bg-surface-dark-alt/50 dark:focus-visible:outline-primary-dark"
                                >
                            </div>
                        </div>
                        <div>
                            @error('form.expected_payment_date') <span class="error">{{ $message }}</span> @enderror
                        </div>
                    </div>
                    <div>
                        <label for="amount" class="w-fit pl-0.5 text-sm">Monto</label>
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
                        <label for="description" class="w-fit pl-0.5 text-sm">Descripción</label>
                        <textarea
                            id="description"
                            class="w-full text-gray-700 dark:text-gray-200 rounded-radius border border-outline bg-surface-alt px-2.5 py-2 text-sm focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary disabled:cursor-not-allowed disabled:opacity-75 dark:border-outline-dark dark:bg-surface-dark-alt/50 dark:focus-visible:outline-primary-dark"
                            rows="3"
                            name="description"
                            placeholder="Pago de renta, Compra de cigarros..."
                            wire:model="form.description"
                        >

                            </textarea>
                        <div>
                            @error('form.description') <span class="error">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div class="mt-6">
                        <label for="datepicker"
                               class="block mb-2 font-medium text-gray-700 dark:text-gray-200">Fecha</label>
                        <div class="custom-datepicker relative w-full">
                            <div class="absolute inset-y-0 start-0 flex items-center ps-3 pointer-events-none">
                                <svg class="w-4 h-4 text-gray-300" aria-hidden="true"
                                     xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                                    <path
                                        d="M20 4a2 2 0 0 0-2-2h-2V1a1 1 0 0 0-2 0v1h-3V1a1 1 0 0 0-2 0v1H6V1a1 1 0 0 0-2 0v1H2a2 2 0 0 0-2 2v2h20V4ZM0 18a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V8H0v10Zm5-8h10a1 1 0 0 1 0 2H5a1 1 0 0 1 0-2Z"/>
                                </svg>
                            </div>

                            <div
                                wire:ignore
                                x-data
                                x-init="
                                flatpickr($refs.input, {
                                dateFormat: 'Y-m-d',
                                maxDate: 'today',
                                defaultDate: @entangle('form.date').defer
                                 });
                                "
                            >
                                <input
                                    x-ref="input"
                                    type="text"
                                    wire:model="form.date"
                                    placeholder="Selecciona la fecha"
                                    class="w-1/2 text-gray-700 dark:text-gray-200 rounded-radius border border-outline bg-surface-alt px-9 py-2 text-sm focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary disabled:cursor-not-allowed disabled:opacity-75 dark:border-outline-dark dark:bg-surface-dark-alt/50 dark:focus-visible:outline-primary-dark"
                                >
                            </div>
                        </div>
                        <div>
                            @error('form.date') <span class="error">{{ $message }}</span> @enderror
                        </div>

                    </div>

                    <div class="mt-6">
                        <div class="flex justify-center items-center gap-3">
                            <button type="submit" wire:navigate.prevent
                                    class="bg-gray-950 p-3 rounded-lg text-neutral-50 text-center sm:text-sm hover:bg-gray-700 transition-colors ease-in-out">
                                Guardar
                                Transacción
                            </button>
                            <a href="{{ url()->previous() ?? route('dashboard') }}" wire:navigate.prevent
                               class="bg-gray-100 p-2 px-6 text-center rounded-lg shadow border border-gray-300 text-gray-900 text-sm focus:ring-neutral-500
                           focus:border-neutral-500 hover:bg-gray-400 transition-colors ease-in-out ">
                                Cancelar
                            </a>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </article>
</div>
