<x-card title="Form Data Pengguna" shadow separator>
    <x-table :headers="$headers" :rows="$users" with-pagination per-page="perPage" :per-page-values="[3, 5, 10]">
        @scope('actions', $user)
            <div class="flex gap-2">
                <x-button icon="o-trash" wire:click="delete('{{ $user->id }}')" spinner class="btn-sm btn-error"
                    wire:confirm.prompt="Apakah ingin menghapus data?\n\nKETIK HAPUS to confirm|HAPUS" />
                <x-button icon="o-pencil" link="/user/{{ $user->id }}/edit" class="btn-sm btn-warning" />
            </div>
        @endscope

        @scope('cell_peran', $user)
            @foreach ($user->roles as $role)
                <x-badge value=" {{ $role->name }}" class="badge-error badge-dash" />
            @endforeach
        @endscope
    </x-table>

    <x-slot:menu>
        <x-button icon="o-plus" class="btn-primary" link="/user/create" />
    </x-slot:menu>

</x-card>
