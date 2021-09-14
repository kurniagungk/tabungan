<table class="table">
    <thead>
        <tr>
            <th scope="col" colspan="2">LAPORAN TABUNGAN AL-KAHFI {{ $data['bulan'] }}</th>

        </tr>

    </thead>
    <tbody>
        <tr>
            <td width="50">Tanggal Cetak</td>
            <td width="50">{{ date('Y-m-d H:i:s') }}</td>
        </tr>
        <tr>
            <td>Total Saldo Awal</td>
            <td>Rp. {{ number_format($data['saldo'] + $data['biaya'], 2, ',', '.') }}</td>
        </tr>
        <tr>
            <td>Jumlah Nasabah</td>
            <td style="text-align: left">{{ $data['nasabah'] }}</td>
        </tr>
        <tr>
            <td>BIAYA ADMIN</td>
            <td>Rp. {{ number_format($data['biaya'], 2, ',', '.') }}</td>
        </tr>
        <tr>
            <td>Total Saldo Akhir</td>
            <td>Rp. {{ number_format($data['saldo'], 2, ',', '.') }}</td>
        </tr>

    </tbody>
</table>
