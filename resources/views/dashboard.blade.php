<x-layouts.app :title="__('Dashboard')">
    <div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl">
        <livewire:metrics.grid/>
        <div
            class="relative h-full flex-1 overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700">

            @if(session('message'))
                <x-app.alert message="{{ session('message') }}"/>
            @endif

            <section class="grid grid-cols-6 gap-6 py-5 mx-2">
                <aside
                    class="col-span-6 md:col-span-2 shadow-lg rounded-lg border border-neutral-200 dark:border-neutral-700">
                    <livewire:transactions.create/>
                </aside>

                <main
                    class="shadow-lg rounded-lg border border-neutral-200 dark:border-neutral-700 col-span-6 md:col-start-3 md:col-span-4">
                    <livewire:transactions.index/>
                </main>
            </section>
        </div>
    </div>
</x-layouts.app>
