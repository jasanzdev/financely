<div class="p-4 sm:p-6 lg:p-8">
    @if(session('message'))
        <x-app.alert message="{{ session('message') }}"/>
    @endif
    <div x-data="{modalIsOpen: false}" class="max-w-7xl mx-auto">
        <div
            class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 sm:gap-6 mb-6 sm:mb-8 sm:px-6">
            <div>
                <h1 class="text-2xl sm:text-3xl lg:text-4xl font-bold text-gray-900 dark:text-white">
                    Gestión de Categorías
                </h1>
                <p class="mt-1 text-sm sm:text-base text-gray-600 dark:text-gray-300">
                    Administra tus categorías de manera eficiente
                </p>
            </div>
            <button x-on:click="modalIsOpen=true" type="button"
                    class="inline-flex justify-center items-center gap-2 whitespace-nowrap bg-neutral-900 rounded-lg border border-surface-alt dark:border-surface-dark-alt px-4 py-2 sm:px-11 sm:py-2.5 text-sm font-medium tracking-wide text-neutral-100 transition hover:opacity-75 text-center focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-surface-alt active:opacity-100 active:outline-offset-0 disabled:opacity-75 disabled:cursor-not-allowed dark:bg-surface-dark-alt dark:text-on-surface-dark-strong dark:focus-visible:outline-surface-dark-alt">
                <svg aria-hidden="true" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                     class="size-5" fill="currentColor">
                    <path fill-rule="evenodd"
                          d="M12 3.75a.75.75 0 01.75.75v6.75h6.75a.75.75 0 010 1.5h-6.75v6.75a.75.75 0 01-1.5 0v-6.75H4.5a.75.75 0 010-1.5h6.75V4.5a.75.75 0 01.75-.75z"
                          clip-rule="evenodd"/>
                </svg>
                Crear
            </button>
        </div>

        <!-- Categories List -->
        <ul class="space-y-4">
            @forelse($categories as $category)
                <li class="p-4 sm:p-5 rounded-xl bg-gray-50 dark:bg-neutral-800 border border-gray-200 dark:border-neutral-700 shadow-sm hover:shadow-md transition-all duration-200">
                    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                        <!-- Category Info -->
                        <div class="flex-1">
                            <h2 class="text-lg sm:text-xl font-semibold text-gray-900 dark:text-white">
                                {{ $category->category }}
                            </h2>
                            <p class="mt-1 text-sm sm:text-base text-gray-600 dark:text-gray-300 line-clamp-2">
                                {{ $category->description }}
                            </p>
                            <div class="flex flex-wrap items-center gap-2 mt-2">
                                <span
                                    class="bg-gray-200 dark:bg-neutral-700 text-gray-700 dark:text-gray-200 text-xs font-medium px-2.5 py-1 rounded-full border border-gray-300 dark:border-neutral-600">
                                    /{{ $category->slug }}
                                </span>
                            </div>
                        </div>

                        <!-- Actions -->
                        <div class="flex items-center gap-3 w-full sm:w-auto mt-3 sm:mt-0">
                            <a
                                href="{{ route('category.edit', $category) }}" wire:navigate
                                class="p-2 rounded-lg border border-neutral-300 dark:border-neutral-600 shadow-lg hover:bg-neutral-200 dark:hover:bg-neutral-700"
                            >
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"
                                     class="size-6 fill-black">
                                    <path
                                        d="M21.731 2.269a2.625 2.625 0 0 0-3.712 0l-1.157 1.157 3.712 3.712 1.157-1.157a2.625 2.625 0 0 0 0-3.712ZM19.513 8.199l-3.712-3.712-8.4 8.4a5.25 5.25 0 0 0-1.32 2.214l-.8 2.685a.75.75 0 0 0 .933.933l2.685-.8a5.25 5.25 0 0 0 2.214-1.32l8.4-8.4Z"/>
                                    <path
                                        d="M5.25 5.25a3 3 0 0 0-3 3v10.5a3 3 0 0 0 3 3h10.5a3 3 0 0 0 3-3V13.5a.75.75 0 0 0-1.5 0v5.25a1.5 1.5 0 0 1-1.5 1.5H5.25a1.5 1.5 0 0 1-1.5-1.5V8.25a1.5 1.5 0 0 1 1.5-1.5h5.25a.75.75 0 0 0 0-1.5H5.25Z"/>
                                </svg>
                            </a>
                            <button
                                wire:click.prevent="delete('{{ $category->id }}')"
                                wire:confirm="¿Estás seguro que desea eliminar la categoría?"
                                class="p-2 rounded-lg border border-neutral-300 dark:border-neutral-600 shadow-lg hover:bg-neutral-200 dark:hover:bg-neutral-700"
                                type="button"
                                aria-label="Eliminar categoría"
                            >
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"
                                     class="size-6 fill-danger">
                                    <path fill-rule="evenodd"
                                          d="M16.5 4.478v.227a48.816 48.816 0 0 1 3.878.512.75.75 0 1 1-.256 1.478l-.209-.035-1.005 13.07a3 3 0 0 1-2.991 2.77H8.084a3 3 0 0 1-2.991-2.77L4.087 6.66l-.209.035a.75.75 0 0 1-.256-1.478A48.567 48.567 0 0 1 7.5 4.705v-.227c0-1.564 1.213-2.9 2.816-2.951a52.662 52.662 0 0 1 3.369 0c1.603.051 2.815 1.387 2.815 2.951Zm-6.136-1.452a51.196 51.196 0 0 1 3.273 0C14.39 3.05 15 3.684 15 4.478v.113a49.488 49.488 0 0 0-6 0v-.113c0-.794.609-1.428 1.364-1.452Zm-.355 5.945a.75.75 0 1 0-1.5.058l.347 9a.75.75 0 1 0 1.499-.058l-.346-9Zm5.48.058a.75.75 0 1 0-1.498-.058l-.347 9a.75.75 0 0 0 1.5.058l.345-9Z"
                                          clip-rule="evenodd"/>
                                </svg>

                            </button>
                        </div>
                    </div>

                    <livewire:category.category-create redirectTo="category"/>
                </li>
            @empty
                <li class="text-center py-6 text-gray-500 dark:text-gray-400 text-sm sm:text-base">
                    No hay categorías registradas actualmente
                </li>
            @endforelse

        </ul>
    </div>
</div>
