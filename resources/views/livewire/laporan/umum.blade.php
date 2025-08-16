<x-card title="Jurnal Umum">
    <x-slot:header>
        <x-dropdown>
            <x-dropdown.header>Export File :</x-dropdown.header>
            <x-dropdown.link href="#">PDF</x-dropdown.link>
            <x-dropdown.link href="#">Excel</x-dropdown.link>
            <x-dropdown.divider />
            <x-dropdown.link href="#">Something else here</x-dropdown.link>
        </x-dropdown>
    </x-slot:header>

    <x-row>
        <x-col desktop="7">
            <x-card>
                <x-form wire:submit="filter">
                    <x-form.group label="Periode">
                        <x-input type="date" wire:model.live="awal" label="Awal" />
                        <x-input type="date" wire:model.live="akhir" label="Akhir" />
                    </x-form.group>

                    <x-form.group label="Jenis Transaksi">
                        <x-radio wire:model.live="jenisTransaksi" name="jenisTransaksi" value="" label="Semua" />
                        <x-radio wire:model.live="jenisTransaksi" name="jenisTransaksi" value="setor" label="Setor" />
                        <x-radio wire:model.live="jenisTransaksi" name="jenisTransaksi" value="tarik" label="Tarik" />
                    </x-form.group>

                    <x-form.group label="Mitra">
                        <x-select wire:model.live='selectMitra' :options="$mitra" option-label="name" option-value="id"
                            label="Pilih Mitra" />
                    </x-form.group>

                    <x-slot:footer>
                        <x-button type="submit" icon="fas fa-filter" color="info">Filter</x-button>
                    </x-slot:footer>
                </x-form>
            </x-card>
        </x-col>

        <x-col desktop="5">
            <x-card>
                <x-description-list>
                    <x-description term="Periode">{{ $awal ?? '' }} - {{ $akhir ?? '' }}
                    </x-description>
                    <x-description term="Total Setor">{{ $totalSetor ?? '' }}</x-description>
                    <x-description term="Total Tarik">{{ $totalTarik ?? '' }}</x-description>
                    <x-description term="Saldo">{{ $totalSetor - $totalTarik ?? '' }}</x-description>
                </x-description-list>

                <x-slot:footer>
                    <x-button wire:click='export' icon="fas fa-download" color="warning">Export</x-button>
                </x-slot:footer>
            </x-card>
        </x-col>
    </x-row>


    <x-table :headers="$headers" :rows="$transaksi">
        @scope('cell_no', $t, $loop)
        {{ $loop->index + 1 }}
        @endscope

        @scope('cell_id', $t)
        {{ substr($t->id, 0, 8) }}
        @endscope

        @scope('cell_created_at', $t)
        {{ $t->created_at }}
        @endscope

        @scope('cell_nasabah.nis', $t)
        {{ $t->nasabah->nis }}
        @endscope

        @scope('cell_nasabah.nama', $t)
        {{ $t->nasabah->nama }}
        @endscope

        @scope('cell_jenis', $t)
        {{ $t->jenis }}
        @endscope

        @scope('cell_mitra.name', $t)
        {{ $t->mitra->name }}
        @endscope

        @scope('cell_jumlah', $t)
        {{ $t->jumlah }}
        @endscope

        <x-slot:empty>
            <x-table.row>
                <x-table.cell colspan="8" class="text-center">Tidak ada data</x-table.cell>
            </x-table.row>
        </x-slot:empty>
    </x-table>
</x-card>