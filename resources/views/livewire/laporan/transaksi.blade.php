<div>
    <x-card title="laporan Transaksi" shadow>
        <div class="grid grid-cols-1 md:grid-cols-7 gap-5 content-center">
            <div class="col-span-1 md:col-span-2">
                <x-input type="date" wire:model="dari" label="Dari" />
            </div>
            <div class="col-span-1 md:col-span-2">
                <x-input wire:model="sampai" type="date" label="Sampai" />
            </div>
            <div class="col-span-1 md:col-span-2">
                <x-select label="Lembaga" wire:model="lembaga_id" :options="$lembaga" option-value="id"
                    option-label="nama" :disabled="!auth()->user()->hasRole('admin')" />
            </div>
            <div class="grid content-end ">
                <x-button icon="o-magnifying-glass" label="Lihat" class="btn-primary" wire:click="laporan" spinner />
            </div>
        </div>
    </x-card>
    @if ($show)
    <x-card title="Data" shadow class="mt-5">

        <x-slot:menu>
            <x-button icon="o-share" class="btn btn-success" wire:click="export" />
        </x-slot:menu>
        <div class="p-6 overflow-x-auto">
            <table class="table table-zebra">
                <thead>
                    <tr>
                        <th colspan="2">PERIODE</th>
                        <th colspan="7">{{ $dari }} - {{ $sampai }}</th>
                    </tr>
                    <tr>
                        <th colspan="2">Total Setor</th>
                        <th colspan="6">{{ Number::currency($transaksi->sum('debit'), 'Rp.') }}</th>
                    </tr>
                    <tr>
                        <th colspan="2">Total Tarik</th>
                        <th colspan="6">{{ Number::currency($transaksi->sum('credit'), 'Rp.') }}</th>
                    </tr>

                    <tr>
                        <th colspan="2">Total</th>
                        <th colspan="6">
                            {{ Number::currency($transaksi->sum('debit') - $transaksi->sum('credit'), 'Rp.') }}</th>
                    </tr>
                    <tr>
                        <th colspan="9"></th>
                    </tr>
                    <tr>
                        <th>No</th>
                        <th>Tanggal</th>
                        <th>Rekening</th>
                        <th>Nama</th>
                        <th>Ambil</th>
                        <th>Simpan</th>
                        <th>Total</th>
                        <th>Keterangan</th>
                        <th>Petugas</th>
                    </tr>
                </thead>

                <tbody>
                    @php $jumlah = 0; @endphp
                    @foreach ($transaksi as $tr)
                    @php $jumlah += $tr->debit - $tr->credit; @endphp
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $tr->created_at }}</td>
                        <td>{{ $tr->nasabah->rekening }}</td>
                        <td>{{ $tr->nasabah->nama }}</td>
                        <td>{{ Number::currency($tr->credit, 'Rp.') }}</td>
                        <td>{{ Number::currency($tr->debit, 'Rp.') }}</td>
                        <td>{{ Number::currency($jumlah, 'Rp.') }}</td>
                        <td>{{ $tr->keterangan }}</td>
                        <td>{{ $tr->user ? $tr->user->name : '-' }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

    </x-card>
    @endif

</div>