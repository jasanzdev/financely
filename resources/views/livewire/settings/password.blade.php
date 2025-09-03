<section class="w-full">
    @include('partials.settings-heading')

    <x-settings.layout :heading="__('Cambiar contraseña')"
                       :subheading="__('Asegúrate de que tu cuenta utilice una contraseña larga y aleatoria para mantenerla segura.')">
        <form wire:submit="updatePassword" class="mt-6 space-y-6">
            <flux:input
                wire:model="current_password"
                :label="__('Contraseña Actual')"
                type="password"
                required
                autocomplete="current-password"
            />
            <flux:input
                wire:model="password"
                :label="__('Nueva Contraseña')"
                type="password"
                required
                autocomplete="new-password"
            />
            <flux:input
                wire:model="password_confirmation"
                :label="__('Confirmar Contraseña')"
                type="password"
                required
                autocomplete="new-password"
            />

            <div class="flex items-center gap-4">
                <div class="flex items-center justify-end">
                    <flux:button variant="primary" type="submit" class="w-full">{{ __('Guardar') }}</flux:button>
                </div>

                <x-action-message class="me-3" on="password-updated">
                    {{ __('Guardado.') }}
                </x-action-message>
            </div>
        </form>
    </x-settings.layout>
</section>
