<x-card title="Setting Tabungan" separator>
    @if (session()->has('pesan'))
        <x-alert type="success" class="mb-4">{{ session('pesan') }}</x-alert>
    @endif

    <form wire:submit.prevent="store" class="space-y-4">
        <x-input label="Tanggal Pengambilan Biaya Admin" type="number" wire:model.live="tanggal" name="tanggal"
            :error="$errors->first('tanggal')" />

        <x-input label="Biaya Admin Per Bulan" type="number" wire:model.live="biaya" name="biaya" :error="$errors->first('biaya')" />

        <x-input label="Saldo Minimal" type="number" wire:model.live="minimal" name="minimal" :error="$errors->first('minimal')" />

        <x-textarea label="WA Saldo Habis" wire:model.live="habis" name="habis" :error="$errors->first('habis')" rows="5" />

        <div class="flex justify-end gap-2 mt-5">
            <x-button type="submit" color="success" size="sm">Simpan</x-button>
            <x-button type="reset" color="danger" size="sm" onclick="window.location.href='/nasabah'">
                Cancel
            </x-button>
        </div>
    </form>
</x-card>
