<div>
    @if(session('message'))
        <x-app.alert message="{{ session('message') }}"/>
    @endif
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 min-h-[100px]">
        <div
            class="relative h-full">
            <x-app.card
                title="Pendiente de Cobro"
                amount="$U {{$receivables->sum('amount')}}"
                description="Total pendiente por cobrar"
                amountColor="text-green-700"
                iconColor="text-green-700"
                icon='<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-6">
                          <path fill-rule="evenodd" d="M15.22 6.268a.75.75 0 0 1 .968-.431l5.942 2.28a.75.75 0 0 1 .431.97l-2.28 5.94a.75.75 0 1 1-1.4-.537l1.63-4.251-1.086.484a11.2 11.2 0 0 0-5.45 5.173.75.75 0 0 1-1.199.19L9 12.312l-6.22 6.22a.75.75 0 0 1-1.06-1.061l6.75-6.75a.75.75 0 0 1 1.06 0l3.606 3.606a12.695 12.695 0 0 1 5.68-4.974l1.086-.483-4.251-1.632a.75.75 0 0 1-.432-.97Z"
                          clip-rule="evenodd"/>
                       </svg>'
            />
        </div>
        <div
            class="relative h-full">
            <x-app.card
                title="Cobros"
                amount="{{count($receivables)}}"
                description="Transacciones pendientes de cobro"
                amountColor="text-red-700"
                amountColor="text-blue-700"
                iconColor="text-blue-700"
                icon='<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                         stroke="currentColor" class="size-6">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m3.75 9v7.5m2.25-6.466a9.016 9.016 0 0 0-3.461-.203c-.536.072-.974.478-1.021 1.017a4.559 4.559 0 0 0-.018.402c0 .464.336.844.775.994l2.95 1.012c.44.15.775.53.775.994 0 .136-.006.27-.018.402-.047.539-.485.945-1.021 1.017a9.077 9.077 0 0 1-3.461-.203M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z"/>
                    </svg>'
            />
        </div>
        <div
            class="relative h-full">
            <x-app.card
                title="Pendiente de Pago"
                amount="$U {{ $payables->sum('amount') }}"
                description="Total pendiente por pagar"
                amountColor="text-red-700"
                iconColor="text-red-700"
                icon=' <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-6">
                        <path fill-rule="evenodd"
                              d="M1.72 5.47a.75.75 0 0 1 1.06 0L9 11.69l3.756-3.756a.75.75 0 0 1 .985-.066 12.698 12.698 0 0 1 4.575 6.832l.308 1.149 2.277-3.943a.75.75 0 1 1 1.299.75l-3.182 5.51a.75.75 0 0 1-1.025.275l-5.511-3.181a.75.75 0 0 1 .75-1.3l3.943 2.277-.308-1.149a11.194 11.194 0 0 0-3.528-5.617l-3.809 3.81a.75.75 0 0 1-1.06 0L1.72 6.53a.75.75 0 0 1 0-1.061Z"
                              clip-rule="evenodd"/>
                    </svg>'
            />
        </div>

        <div
            class="relative h-full">
            <x-app.card
                title="Pagos"
                amount="{{ count($payables) }}"
                description="Transacciones pendientes de pago"
                amountColor="text-blue-700"
                iconColor="text-blue-700"
                icon='<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                         stroke="currentColor" class="size-6">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m3.75 9v7.5m2.25-6.466a9.016 9.016 0 0 0-3.461-.203c-.536.072-.974.478-1.021 1.017a4.559 4.559 0 0 0-.018.402c0 .464.336.844.775.994l2.95 1.012c.44.15.775.53.775.994 0 .136-.006.27-.018.402-.047.539-.485.945-1.021 1.017a9.077 9.077 0 0 1-3.461-.203M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z"/>
                    </svg>'
            />
        </div>
    </div>

    <div x-data="{ selectedTab: @entangle('selectedTab') }" class="w-full p-4 sm:p-6 mt-4">
        <div x-on:keydown.right.prevent="$focus.wrap().next()" x-on:keydown.left.prevent="$focus.wrap().previous()"
             class="flex flex-col sm:flex-row gap-2 border-b border-outline dark:border-outline-dark" role="tablist"
             aria-label="tab options">
            <button x-on:click="$wire.selectAll" x-bind:aria-selected="selectedTab === 'all'"
                    x-bind:tabindex="selectedTab === 'all' ? '0' : '-1'"
                    x-bind:class="selectedTab === 'all' ? 'font-bold text-secondary border-b-2 dark:border-primary-dark dark:text-primary-dark' : 'text-on-surface font-medium dark:text-on-surface-dark dark:hover:border-b-outline-dark-strong dark:hover:text-on-surface-dark-strong hover:border-b-2 hover:border-b-outline-strong hover:text-on-surface-strong'"
                    class="h-min px-4 py-3 text-sm sm:text-base {{ $selectedTab === 'all' ? 'bg-gradient-to-t from-neutral-200 dark:from-neutral-800 shadow shadow-gray-400 dark:shadow-gray-600' : '' }}"
                    type="button" role="tab" aria-controls="tabpanelAll">Todas
            </button>
            <button x-on:click="$wire.selectReceivable" x-bind:aria-selected="selectedTab === 'receivable'"
                    x-bind:tabindex="selectedTab === 'receivable' ? '0' : '-1'"
                    x-bind:class="selectedTab === 'receivable' ? 'font-bold text-secondary border-b-2 dark:border-primary-dark dark:text-primary-dark' : 'text-on-surface font-medium dark:text-on-surface-dark dark:hover:border-b-outline-dark-strong dark:hover:text-on-surface-dark-strong hover:border-b-2 hover:border-b-outline-strong hover:text-on-surface-strong'"
                    class="h-min px-4 py-3 text-sm sm:text-base {{ $selectedTab === 'receivable' ? 'bg-gradient-to-t from-neutral-200 dark:from-neutral-800 shadow shadow-gray-400 dark:shadow-gray-600' : '' }}"
                    type="button" role="tab" aria-controls="tabpanelReceivable">Pendientes de
                cobro
            </button>
            <button x-on:click="$wire.selectPayable" x-bind:aria-selected="selectedTab === 'payable'"
                    x-bind:tabindex="selectedTab === 'payable' ? '0' : '-1'"
                    x-bind:class="selectedTab === 'payable' ? 'font-bold text-secondary border-b-2 dark:border-primary-dark dark:text-primary-dark' : 'text-on-surface font-medium dark:text-on-surface-dark dark:hover:border-b-outline-dark-strong dark:hover:text-on-surface-dark-strong hover:border-b-2 hover:border-b-outline-strong hover:text-on-surface-strong'"
                    class="h-min px-4 py-3 text-sm sm:text-base {{ $selectedTab === 'payable' ? 'bg-gradient-to-t from-neutral-200 dark:from-neutral-800 shadow shadow-gray-400 dark:shadow-gray-600' : '' }}"
                    type="button" role="tab" aria-controls="tabpanelPayable">Pendientes
                de pago
            </button>
        </div>
        <div class="px-2 py-4 text-on-surface dark:text-on-surface-dark">
            <ul class="grid grid-cols-1 sm:grid-cols-[repeat(auto-fill,minmax(350px,1fr))] gap-4">
                @forelse($transactions as $transaction)
                    <li wire:key="toggle-{{ $transaction->id }}"
                        class="flex flex-col gap-3 p-3 sm:p-4 rounded-lg bg-surface-alt shadow border dark:border-neutral-700 dark:bg-surface-dark-alt/50 hover:shadow-xs dark:hover:shadow-neutral-600 transition-shadow duration-200
                        {{ !$transaction->is_payment_future ? 'border-2 border-red-300 dark:border-red-600 animate-pulse' : '' }}">
                        <div class="flex items-center w-full sm:w-auto mb-2 sm:mb-0">
                            <div
                                class="rounded-full p-2 mr-2 sm:mr-3 {{ $transaction->type === 'income' ? 'bg-green-100 dark:bg-green-900/30' : 'bg-red-100 dark:bg-red-900/30' }}">
                                @if($transaction->type === 'income')
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 24 24"
                                         class="w-4 h-4 sm:w-5 sm:h-5 text-green-700 dark:text-green-400">
                                        <path fill-rule="evenodd"
                                              d="M15.22 6.268a.75.75 0 0 1 .968-.431l5.942 2.28a.75.75 0 0 1 .431.97l-2.28 5.94a.75.75 0 1 1-1.4-.537l1.63-4.251-1.086.484a11.2 11.2 0 0 0-5.45 5.173.75.75 0 0 1-1.199.19L9 12.312l-6.22 6.22a.75.75 0 0 1-1.06-1.061l6.75-6.75a.75.75 0 0 1 1.06 0l3.606 3.606a12.695 12.695 0 0 1 5.68-4.974l1.086-.483-4.251-1.632a.75.75 0 0 1-.432-.97Z"
                                              clip-rule="evenodd"/>
                                    </svg>
                                @else
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 24 24"
                                         class="w-4 h-4 sm:w-5 sm:h-5 text-red-700 dark:text-red-400">
                                        <path fill-rule="evenodd"
                                              d="M1.72 5.47a.75.75 0 0 1 1.06 0L9 11.69l3.756-3.756a.75.75 0 0 1 .985-.066 12.698 12.698 0 0 1 4.575 6.832l.308 1.149 2.277-3.943a.75.75 0 1 1 1.299.75l-3.182 5.51a.75.75 0 0 1-1.025.275l-5.511-3.181a.75.75 0 0 1 .75-1.3l3.943 2.277-.308-1.149a11.194 11.194 0 0 0-3.528-5.617l-3.809 3.81a.75.75 0 0 1-1.06 0L1.72 6.53a.75.75 0 0 1 0-1.061Z"
                                              clip-rule="evenodd"/>
                                    </svg>
                                @endif
                            </div>

                            <div class="flex-1 space-y-2">
                                <p class="text-base sm:text-lg font-medium text-on-surface dark:text-on-surface-dark line-clamp-2">
                                    {{ $transaction->description }}
                                </p>

                                <div class="flex flex-wrap items-center gap-3 mt-1">
                                        <span
                                            class="bg-neutral-100 dark:bg-neutral-700 border border-neutral-300 dark:border-neutral-600 rounded-full px-2 py-0.5 text-xs">
                                            {{ $transaction->category->category }}
                                        </span>
                                    @if($transaction->category->slug !== 'obligaciones-mensuales')
                                        <span class="text-xs sm:text-sm text-gray-500 dark:text-gray-400">
                                            {{$transaction->formatted_date}}
                                        </span>
                                    @endif
                                </div>
                                <span class="text-xs sm:text-sm text-gray-500 dark:text-gray-400">
                                       Fecha de pago -> {{$transaction->formatted_expected_payment_date}}
                                    </span>
                            </div>
                        </div>
                        <div class="flex items-center gap-3 justify-end">
                            <label for="toggle-{{ $transaction->id }}" class="inline-flex items-center gap-3">
                                <input id="toggle-{{ $transaction->id }}" type="checkbox" class="peer sr-only"
                                       role="switch"
                                       wire:click.prevent="changeStatus('{{ $transaction->id }}')"
                                       wire:confirm="Desea marcar como pagada esta transacción?"
                                />
                                <span
                                    class="trancking-wide text-sm font-medium text-on-surface peer-checked:text-on-surface-strong peer-disabled:cursor-not-allowed peer-disabled:opacity-70 dark:text-on-surface-dark dark:peer-checked:text-on-surface-dark-strong">
                                    {{ $transaction->type === 'income' ? 'Cobrar' : 'Pagar' }}
                                </span>
                                <div
                                    class="relative h-7 w-12 after:h-6 after:w-6 peer-checked:after:translate-x-5 rounded-full border border-outline bg-surface-alt after:absolute after:bottom-0 after:left-[0.0625rem] after:top-0 after:my-auto after:rounded-full after:bg-on-surface after:transition-all after:content-[''] peer-checked:bg-primary peer-checked:after:bg-on-primary peer-focus:outline-2 peer-focus:outline-offset-2 peer-focus:outline-outline-strong peer-focus:peer-checked:outline-primary peer-active:outline-offset-0 peer-disabled:cursor-not-allowed peer-disabled:opacity-70 dark:border-outline-dark dark:bg-surface-dark-alt dark:after:bg-on-surface-dark dark:peer-checked:bg-primary-dark dark:peer-checked:after:bg-on-primary-dark dark:peer-focus:outline-outline-dark-strong dark:peer-focus:peer-checked:outline-primary-dark"
                                    aria-hidden="true"></div>
                            </label>
                            <div
                                class="text-sm sm:text-base font-semibold {{ $transaction->type === 'income' ? 'text-green-700 dark:text-green-400' : 'text-red-700 dark:text-red-400' }}">
                                <span>{{ $transaction->type === 'income' ? '+' : '-' }} ${{ number_format($transaction->amount, 2, ',', '.') }}</span>
                            </div>

                        </div>
                        <div
                            class="flex items-center w-full sm:w-auto gap-2 sm:gap-3 mt-2 sm:mt-0">

                            <a href="{{ route('transaction.edit', [$transaction, 'from' => 'pending']) }}"
                               wire:navigate
                               class="p-2 rounded-lg border border-neutral-300 dark:border-neutral-600 shadow-lg hover:bg-neutral-200 dark:hover:bg-neutral-700">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"
                                     class="size-4 sm:size-6 fill-black dark:fill-neutral-400">
                                    <path
                                        d="M21.731 2.269a2.625 2.625 0 0 0-3.712 0l-1.157 1.157 3.712 3.712 1.157-1.157a2.625 2.625 0 0 0 0-3.712ZM19.513 8.199l-3.712-3.712-8.4 8.4a5.25 5.25 0 0 0-1.32 2.214l-.8 2.685a.75.75 0 0 0 .933.933l2.685-.8a5.25 5.25 0 0 0 2.214-1.32l8.4-8.4Z"/>
                                    <path
                                        d="M5.25 5.25a3 3 0 0 0-3 3v10.5a3 3 0 0 0 3 3h10.5a3 3 0 0 0 3-3V13.5a.75.75 0 0 0-1.5 0v5.25a1.5 1.5 0 0 1-1.5 1.5H5.25a1.5 1.5 0 0 1-1.5-1.5V8.25a1.5 1.5 0 0 1 1.5-1.5h5.25a.75.75 0 0 0 0-1.5H5.25Z"/>
                                </svg>
                            </a>
                            <button wire:click.prevent="delete('{{ $transaction->id }}')"
                                    wire:confirm="Estás seguro que desea eliminar la transacción?"
                                    class="p-2 rounded-lg border border-neutral-300 dark:border-neutral-600 shadow-lg hover:bg-neutral-200 dark:hover:bg-neutral-700"
                                    type="button"
                                    aria-label="Delete transaction">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"
                                     class="size-4 sm:size-6 fill-danger">
                                    <path fill-rule="evenodd"
                                          d="M16.5 4.478v.227a48.816 48.816 0 0 1 3.878.512.75.75 0 1 1-.256 1.478l-.209-.035-1.005 13.07a3 3 0 0 1-2.991 2.77H8.084a3 3 0 0 1-2.991-2.77L4.087 6.66l-.209.035a.75.75 0 0 1-.256-1.478A48.567 48.567 0 0 1 7.5 4.705v-.227c0-1.564 1.213-2.9 2.816-2.951a52.662 52.662 0 0 1 3.369 0c1.603.051 2.815 1.387 2.815 2.951Zm-6.136-1.452a51.196 51.196 0 0 1 3.273 0C14.39 3.05 15 3.684 15 4.478v.113a49.488 49.488 0 0 0-6 0v-.113c0-.794.609-1.428 1.364-1.452Zm-.355 5.945a.75.75 0 1 0-1.5.058l.347 9a.75.75 0 1 0 1.499-.058l-.346-9Zm5.48.058a.75.75 0 1 0-1.498-.058l-.347 9a.75.75 0 0 0 1.5.058l.345-9Z"
                                          clip-rule="evenodd"/>
                                </svg>
                            </button>
                        </div>
                    </li>
                @empty
                    <li class="text-center py-4 text-gray-500 dark:text-gray-400">
                        @if($selectedTab === 'all')
                            No tienes transacciones pendientes
                        @elseif($selectedTab === 'receivable')
                            No tienes transacciones pendientes de cobro
                        @else
                            No tiene transacciones pendientes de pago
                        @endif
                    </li>
                @endforelse
            </ul>
            <div class="py-2 px-5">
                {{ $transactions->links() }}
            </div>
        </div>
    </div>

</div>
