@props([
'transactions'
])

<ul class="space-y-3 mt-3 sm:mt-4">
    @forelse($transactions as $transaction)
        <li class="flex flex-col sm:flex-row justify-between items-start sm:items-center p-3 sm:p-4 rounded-lg bg-surface-alt shadow-sm border dark:border-neutral-700 dark:bg-surface-dark-alt/50 hover:shadow-md dark:hover:shadow-neutral-300 transition-shadow duration-200">
            <a href="{{ route('transaction.edit', [$transaction, 'from' => url()->current()]) }}"
               wire:navigate
               class="flex flex-col sm:flex-row items-start sm:items-center flex-1"
            >
                <div class="flex items-center w-full sm:w-auto mb-3 sm:mb-0">

                    <div
                        class="rounded-full p-2 mr-3 {{ $transaction->type === 'income' ? 'bg-green-100 dark:bg-green-900/30' : 'bg-red-100 dark:bg-red-900/30' }}">
                        @if($transaction->type === 'income')
                            <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 24 24"
                                 class="w-5 h-5 text-green-700 dark:text-green-400">
                                <path fill-rule="evenodd"
                                      d="M15.22 6.268a.75.75 0 0 1 .968-.431l5.942 2.28a.75.75 0 0 1 .431.97l-2.28 5.94a.75.75 0 1 1-1.4-.537l1.63-4.251-1.086.484a11.2 11.2 0 0 0-5.45 5.173.75.75 0 0 1-1.199.19L9 12.312l-6.22 6.22a.75.75 0 0 1-1.06-1.061l6.75-6.75a.75.75 0 0 1 1.06 0l3.606 3.606a12.695 12.695 0 0 1 5.68-4.974l1.086-.483-4.251-1.632a.75.75 0 0 1-.432-.97Z"
                                      clip-rule="evenodd"/>
                            </svg>
                        @else
                            <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 24 24"
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
                                {{ $transaction->date }}
                            </span>
                        </div>
                    </div>
                </div>
            </a>
            <div class="flex items-center justify-between w-full sm:w-auto sm:justify-end gap-3 mt-2 sm:mt-0">
                <div
                    class="text-base sm:text-lg font-semibold {{ $transaction->type === 'income' ? 'text-green-700 dark:text-green-400' : 'text-red-700 dark:text-red-400' }}">
                        <span>
                            {{ $transaction->type === 'income' ? '+' : '-' }} ${{ number_format($transaction->amount, 2, ',', '.') }}</span>
                </div>
                <button wire:click.prevent="delete('{{ $transaction->id }}')"
                        wire:confirm="Estás seguro que desea eliminar la transacción?"
                        class="p-1 sm:p-2 text-white bg-red-500 hover:bg-red-600 rounded cursor-pointer transition-colors duration-200"
                        type="button"
                        aria-label="Delete transaction">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                         stroke="currentColor" class="w-4 h-4">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0"/>
                    </svg>
                </button>
            </div>
        </li>

    @empty
        <li class="text-center py-4 text-gray-500 dark:text-gray-400">
            No haz realizado movimientos financieros hasta el momento
        </li>
    @endforelse
</ul>
