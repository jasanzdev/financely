<div class="flex flex-col gap-6">
    <x-auth-header :title="__('Has olvidado tu contraseña')"
                   :description="__('Ingrese su correo electrónico para recibir un enlace de restablecimiento de contraseña')"/>

    <!-- Session Status -->
    <x-auth-session-status class="text-center" :status="session('status')"/>

    <form wire:submit="sendPasswordResetLink" class="flex flex-col gap-6">
        <!-- Email Address -->
        <flux:input
            wire:model="email"
            :label="__('Correo Electrónico')"
            type="email"
            required
            autofocus
            placeholder="email@example.com"
        />

        <flux:button variant="primary" type="submit"
                     class="w-full">{{ __('Enlace de restablecimiento de contraseña') }}</flux:button>
    </form>

    <div class="space-x-1 rtl:space-x-reverse text-center text-sm text-zinc-400">
        <span>{{ __('O, volver a') }}</span>
        <flux:link :href="route('login')" wire:navigate>{{ __('Iniciar sesión') }}</flux:link>
    </div>
</div>
