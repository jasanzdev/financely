<section class="mt-10 space-y-6">
    <div class="relative mb-5">
        <flux:heading>{{ __('Eliminar cuenta') }}</flux:heading>
        <flux:subheading>{{ __('Eliminar tu cuenta y todos sus recursos') }}</flux:subheading>
    </div>

    <flux:modal.trigger name="confirm-user-deletion">
        <flux:button variant="danger" x-data="" x-on:click.prevent="$dispatch('open-modal', 'confirm-user-deletion')">
            {{ __('Eliminar cuenta') }}
        </flux:button>
    </flux:modal.trigger>

    <flux:modal name="confirm-user-deletion" :show="$errors->isNotEmpty()" focusable class="max-w-lg">
        <form wire:submit="deleteUser" class="space-y-6">
            <div>
                <flux:heading size="lg">{{ __('Está seguro que desea eliminar su cuenta?') }}</flux:heading>

                <flux:subheading>
                    {{ __('Una vez que tu cuenta sea eliminada, todos sus recursos y datos se borrarán de forma permanente. Por favor, ingresa tu contraseña para confirmar que deseas eliminar tu cuenta de manera permanente.') }}
                </flux:subheading>
            </div>

            <flux:input wire:model="password" :label="__('Contraseña')" type="password"/>

            <div class="flex justify-end space-x-2 rtl:space-x-reverse">
                <flux:modal.close>
                    <flux:button variant="filled">{{ __('Cancelar') }}</flux:button>
                </flux:modal.close>

                <flux:button variant="danger" type="submit">{{ __('Eliminar cuenta') }}</flux:button>
            </div>
        </form>
    </flux:modal>
</section>
