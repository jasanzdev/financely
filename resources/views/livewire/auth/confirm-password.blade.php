<div class="flex flex-col gap-6">
    <x-auth-header
        :title="__('Confirmar contraseña')"
        :description="__('Esta es una zona segura de la aplicación. Confirme su contraseña antes de continuar.')"
    />

    <!-- Session Status -->
    <x-auth-session-status class="text-center" :status="session('status')"/>

    <form wire:submit="confirmPassword" class="flex flex-col gap-6">
        <!-- Password -->
        <flux:input
            wire:model="password"
            :label="__('Contraseña')"
            type="password"
            required
            autocomplete="new-password"
            :placeholder="__('Contraseña')"
            viewable
        />

        <flux:button variant="primary" type="submit" class="w-full">{{ __('Confirmars') }}</flux:button>
    </form>
</div>
