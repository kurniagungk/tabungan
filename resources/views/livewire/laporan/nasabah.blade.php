<div>
    <x-card title="Laporan Mutasi Nasabah" shadow>
        <div class="grid grid-cols-1 md:grid-cols-7 gap-5 content-center">
            <div class="col-span-1 md:col-span-2">
                <x-input wire:model="rekening" label="Nomor Rekening" placeholder="Masukkan nomor rekening..." />
            </div>
            <div class="col-span-1 md:col-span-2">
                <x-input type="date" wire:model="tanggal_dari" label="Dari" />
            </div>
            <div class="col-span-1 md:col-span-2">
                <x-input type="date" wire:model="tanggal_sampai" label="Sampai" />
            </div>
            <div class="grid content-end">
                <x-button icon="o-magnifying-glass" label="Lihat" class="btn-primary" wire:click="filter" spinner />
            </div>
        </div>
    </x-card>

    @if($transaksi)
    <x-card title="Data Transaksi" shadow class="mt-5">
        <x-slot:menu>
            <x-button icon="o-share" class="btn btn-success" wire:click="export" />
        </x-slot:menu>
        <div class="p-6 overflow-x-auto">
            <table class="table table-zebra">
                <thead>
                    <tr>
                        <td class="font-medium">NASABAH</td>
                        <td colspan="4">{{ $nasabah->nama }} ({{ $nasabah->rekening }})</td>
                    </tr>
                    <tr>
                        <td class="font-medium">PERIODE</td>
                        <td colspan="4">{{ date('Y-m-d', strtotime($tanggal_dari)) }} - {{ date('Y-m-d',
                            strtotime($tanggal_sampai)) }}</td>
                    </tr>
                    <tr>
                        <td class="font-medium">Total Setor</td>
                        <td colspan="4">{{ $totalSetor ? 'RP. ' . number_format($totalSetor, 2) : 'RP. 0.00' }}</td>
                    </tr>
                    <tr>
                        <td class="font-medium">Total Tarik</td>
                        <td colspan="4">{{ $totalTarik ? 'RP. ' . number_format($totalTarik, 2) : 'RP. 0.00' }}</td>
                    </tr>
                    <tr>
                        <td class="font-medium">Total</td>
                        <td colspan="4">{{ 'RP. ' . number_format($totalSetor - $totalTarik, 2) }}</td>
                    </tr>
                    <tr>
                        <td colspan="5"></td>
                    </tr>
                    <tr>
                        <th>No</th>
                        <th>Tanggal</th>
                        <th>Simpan</th>
                        <th>Ambil</th>
                        <th>Keterangan</th>
                        <th>Petugas</th>
                    </tr>
                </thead>
                <tbody>
                    @if($transaksi)
                    @php $jumlah = 0; @endphp
                    @foreach($transaksi as $tr)
                    @php
                    $jumlah += $tr->debit - $tr->credit;
                    @endphp
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ date('Y-m-d H:i:s', strtotime($tr->created_at)) }}</td>
                        <td>{{ $tr->debit > 0 ? 'RP. ' . number_format($tr->debit, 2) : 'RP. 0.00' }}</td>
                        <td>{{ $tr->credit > 0 ? 'RP. ' . number_format($tr->credit, 2) : 'RP. 0.00' }}</td>
                        <td>{{ $tr->keterangan ?? '-' }}</td>
                        <td>{{ $tr->user ? $tr->user->name : '-' }}</td>
                    </tr>
                    @endforeach
                    @endif
                </tbody>
            </table>
        </div>
    </x-card>
    @endif

</div>