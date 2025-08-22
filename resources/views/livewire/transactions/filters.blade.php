<div x-data="{ selectedTab: @entangle('selectedTab') }" class="p-3 sm:p-5 w-full">
    <div x-on:keydown.right.prevent="$focus.wrap().next()" x-on:keydown.left.prevent="$focus.wrap().previous()"
         class="flex gap-2 border-b border-outline dark:border-outline-dark" role="tablist"
         aria-label="tab options">
        <button x-on:click="$wire.latestDays(7)" x-bind:aria-selected="selectedTab === 'days'"
                x-bind:tabindex="selectedTab === 'days' ? '0' : '-1'"
                x-bind:class="selectedTab === 'days' ? ' font-bold text-secondary border-b-2 dark:border-primary-dark dark:text-primary-dark' : 'text-on-surface font-medium dark:text-on-surface-dark dark:hover:border-b-outline-dark-strong dark:hover:text-on-surface-dark-strong hover:border-b-2 hover:border-b-outline-strong hover:text-on-surface-strong'"
                class="h-min px-3 py-2 sm:px-4 sm:py-4 text-xs sm:text-sm {{ $selectedTab === 'days' ? 'bg-gradient-to-t from-neutral-200 dark:from-neutral-800 shadow shadow-gray-400 dark:shadow-gray-600' : '' }}"
                type="button" role="tab"
                aria-controls="tabpanelDays">Últimos 7 días
        </button>
        <button x-on:click="$wire.latestMonth({{now()->month}})"
                x-bind:aria-selected="selectedTab === 'current-month'"
                x-bind:tabindex="selectedTab === 'current-month' ? '0' : '-1'"
                x-bind:class="selectedTab === 'current-month' ? 'font-bold text-secondary border-b-2 dark:border-primary-dark dark:text-primary-dark' : 'text-on-surface font-medium dark:text-on-surface-dark dark:hover:border-b-outline-dark-strong dark:hover:text-on-surface-dark-strong hover:border-b-2 hover:border-b-outline-strong hover:text-on-surface-strong'"
                class="h-min px-3 py-2 sm:px-4 sm:py-4 text-xs sm:text-sm {{ $selectedTab === 'current-month' ? 'bg-gradient-to-t from-neutral-200 dark:from-neutral-800 shadow shadow-gray-400 dark:shadow-gray-600' : '' }}"
                type="button" role="tab"
                aria-controls="tabpanelCurrentMonth">Mes Actual
        </button>
        <button x-on:click="$wire.latestMonth({{now()->month - 1}})"
                x-bind:aria-selected="selectedTab === 'previous-month'"
                x-bind:tabindex="selectedTab === 'previous-month' ? '0' : '-1'"
                x-bind:class="selectedTab === 'previous-month' ? 'font-bold text-secondary border-b-2 dark:border-primary-dark dark:text-primary-dark' : 'text-on-surface font-medium dark:text-on-surface-dark dark:hover:border-b-outline-dark-strong dark:hover:text-on-surface-dark-strong hover:border-b-2 hover:border-b-outline-strong hover:text-on-surface-strong'"
                class="h-min px-3 py-2 sm:px-4 sm:py-4 text-xs sm:text-sm {{ $selectedTab === 'previous-month' ? 'bg-gradient-to-t from-neutral-200 dark:from-neutral-800 shadow shadow-gray-400 dark:shadow-gray-600' : '' }}"
                type="button" role="tab"
                aria-controls="tabpanelPreviousMonth">Mes Anterior
        </button>
        <button x-on:click="$wire.historical"
                x-bind:aria-selected="selectedTab === 'historical'"
                x-bind:tabindex="selectedTab === 'historical' ? '0' : '-1'"
                x-bind:class="selectedTab === 'historical' ? 'font-bold text-secondary border-b-2 dark:border-primary-dark dark:text-primary-dark' : 'text-on-surface font-medium dark:text-on-surface-dark dark:hover:border-b-outline-dark-strong dark:hover:text-on-surface-dark-strong hover:border-b-2 hover:border-b-outline-strong hover:text-on-surface-strong'"
                class="h-min px-3 py-2 sm:px-4 sm:py-4 text-xs sm:text-sm {{ $selectedTab === 'historical' ? 'bg-gradient-to-t from-neutral-200 dark:from-neutral-800 shadow shadow-gray-400 dark:shadow-gray-600' : '' }}"
                type="button" role="tab"
                aria-controls="tabpanelHistorical">Consulta Histórica
        </button>
    </div>

    <div
        class="px-2 py-4 sm:px-3 sm:py-6 space-y-3 text-on-surface dark:text-on-surface-dark">
        <div class="flex justify-between items-center md:px-4">
            <div x-show="selectedTab !== 'historical'"
                 class="flex w-full flex-col sm:flex-row md:gap-4 sm:gap-6">
                <x-app.stats-filter-bar :incomes="$incomes" :expenses="$expenses" :categories="$categories"/>
            </div>
        </div>

        <div x-cloak x-show="selectedTab !== 'historical'" id="tabpanelLatest" role="tabpanel" aria-label="latest">
            <x-app.transaction-list :transactions="$transactions"/>

            <div class="py-3 px-5">
                {{ $transactions->links() }}
            </div>
        </div>
        <div x-cloak x-show="selectedTab === 'historical'" id="tabpanelHistorical" role="tabpanel"
             aria-label="historical">

            <x-app.historical-filters/>

            <div class="flex flex-col sm:flex-row items-center md:gap-4 sm:gap-6 mt-6 sm:mt-12">
                <x-app.stats-filter-bar :incomes="$incomes" :expenses="$expenses" :categories="$categories"/>
            </div>
            <x-app.transaction-list
                :transactions="$transactions"/>
            <div class="py-2 px-5">
                {{ $transactions->links() }}
            </div>
        </div>
    </div>
</div>

