<div class="mt-2">
    <x-header title="Dashboard" separator />

    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <x-stat title="Messages" value="{{ $data['jumlahNasaba'] }}" icon="o-user-circle" tooltip="Nasabah"
            color="text-primary" />
        <x-stat title="Total Saldo Tabungan" value="{{ Number::currency($data['saldo'], 'Rp.') }}" icon="o-wallet"
            color="text-success" />
        <x-stat title="Saldo Nasabah Aktif" value="{{ Number::currency($data['aktif'], 'Rp.') }}" icon="o-credit-card"
            tooltip="Hello" color="text-warning" />
        <x-stat title="Saldo Nasabah Non Aktif" value="{{ Number::currency($data['tidak'], 'Rp.') }}" icon="o-banknotes"
            tooltip="Hello" color="text-error" />
    </div>

    <livewire:dasbord.chart />
</div>

@assets
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
@endassets
