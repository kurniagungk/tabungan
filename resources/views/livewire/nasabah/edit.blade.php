<x-card title="Edit Nasabah" separator progress-indicator>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mb-5">

        <x-input label="NIS" wire:model="nisn" />
        <x-input label="Nama" wire:model="nama" />

        <x-input label="Tempat Lahir" wire:model="tempat_lahir" />
        <x-input label="Tanggal Lahir" type="date" wire:model="tanggal_lahir" />




        <x-input label="Telepon" wire:model="telepon" type="number" />

        <x-select label="Jenis Kelamin" wire:model="jenis_kelamin" :options="$jenis_kelamins" icon="o-user" />


        <x-input label="Nama Wali" wire:model="nama_wali" />

        <x-input label="Tahun Masuk" wire:model="tahun" />

        <x-input label="Password" type="password" wire:model="pasword" />

        <x-radio label="Aktifkan WhatsApp" wire:model="wa" :options="$waSelect" inline />

        <x-input prefix="Rp." label="Saldo" value="{{ $saldo }}" disabled />


        <x-file wire:model="photo" accept="image/png" class="flex justify-center">
            <img src="{{ $photo?->temporaryUrl() ?? ($photo_old ? asset('storage/' . $photo_old) : '/images/upload.png') }}"
                class=" h-20 rounded-lg mx-auto" />
        </x-file>
        <x-textarea label="Alamat" wire:model="alamat" placeholder="Here ..." hint="Max 1000 chars" rows="5" />

    </div>

    <x-button label="Save" wire:click="update" class="btn-primary" />
</x-card>
