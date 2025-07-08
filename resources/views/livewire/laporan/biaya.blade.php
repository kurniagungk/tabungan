<div class="grid grid-cols-1 md:grid-cols-6 gap-5 mt-5">
    <x-card title="laporan Biaya" shadow class="col-span-1 md:col-span-3 self-start">

        <x-datetime type="month" wire:model="tanggal" label="Tanggal" />

        <x-select label="Lembaga" wire:model="lembaga_id" :options="$lembaga" option-value="id" option-label="nama"
            :disabled="!auth()->user()->hasRole('admin')" />


        <div class=" flex justify-end mt-5">
            <x-button icon="o-magnifying-glass" label="Lihat" class="btn-primary" wire:click="laporan" spinner />
        </div>

    </x-card>

    <x-card title="laporan Biaya" shadow class="col-span-1 md:col-span-3">
        <table class="table">
            <thead>
                <tr>
                    <th scope="col">LAPORAN TABUNGAN AL-KAHFI {{ $tanggal }}</th>
                    <th></th>
                </tr>

            </thead>
            <tbody>
                <tr>
                    <td>Tanggal Cetak</td>
                    <td>{{ $cetak }}</td>
                </tr>
                <tr>
                    <td>Total Saldo Awal</td>
                    <td>Rp. {{ number_format($saldo + $biaya, 2, ',', '.') }}</td>
                </tr>
                <tr>
                    <td>Jumlah Nasabah</td>
                    <td>{{ $nasabah }}</td>
                </tr>
                <tr>
                    <td>BIAYA ADMIN</td>
                    <td>Rp. {{ number_format($biaya, 2, ',', '.') }}</td>
                </tr>
                <tr>
                    <td>Total Saldo Akhir</td>
                    <td>Rp. {{ number_format($saldo, 2, ',', '.') }}</td>
                </tr>

            </tbody>
        </table>

        <x-slot:menu>
            <x-button icon="o-share" class="btn-circle btn-sm" wire:click="export" />

        </x-slot:menu>
    </x-card>


</div>
