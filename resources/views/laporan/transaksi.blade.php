<table class="table">
    <thead>
        <tr>
            <th colspan="2" class="text">PERIODE</th>
            <th colspan="7">
                {{ $dari }} - {{ $sampai }}
            </th>
        </tr>
        <tr>
            <th colspan="2" class="text">Total Tarik</th>
            <th colspan="6">
                {{ $transaksi->sum('credit') }}
            </th>
        </tr>
        <tr>
            <th colspan="2" class="text">Total Setor</th>
            <th colspan="6">
                {{ $transaksi->sum('debit') }}
            </th>
        </tr>
        <tr>
            <th colspan="2" class="text">Total</th>
            <th colspan="6">
                {{ $transaksi->sum('debit') - $transaksi->sum('credit') }}
            </th>
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
        </tr>

    </thead>

    <tbody>

        @php
            $jumlah = 0;
        @endphp

        @foreach ($transaksi as $tr)

            @php
                $jumlah += $tr->debit - $tr->credit;
            @endphp

            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $tr->created_at }}</td>
                <td>{{ $tr->nasabah->rekening }}</td>
                <td>{{ $tr->nasabah->nama }}</td>
                <td>{{ $tr->credit }}</td>
                <td>{{ $tr->debit }}</td>
                <td>{{ $jumlah }}</td>
                <td>{{ $tr->keterangan }}</td>
            </tr>


        @endforeach
    </tbody>


</table>
