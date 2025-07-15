<x-card title="Data Nasabah" shadow separator>
    <div>
        <x-button label="tambah data" class="btn-primary" icon="o-plus" link="/nasabah/create" />
        <x-button label="Export Data" class="btn-success" icon="s-arrow-down-on-square-stack" wire:click="export" />
        <x-button label="Import Data" class="btn-success hidden" icon="o-arrow-up-on-square-stack"
            link="/nasabah/import" />
    </div>

    <div class="mt-5 grid grid-cols-1 md:grid-cols-4 gap-5">
        <x-input placeholder="Search..." label="Search" wire:model.live.debounce="search" clearable
            icon="o-magnifying-glass" />
        <fieldset class="fieldset">
            <legend class="fieldset-legend">Status Nasabah</legend>
            <select class="select" wire:model.live.debounce="status">
                <option value="semua">Semua</option>
                <option value="aktif">Aktif</option>
                <option value="tidak">Tidak Aktif</option>
            </select>
        </fieldset>
        @role('admin')
            <x-select label="Lembaga" wire:model.live.debounce="saldo_id" :options="$saldos" placeholder="Pilih Lembaga"
                option-value="id" option-label="nama" />
        @endrole
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

        @scope('cell_saldo', $nasabah)
            {{ Number::currency($nasabah->saldo, 'Rp.') }}
        @endscope
        @scope('cell_status', $nasabah)
            <span class="badge {{ $nasabah->status === 'aktif' ? 'badge-success' : 'badge-error' }}">
                {{ ucfirst($nasabah->status) }}
            </span>
        @endscope
        @scope('actions', $nasabah)
            <div class="flex gap-2">
                <x-button icon="o-eye" link="/nasabah/{{ $nasabah->id }}/show" class="btn-sm btn-primary" />
                <x-button icon="o-pencil" link="/nasabah/{{ $nasabah->id }}/edit" class="btn-sm btn-warning" />
                @if ($nasabah->status === 'aktif')
                    <x-button icon="o-power" wire:click="statusNasabah('{{ $nasabah->id }}')"
                        wire:confirm.prompt="Akan menonaktifkan nasabah?\n\nTulis IYA to confirm|IYA"
                        class="btn-sm  btn-error" />
                @else
                    <x-button icon="o-power" wire:click="statusNasabah('{{ $nasabah->id }}')"
                        wire:confirm.prompt="Akan mengaktifkan nasabah?\n\nTulis IYA to confirm|IYA"
                        class="btn-sm btn-success" />
                @endif

            </div>
        @endscope
    </x-table>
</x-card>
