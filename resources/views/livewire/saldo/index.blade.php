<div>

    <x-card title="Data Lembaga" shadow separator>
        <x-table :headers="$headers" :rows="$saldo" with-pagination per-page="perPage" :per-page-values="[3, 5, 10]">
            @scope('actions', $saldo)
                <div class="flex gap-2">
                    <x-button icon="o-pencil" wire:click="openModal({{ $saldo->id }})" class="btn-sm btn-warning" />
                </div>
            @endscope
        </x-table>

        <x-slot:menu>
            <x-button icon="o-plus" class="btn-primary" wire:click="openModal(null)" />
        </x-slot:menu>

    </x-card>

    <x-modal wire:model="modal" title="Form Data Lembaga" @close="$wire.closeModal()">

        <x-input label="nama" icon="o-user" wire:model="nama" />

        <x-slot:actions>
            <x-button label="Cancel" wire:click="closeModal" />
            <x-button label="Save" class="btn-primary" wire:click="save" />
        </x-slot:actions>

    </x-modal>
</div>
