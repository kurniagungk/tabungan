<x-card title="Data Nasabah" shadow separator>
    <div>
        <x-button label="tambah data" class="btn-primary" icon="o-plus" link="/nasabah/create" />
        <x-button label="Export Data" class="btn-success" icon="s-arrow-down-on-square-stack" wire:click="export" />
        <x-button label="Import Data" class="btn-success hidden" icon="o-arrow-up-on-square-stack"
            link="/nasabah/import" />
    </div>

    <div class="mt-5 grid grid-cols-1 md:grid-cols-4 gap-5">
        <x-input placeholder="Search..." wire:model.live.debounce="search" clearable icon="o-magnifying-glass" />
    </div>

    <x-table :headers="$headers" :rows="$nasabah" striped class="mt-5" with-pagination per-page="perPage"
        :sort-by="$sortBy" :per-page-values="[10, 15, 20]">
        @scope('cell_no', $nasabah, $firstPage)
            {{ $firstPage + $loop->index }}
        @endscope

        @scope('cell_photo', $nasabah)
            <img src="{{ $nasabah->foto ? 'storage/' . $nasabah->foto : '/images/user.png' }}"
                class=" h-10 rounded-lg mx-auto" />
        @endscope

        @scope('actions', $nasabah)
            <div class="flex gap-2">
                <x-button icon="o-pencil" link="/nasabah/{{ $nasabah->id }}/edit" class="btn-sm btn-warning" />
            </div>
        @endscope
    </x-table>
</x-card>
