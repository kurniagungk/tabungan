<x-card title="Notifikasi WhatsApp" shadow separator>
    <div class="grid grid-cols-1 md:grid-cols-5 gap-5 mb-5">
        <div class="col-span-1 md:col-span-2">
            @role('admin')
                <x-select label="Lembaga" wire:model.live="saldo_id" :options="$dataSaldo" option-value="id"
                    option-label="nama" />
            @endrole

            <x-textarea label="Pesan" wire:model="message" placeholder="Tulis pesan notifikasi..." hint="Max 1000 chars"
                rows="6" />

            <div class="mt-3">
                <label class="label">Kirim ke Semua Nasabah (berdasarkan Lembaga)</label>
                <label class="cursor-pointer label justify-start gap-3">
                    <x-toggle class="toggle toggle-success" wire:model.live="broadcastAll" />
                    <span class="label-text">Broadcast ke semua nasabah pada lembaga</span>
                </label>
            </div>

            <div class="mt-4 flex gap-2">
                <x-button label="Kirim" class="btn-primary" wire:click="send" spinner
                    wire:confirm.prompt="Kirim notifikasi sekarang?\n\nTulis IYA to confirm|IYA" />
            </div>
        </div>

        <div class="col-span-1 md:col-span-3 pt-5 px-10">
            <p>Keterangan</p>
            <p class="">{nama} = Nama Nasabah</p>
            <p class="">{saldo} = Saldo Nasabah</p>
            <p class="">{jumlah} = Nominal Tarik / Setor Tunai</p>
            <p class="">{tanggal} = Tanggal Transaksi</p>
            <p class="">{keterangan} = Keterangan Transaksi</p>
        </div>
    </div>

    @if (!$broadcastAll)
        <div class="mb-3">
            <x-input label="Search" placeholder="Nama / Rekening / Telepon" wire:model.live.debounce="search"
                icon="o-magnifying-glass" clearable />
        </div>

        <x-table :headers="$headers" :rows="$nasabah" striped class="mt-5" with-pagination per-page="perPage"
            wire:model.live="selected" selectable :per-page-values="[10, 15, 20]">

            @scope('cell_saldo', $item)
                {{ Number::currency($item->saldo, 'Rp.') }}
            @endscope

            @scope('cell_telepon', $item)
                <span class="{{ $item->wa ? 'text-success' : 'text-error' }}">
                    {{ $item->telepon ?? '-' }}
                </span>
            @endscope
        </x-table>
    @endif
</x-card>
