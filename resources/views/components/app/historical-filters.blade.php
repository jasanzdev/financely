<div class="flex flex-col sm:flex-row gap-3 sm:gap-5">
    <div class="relative flex w-full max-w-xs flex-col gap-1 text-on-surface dark:text-on-surface-dark">
        <label for="historical" class="w-fit pl-0.5 text-xs sm:text-sm">Mes</label>
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"
             class="absolute pointer-events-none right-4 top-8 sm:top-9 size-4 sm:size-5">
            <path fill-rule="evenodd"
                  d="M5.22 8.22a.75.75 0 0 1 1.06 0L10 11.94l3.72-3.72a.75.75 0 1 1 1.06 1.06l-4.25 4.25a.75.75 0 0 1-1.06 0L5.22 9.28a.75.75 0 0 1 0-1.06Z"
                  clip-rule="evenodd"/>
        </svg>
        <select id="historical" name="selectedMonth" autocomplete="selectedMonth"
                wire:model.live="selectedMonth" wire:change="historical"
                class="w-full appearance-none rounded-radius border border-outline bg-surface-alt px-3 py-2 text-xs sm:text-sm focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary disabled:cursor-not-allowed disabled:opacity-75 dark:border-outline-dark dark:bg-surface-dark-alt/50 dark:focus-visible:outline-primary-dark">
            <option selected disabled>Selecciona un mes</option>
            <option value="1">Enero</option>
            <option value="2">Febrero</option>
            <option value="3">Marzo</option>
            <option value="4">Abril</option>
            <option value="5">Mayo</option>
            <option value="6">Junio</option>
            <option value="7">Julio</option>
            <option value="8">Agosto</option>
            <option value="9">Septiembre</option>
            <option value="10">Octubre</option>
            <option value="11">Noviembre</option>
            <option value="12">Diciembre</option>
        </select>
    </div>
    <div class="relative flex w-full max-w-xs flex-col gap-1 text-on-surface dark:text-on-surface-dark">
        <label for="year" class="w-fit pl-0.5 text-xs sm:text-sm">Año</label>
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"
             class="absolute pointer-events-none right-4 top-8 sm:top-9 size-4 sm:size-5">
            <path fill-rule="evenodd"
                  d="M5.22 8.22a.75.75 0 0 1 1.06 0L10 11.94l3.72-3.72a.75.75 0 1 1 1.06 1.06l-4.25 4.25a.75.75 0 0 1-1.06 0L5.22 9.28a.75.75 0 0 1 0-1.06Z"
                  clip-rule="evenodd"/>
        </svg>
        <select id="year" name="year" wire:model.live="selectedYear" wire:change="historical"
                class="w-full appearance-none rounded-radius border border-outline bg-surface-alt px-3 py-2 text-xs sm:text-sm focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary disabled:cursor-not-allowed disabled:opacity-75 dark:border-outline-dark dark:bg-surface-dark-alt/50 dark:focus-visible:outline-primary-dark">
            <option selected>{{ now()->year }}</option>
            @for ($i = now()->year - 1; $i > now()->year - 10; $i--)
                <option value="{{ $i }}">{{ $i }}</option>
            @endfor
        </select>
    </div>
</div>
