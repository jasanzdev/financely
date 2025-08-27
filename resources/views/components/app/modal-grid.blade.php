@props([
    'modalType',
    'receivableTransactions',
    'payableTransactions'
])


<div x-cloak
     x-show="modalIsOpen"
     x-transition.opacity.duration.200ms
     x-trap.inert.noscroll="modalIsOpen"
     x-on:keydown.esc.window="modalIsOpen = false"
     x-on:click.self="modalIsOpen = false"
     class="fixed inset-0 z-30 flex w-full items-start justify-center bg-black/20 p-4 pb-8 backdrop-blur-md lg:p-8"
     role="dialog"
     aria-modal="true"
     aria-labelledby="defaultModalTitle">
    <!-- Modal Dialog -->
    <div x-show="modalIsOpen"
         x-transition:enter="transition ease-out duration-200 delay-100 motion-reduce:transition-opacity"
         x-transition:enter-start="opacity-0 scale-50"
         x-transition:enter-end="opacity-100 scale-100"
         class="flex max-w-xl max-h-full flex-col gap-4 overflow-hidden rounded-radius border border-outline bg-surface text-on-surface dark:border-outline-dark dark:bg-surface-dark-alt dark:text-on-surface-dark">
        <!-- Dialog Header -->
        <div
            class="flex items-center justify-between border-b border-outline bg-surface-alt/60 p-4 dark:border-outline-dark dark:bg-surface-dark/20">
            <h3 id="defaultModalTitle"
                class="font-semibold tracking-wide text-on-surface-strong dark:text-on-surface-dark-strong">
                {{ $modalType === 'receivable' ? 'Cuentas por Cobrar' : 'Cuentas por Pagar' }}
            </h3>
            <button x-on:click="modalIsOpen = false" aria-label="close modal">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" aria-hidden="true"
                     stroke="currentColor"
                     fill="none" stroke-width="1.4" class="w-5 h-5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>
        <!-- Dialog Body -->
        <div class="px-4 overflow-y-auto">
            <ul class="space-y-3 mt-3 sm:mt-4">
                @forelse($modalType === 'receivable' ? $receivableTransactions : $payableTransactions as $transaction)
                    <li class="flex flex-col sm:flex-row justify-between items-start sm:items-center p-3 sm:p-4 rounded-lg bg-surface-alt shadow-sm border dark:border-neutral-700 dark:bg-surface-dark-alt/50">
                        <div class="flex flex-col sm:flex-row items-start sm:items-center flex-1">
                            <div class="flex items-center w-full sm:w-auto mb-3 sm:mb-0">
                                <div
                                    class="rounded-full p-2 mr-3 {{ $transaction->type === 'income' ? 'bg-green-100 dark:bg-green-900/30' : 'bg-red-100 dark:bg-red-900/30' }}">
                                    @if($transaction->type === 'income')
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor"
                                             viewBox="0 0 24 24"
                                             class="w-5 h-5 text-green-700 dark:text-green-400">
                                            <path fill-rule="evenodd"
                                                  d="M15.22 6.268a.75.75 0 0 1 .968-.431l5.942 2.28a.75.75 0 0 1 .431.97l-2.28 5.94a.75.75 0 1 1-1.4-.537l1.63-4.251-1.086.484a11.2 11.2 0 0 0-5.45 5.173.75.75 0 0 1-1.199.19L9 12.312l-6.22 6.22a.75.75 0 0 1-1.06-1.061l6.75-6.75a.75.75 0 0 1 1.06 0l3.606 3.606a12.695 12.695 0 0 1 5.68-4.974l1.086-.483-4.251-1.632a.75.75 0 0 1-.432-.97Z"
                                                  clip-rule="evenodd"/>
                                        </svg>
                                    @else
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor"
                                             viewBox="0 0 24 24"
                                             class="w-5 h-5 text-red-700 dark:text-red-400">
                                            <path fill-rule="evenodd"
                                                  d="M1.72 5.47a.75.75 0 0 1 1.06 0L9 11.69l3.756-3.756a.75.75 0 0 1 .985-.066 12.698 12.698 0 0 1 4.575 6.832l.308 1.149 2.277-3.943a.75.75 0 1 1 1.299.75l-3.182 5.51a.75.75 0 0 1-1.025.275l-5.511-3.181a.75.75 0 0 1 .75-1.3l3.943 2.277-.308-1.149a11.194 11.194 0 0 0-3.528-5.617l-3.809 3.81a.75.75 0 0 1-1.06 0L1.72 6.53a.75.75 0 0 1 0-1.061Z"
                                                  clip-rule="evenodd"/>
                                        </svg>
                                    @endif
                                </div>
                                <div class="flex-1">
                                    <p class="text-base sm:text-lg font-medium text-on-surface dark:text-on-surface-dark line-clamp-2">
                                        {{ $transaction->description }}
                                    </p>

                                    <div class="flex flex-wrap items-center gap-2 mt-1">
                            <span
                                class="bg-neutral-100 dark:bg-neutral-700 border border-neutral-300 dark:border-neutral-600 rounded-full px-2 py-0.5 text-xs">
                                {{ $transaction->category->category }}
                            </span>

                                        <span class="text-xs sm:text-sm text-gray-500 dark:text-gray-400">
                                Fecha de pago -> {{$transaction->expected_payment_date}}
                            </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div
                            class="flex items-center justify-between w-full sm:w-auto sm:justify-end gap-3 mt-2 sm:mt-0">
                            <div
                                class="text-sm sm:text-base font-semibold {{ $transaction->type === 'income' ? 'text-green-700 dark:text-green-400' : 'text-red-700 dark:text-red-400' }}">
                                <span>{{ $transaction->type === 'income' ? '+' : '-' }} ${{ number_format($transaction->amount, 2, ',', '.') }}</span>
                            </div>
                            <label for="toggleGrid" class="inline-flex items-center gap-3">
                                <input id="toggleGrid" type="checkbox" class="peer sr-only" role="switch"
                                       wire:click.prevent="changeStatus('{{ $transaction->id }}', '')"
                                       wire:confirm="Desea marcar como pagada esta transacción?"
                                />
                                <span
                                    class="trancking-wide text-sm font-medium text-on-surface peer-checked:text-on-surface-strong peer-disabled:cursor-not-allowed peer-disabled:opacity-70 dark:text-on-surface-dark dark:peer-checked:text-on-surface-dark-strong">
                                    {{ $modalType === 'receivable' ? 'Cobrar' : 'Pagar' }}
                                </span>
                                <div
                                    class="relative h-6 w-11 after:h-5 after:w-5 peer-checked:after:translate-x-5 rounded-full border border-outline bg-surface-alt after:absolute after:bottom-0 after:left-[0.0625rem] after:top-0 after:my-auto after:rounded-full after:bg-on-surface after:transition-all after:content-[''] peer-checked:bg-primary peer-checked:after:bg-on-primary peer-focus:outline-2 peer-focus:outline-offset-2 peer-focus:outline-outline-strong peer-focus:peer-checked:outline-primary peer-active:outline-offset-0 peer-disabled:cursor-not-allowed peer-disabled:opacity-70 dark:border-outline-dark dark:bg-surface-dark-alt dark:after:bg-on-surface-dark dark:peer-checked:bg-primary-dark dark:peer-checked:after:bg-on-primary-dark dark:peer-focus:outline-outline-dark-strong dark:peer-focus:peer-checked:outline-primary-dark"
                                    aria-hidden="true"></div>
                            </label>

                        </div>
                    </li>
                @empty
                    <li class="text-center py-4 text-gray-500 dark:text-gray-400">
                        {{$modalType === 'receivable' ? 'No tiene cuentas por cobrar' : 'No tiene cuentas por pagar'}}
                    </li>
                @endforelse
            </ul>
        </div>
        <!-- Dialog Footer -->
        <div
            class="flex flex-col-reverse justify-between gap-2 border-t border-outline bg-surface-alt/60 p-4 dark:border-outline-dark dark:bg-surface-dark/20 sm:flex-row sm:items-center md:justify-end">
            <a href="{{ route('pending-transactions') }}" wire:navigate
               class="whitespace-nowrap rounded-radius px-4 py-2 text-center text-sm font-medium tracking-wide
                text-on-surface transition hover:opacity-75 focus-visible:outline-2 focus-visible:outline-offset-2
                focus-visible:outline-primary active:opacity-100 active:outline-offset-0 dark:text-on-surface-dark
                dark:focus-visible:outline-primary-dark">
                Gestionar Pendientes
            </a>
            <button x-on:click="modalIsOpen = false" type="button"
                    class="whitespace-nowrap rounded-radius bg-primary border border-primary px-4 py-2 text-center text-sm font-medium tracking-wide text-on-primary transition hover:opacity-75 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary active:opacity-100 active:outline-offset-0 dark:bg-primary-dark dark:border-primary-dark dark:text-on-primary-dark dark:focus-visible:outline-primary-dark">
                Volver
            </button>
        </div>
    </div>
</div>
