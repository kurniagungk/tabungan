<x-card title="Pesan Whatsapp" class="col-span-1 md:col-span-2 px=5">
    <div class="grid grid-cols-1 md:grid-cols-5 gap-5 mb-5">
        <div class="col-span-1 md:col-span-2">
            <x-radio label="Notifikasi Tarik Tunai" wire:model="status_tarik" :options="$Select" inline />
            <x-textarea label="Pesan Tarik Tunai" wire:model="pesan_tarik" placeholder="Here ..." hint="Max 1000 chars"
                rows="5" />

            <x-radio label="Notifikasi Setor Tunai" wire:model="status_setor" :options="$Select" inline />
            <x-textarea label="Pesan Setor Tunai" wire:model="pesan_setor" placeholder="Here ..." hint="Max 1000 chars"
                rows="5" />
        </div>
        <div class="col-span- md:col-span-3 pt-5 px-10">
            <p>Keterangan</p>
            <p class="">{nama} = Nama Nasabah</p>
            <p class="">{saldo} = Saldo Nasabah</p>
            <p class="">{jumlah} = Nominal Tarik / Setor Tunai</p>
            <p class="">{tanggal} = Tanggal Transaksi</p>
        </div>
    </div>


    <x-button class="btn-primary" wire:click="simpan" label="Simpan" spinner />

</x-card>
