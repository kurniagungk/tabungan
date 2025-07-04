<x-card title="Create Nasabah" separator progress-indicator>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mb-5">

        <x-input label="NIS" wire:model="nisn">
            <x-slot:append>
                {{-- Add `join-item` to all appended elements --}}
                <x-button label="CARI DATA PPDB" class="join-item btn-primary" wire:click="cari" />
            </x-slot:append>
        </x-input>
        <x-input label="Nama" wire:model="nama" />

        <x-input label="Tempat Lahir" wire:model="tempat_lahir" />
        <x-input label="Tanggal Lahir" type="date" wire:model="tanggal_lahir" />




        <x-input label="No Hp (Whatsapp)" wire:model="telepon" type="number" prefix="+62"/>

        <x-select label="Jenis Kelamin" wire:model="jenis_kelamin" :options="$jenis_kelamins" icon="o-user" />


        <x-input label="Nama Wali" wire:model="nama_wali" />




        <x-input label="Tahun Masuk" wire:model="tahun" />

        <x-input label="Password" type="password" wire:model="pasword" />

        <x-radio label="Aktifkan WhatsApp" wire:model="wa" :options="$waSelect" inline />

        <x-input prefix="Rp." label="Saldo" type="number" wire:model="saldo" />

        @role('admin')
            <x-select label="Lembaga" wire:model="saldo_id" :options="$dataSaldo" option-value="id" option-label="nama" />
        @endrole

        @if ($photoUrl != null)
            <div wire:key="api">
                <x-file wire:model="photo" accept="image/png, image/jpeg" class="flex justify-center">
                    <img src="{{ asset('storage/' . $photoUrl) }}" class=" h-20 rounded-lg mx-auto" />
                </x-file>
            </div>
        @else
            <div wire:key="local">
                <x-file wire:model="photo" accept="image/png, image/jpeg" class="flex justify-center">
                    <img src="{{ $photo?->temporaryUrl() ?? '/images/upload.png' }}" class=" h-20 rounded-lg mx-auto" />
                </x-file>
            </div>
        @endif

        <x-textarea label="Alamat" wire:model="alamat" hint="Max 1000 chars" rows="5" />

    </div>

    <div class="flex justify-end"> <x-button label="Save" wire:click="store" class="btn-primary" spinner /></div>
</x-card>
