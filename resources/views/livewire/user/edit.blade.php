<x-card title="Form Data Pengguna" shadow separator>
    <form wire:submit.prevent="simpan">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <x-input label="Name" wire:model.defer="name" />
            <x-input label="Email" type="email" wire:model.defer="email" />
            <x-input label="Password" type="password" wire:model.defer="password" />
        </div>

        <div class="mt-6">
            <x-button type="submit">Simpan</x-button>
        </div>
    </form>
</x-card>
