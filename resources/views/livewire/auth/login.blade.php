<div class="w-96 mx-auto">
    {{-- Brand --}}
    <div class="mb-10">
        <div class="flex items-center gap-3">
            <x-icon name="o-cube" class="w-9 h-9 text-purple-500" />
            <span class="font-bold text-4xl bg-gradient-to-r from-purple-500 to-pink-300 bg-clip-text text-transparent">
                ContactCRM
            </span>
        </div>
    </div>

    {{-- Form --}}
    <x-form wire:submit="login">
        <x-input label="E-mail" wire:model="email" type="email" icon="o-envelope" />
        <x-input label="Password" wire:model="password" type="password" icon="o-key" />

        <x-slot:actions>
            <x-button label="Login" type="submit" icon="o-paper-airplane" class="btn-primary" spinner="login" />
        </x-slot:actions>
    </x-form>
</div>
