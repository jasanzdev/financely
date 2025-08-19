<div class="mt-4 flex flex-col gap-6">
    <flux:text class="text-center">
        {{ __('Verifique su dirección de correo electrónico haciendo clic en el enlace que le acabamos de enviar por correo electrónico.') }}
    </flux:text>

    @if (session('status') == 'verification-link-sent')
        <flux:text class="text-center font-medium !dark:text-green-400 !text-green-600">
            {{ __('Se ha enviado un nuevo enlace de verificación a la dirección de correo electrónico que proporcionó durante el registro.') }}
        </flux:text>
    @endif

    <div class="flex flex-col items-center justify-between space-y-3">
        <flux:button wire:click="sendVerification" variant="primary" class="w-full">
            {{ __('Reenviar correo electrónico de verificación') }}
        </flux:button>

        <flux:link class="text-sm cursor-pointer" wire:click="logout">
            {{ __('Cerrar sesión') }}
        </flux:link>
    </div>
</div>
