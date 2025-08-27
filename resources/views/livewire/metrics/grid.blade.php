<div
    x-data="{ modalIsOpen: @entangle('modalIsOpen').live }"
    class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4 min-h-[100px]">
    <div
        class="relative h-full">
        <a href="{{ route('transaction.filters') }}" wire:navigate>
            <x-app.card
                title="Ingresos"
                amount="$U {{$total_income}}"
                description="Ingresos totales este mes"
                amountColor="text-green-700"
                iconColor="text-green-700"
                icon='<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-6">
                          <path fill-rule="evenodd" d="M15.22 6.268a.75.75 0 0 1 .968-.431l5.942 2.28a.75.75 0 0 1 .431.97l-2.28 5.94a.75.75 0 1 1-1.4-.537l1.63-4.251-1.086.484a11.2 11.2 0 0 0-5.45 5.173.75.75 0 0 1-1.199.19L9 12.312l-6.22 6.22a.75.75 0 0 1-1.06-1.061l6.75-6.75a.75.75 0 0 1 1.06 0l3.606 3.606a12.695 12.695 0 0 1 5.68-4.974l1.086-.483-4.251-1.632a.75.75 0 0 1-.432-.97Z"
                          clip-rule="evenodd"/>
                       </svg>'
            />
        </a>
    </div>
    <div
        class="relative h-full">
        <a href="{{ route('transaction.filters') }}" wire:navigate>
            <x-app.card
                title="Gastos"
                amount="$U {{$total_expense}}"
                description="Gastos totales este mes"
                amountColor="text-red-700"
                iconColor="text-red-700"
                icon=' <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-6">
                        <path fill-rule="evenodd"
                              d="M1.72 5.47a.75.75 0 0 1 1.06 0L9 11.69l3.756-3.756a.75.75 0 0 1 .985-.066 12.698 12.698 0 0 1 4.575 6.832l.308 1.149 2.277-3.943a.75.75 0 1 1 1.299.75l-3.182 5.51a.75.75 0 0 1-1.025.275l-5.511-3.181a.75.75 0 0 1 .75-1.3l3.943 2.277-.308-1.149a11.194 11.194 0 0 0-3.528-5.617l-3.809 3.81a.75.75 0 0 1-1.06 0L1.72 6.53a.75.75 0 0 1 0-1.061Z"
                              clip-rule="evenodd"/>
                    </svg>'
            />
        </a>
    </div>
    <div
        class="relative h-full">
        <a href="{{ route('transaction.filters') }}" wire:navigate>
            <x-app.card
                title="Dinero disponible"
                amount="$U {{ $total_income - $total_expense }}"
                description="Dinero disponible este mes"
                amountColor="text-green-700"
                iconColor="text-green-700"
                icon=' <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                         stroke="currentColor" class="size-6">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M12 6v12m-3-2.818.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 1.172-2.303 0-3.182C13.536 12.219 12.768 12 12 12c-.725 0-1.45-.22-2.003-.659-1.106-.879-1.106-2.303 0-3.182s2.9-.879 4.006 0l.415.33M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>
                    </svg>'
            />
        </a>
    </div>

    <div
        class="relative h-full">
        <x-app.card
            wire:click="openModal('receivable')"
            title="Cuentas por Cobrar"
            amount="{{ $receivable_transactions->sum('amount') }}"
            description="Total de cuentas por cobrar"
            amountColor="text-green-400"
            iconColor="text-green-400"
            icon='<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-6">
                        <path fill-rule="evenodd"
                        d="M4.755 10.059a7.5 7.5 0 0 1 12.548-3.364l1.903 1.903h-3.183a.75.75 0 1 0 0 1.5h4.992a.75.75 0 0 0 .75-.75V4.356a.75.75 0 0 0-1.5 0v3.18l-1.9-1.9A9 9 0 0 0 3.306 9.67a.75.75 0 1 0 1.45.388Zm15.408 3.352a.75.75 0 0 0-.919.53 7.5 7.5 0 0 1-12.548 3.364l-1.902-1.903h3.183a.75.75 0 0 0 0-1.5H2.984a.75.75 0 0 0-.75.75v4.992a.75.75 0 0 0 1.5 0v-3.18l1.9 1.9a9 9 0 0 0 15.059-4.035.75.75 0 0 0-.53-.918Z"
                        clip-rule="evenodd" />
                   </svg>'
        />
    </div>
    <div
        class="relative h-full">
        <x-app.card
            wire:click="openModal('payable')"
            title="Cuentas por Pagar"
            amount="{{ $payable_transactions->sum('amount') }}"
            description="Total de cuentas por pagar"
            amountColor="text-red-400"
            iconColor="text-red-400"
            icon='<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-6">
                        <path fill-rule="evenodd"
                        d="M4.755 10.059a7.5 7.5 0 0 1 12.548-3.364l1.903 1.903h-3.183a.75.75 0 1 0 0 1.5h4.992a.75.75 0 0 0 .75-.75V4.356a.75.75 0 0 0-1.5 0v3.18l-1.9-1.9A9 9 0 0 0 3.306 9.67a.75.75 0 1 0 1.45.388Zm15.408 3.352a.75.75 0 0 0-.919.53 7.5 7.5 0 0 1-12.548 3.364l-1.902-1.903h3.183a.75.75 0 0 0 0-1.5H2.984a.75.75 0 0 0-.75.75v4.992a.75.75 0 0 0 1.5 0v-3.18l1.9 1.9a9 9 0 0 0 15.059-4.035.75.75 0 0 0-.53-.918Z"
                        clip-rule="evenodd" />
                   </svg>'
        />
    </div>
    <x-app.modal-grid :modalType="$modalType"
                      :receivableTransactions="$receivable_transactions"
                      :payableTransactions="$payable_transactions"/>
</div>
