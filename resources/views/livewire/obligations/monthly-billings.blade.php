<div class="p-4 sm:p-6 lg:p-8" x-data="{ obligationModalIsOpen: @entangle('obligationModalIsOpen').live }">
    @if(session('message'))
        <x-app.alert message="{{ session('message') }}"/>
    @endif
    <div class="max-w-7xl mx-auto">
        <div
            class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 sm:gap-6 mb-6 sm:mb-8 sm:px-6">
            <div>
                <h1 class="text-2xl sm:text-3xl lg:text-4xl font-bold text-gray-900 dark:text-white">
                    Obligaciones Mensuales
                </h1>
                <p class="mt-1 text-sm sm:text-base text-gray-600 dark:text-gray-300">
                    Gestiona tus pagos y compromisos mensuales
                </p>
            </div>
            <button type="button" wire:click="openModal"
                    class="inline-flex justify-center items-center gap-2 whitespace-nowrap bg-neutral-900 rounded-lg border border-surface-alt dark:border-surface-dark-alt px-4 py-2 sm:px-11 sm:py-2.5 text-sm font-medium tracking-wide text-neutral-100 transition hover:opacity-75 text-center focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-surface-alt active:opacity-100 active:outline-offset-0 disabled:opacity-75 disabled:cursor-not-allowed dark:bg-surface-dark-alt dark:text-on-surface-dark-strong dark:focus-visible:outline-surface-dark-alt">
                <svg aria-hidden="true" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                     class="size-5" fill="currentColor">
                    <path fill-rule="evenodd"
                          d="M12 3.75a.75.75 0 01.75.75v6.75h6.75a.75.75 0 010 1.5h-6.75v6.75a.75.75 0 01-1.5 0v-6.75H4.5a.75.75 0 010-1.5h6.75V4.5a.75.75 0 01.75-.75z"
                          clip-rule="evenodd"/>
                </svg>
                Agregar
            </button>

        </div>

        <!-- Categories List -->
        <ul class="space-y-4">
            <li wire:key="obligation-resume"
                class="p-4 sm:p-5 rounded-xl bg-gray-50 dark:bg-neutral-800 border border-gray-200 dark:border-neutral-700 shadow-sm hover:shadow-md transition-all duration-200">
                <div class="flex items-center w-full sm:w-auto mb-3 sm:mb-0">
                    <div class="flex flex-col gap-2">
                        <p class="text-base sm:text-xl font-medium text-on-surface dark:text-on-surface-dark line-clamp-2">
                            Total Mensual
                        </p>

                        <span
                            class="px-2 mt-3 text-3xl font-bold">
                                $ {{ $totalObligation }}
                            </span>
                        <span class="text-xs sm:text-sm text-gray-500 dark:text-gray-400">
                                {{ $countObligation }} obligaciones activas
                        </span>
                    </div>
                </div>
            </li>
            @forelse($obligations as $obligation)
                <li wire:key="obligation-{{$obligation->id}}"
                    class="p-4 sm:p-5 rounded-xl bg-gray-50 dark:bg-neutral-800 border border-gray-200 dark:border-neutral-700 shadow-sm hover:shadow-md transition-all duration-200">
                    <div class="flex justify-between w-full sm:w-auto mb-3 sm:mb-0">
                        <div class="flex flex-col gap-3">
                            <div>
                                <p class="text-base sm:text-lg font-medium text-on-surface dark:text-on-surface-dark line-clamp-2">
                                    {{ $obligation->name }}
                                </p>
                            </div>
                            <div>
                                <p class="text-sm text-neutral-600 dark:text-neutral-400 line-clamp-2">
                                    {{ $obligation->description }}
                                </p>
                            </div>
                            <div class="flex flex-wrap items-center gap-5">
                                <span class="text-xs sm:text-sm text-gray-500 dark:text-gray-400">
                                    $ {{ $obligation->amount }}
                                </span>
                                <span class="flex gap-1 text-xs sm:text-sm text-gray-500 dark:text-gray-400">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"
                                         class="size-5">
                                        <path
                                            d="M12.75 12.75a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0ZM7.5 15.75a.75.75 0 1 0 0-1.5.75.75 0 0 0 0 1.5ZM8.25 17.25a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0ZM9.75 15.75a.75.75 0 1 0 0-1.5.75.75 0 0 0 0 1.5ZM10.5 17.25a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0ZM12 15.75a.75.75 0 1 0 0-1.5.75.75 0 0 0 0 1.5ZM12.75 17.25a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0ZM14.25 15.75a.75.75 0 1 0 0-1.5.75.75 0 0 0 0 1.5ZM15 17.25a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0ZM16.5 15.75a.75.75 0 1 0 0-1.5.75.75 0 0 0 0 1.5ZM15 12.75a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0ZM16.5 13.5a.75.75 0 1 0 0-1.5.75.75 0 0 0 0 1.5Z"/>
                                        <path fill-rule="evenodd"
                                              d="M6.75 2.25A.75.75 0 0 1 7.5 3v1.5h9V3A.75.75 0 0 1 18 3v1.5h.75a3 3 0 0 1 3 3v11.25a3 3 0 0 1-3 3H5.25a3 3 0 0 1-3-3V7.5a3 3 0 0 1 3-3H6V3a.75.75 0 0 1 .75-.75Zm13.5 9a1.5 1.5 0 0 0-1.5-1.5H5.25a1.5 1.5 0 0 0-1.5 1.5v7.5a1.5 1.5 0 0 0 1.5 1.5h13.5a1.5 1.5 0 0 0 1.5-1.5v-7.5Z"
                                              clip-rule="evenodd"/>
                                    </svg>
                                    Dia {{ $obligation->limit_day }}
                                </span>
                                <span
                                    class="bg-neutral-100 dark:bg-neutral-700 border border-neutral-300 dark:border-neutral-600 rounded-full px-2 py-0.5 text-xs">
                                    {{ $obligation->category }}
                                </span>
                            </div>
                        </div>
                        <div
                            class="flex items-start justify-between w-full sm:w-auto sm:justify-end gap-3 mt-2 sm:mt-0">
                            <button wire:click.prevent="changeStatus('{{ $obligation->id }}')"
                                    wire:confirm="Estás seguro que desea {{ $obligation->is_active ? 'desactivar': 'activar'}} esta obligación?"
                                    class="py-1 px-4 sm:px-3 sm:py-2 text-xs border border-neutral-300 dark:border-neutral-500 hover:bg-neutral-200 hover:dark:bg-neutral-700 text-neutral-950 dark:text-white shadow-lg rounded-lg cursor-pointer transition-colors duration-200"
                                    type="button"
                                    aria-label="Actived transaction">
                                {{ $obligation->is_active ? 'Desactivar' : 'Activar' }}
                            </button>

                            <button wire:click.prevent="openModal('{{ $obligation->id }}')"
                                    class="p-1 sm:p-2 border border-neutral-300 dark:border-neutral-500 hover:bg-neutral-200 hover:dark:bg-neutral-700 text-white shadow-lg rounded cursor-pointer transition-colors duration-200"
                                    type="button"
                                    aria-label="Edit transaction">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"
                                     class="text-black size-4">
                                    <path
                                        d="M21.731 2.269a2.625 2.625 0 0 0-3.712 0l-1.157 1.157 3.712 3.712 1.157-1.157a2.625 2.625 0 0 0 0-3.712ZM19.513 8.199l-3.712-3.712-8.4 8.4a5.25 5.25 0 0 0-1.32 2.214l-.8 2.685a.75.75 0 0 0 .933.933l2.685-.8a5.25 5.25 0 0 0 2.214-1.32l8.4-8.4Z"/>
                                    <path
                                        d="M5.25 5.25a3 3 0 0 0-3 3v10.5a3 3 0 0 0 3 3h10.5a3 3 0 0 0 3-3V13.5a.75.75 0 0 0-1.5 0v5.25a1.5 1.5 0 0 1-1.5 1.5H5.25a1.5 1.5 0 0 1-1.5-1.5V8.25a1.5 1.5 0 0 1 1.5-1.5h5.25a.75.75 0 0 0 0-1.5H5.25Z"/>
                                </svg>

                            </button>

                            <button wire:click.prevent="delete('{{ $obligation->id }}')"
                                    wire:confirm="Estás seguro que desea eliminar la transacción?"
                                    class="p-1 sm:p-2 border border-neutral-300 dark:border-neutral-500 hover:bg-neutral-200 hover:dark:bg-neutral-700 text-white shadow-lg rounded cursor-pointer transition-colors duration-200"
                                    type="button"
                                    aria-label="Delete transaction">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                     stroke-width="1.5"
                                     stroke="currentColor" class="text-danger size-4">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                          d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0"/>
                                </svg>
                            </button>
                        </div>
                    </div>
                </li>
            @empty
                <li class="text-center py-6 text-gray-500 dark:text-gray-400 text-sm sm:text-base">
                    No hay obligaciones registradas actualmente
                </li>
            @endforelse
        </ul>
        <x-app.obligation-modal :obligation="$form->obligation"/>
    </div>
</div>
