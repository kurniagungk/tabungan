<table class="">
    <thead>
        <tr>
            <th colspan="2">PERIODE</th>
            <th colspan="3">{{ $dari }} - {{ $sampai }}</th>
        </tr>
        <tr>
            <th colspan="2">Total Setor</th>
            <th colspan="3">{{ $transaksi->sum('setor') }}</th>
        </tr>
        <tr>
            <th colspan="2">Total Tarik</th>
            <th colspan="3">{{ $transaksi->sum('tarik') }}</th>
        </tr>
        <tr>
            <th colspan="2">Total Selisih</th>
            <th colspan="3">
                {{ $transaksi->sum('setor') - $transaksi->sum('tarik') }}
            </th>
        </tr>
        <tr>
            <th colspan="5"></th>
        </tr>
        <tr>
            <th>No</th>
            <th>Tanggal</th>
            <th>Setor</th>
            <th>Tarik</th>
            <th>Total</th>
        </tr>
    </thead>

    <tbody>
        @php
            $jumlah = 0;
            $totalSetor = 0;
            $totalTarik = 0;
        @endphp
        @foreach ($transaksi as $tr)
            @php
                $jumlah += $tr->setor - $tr->tarik;
                $totalSetor += $tr->setor;
                $totalTarik += $tr->tarik;
            @endphp
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $tr->day }}</td>
                <td>{{ $tr->setor }}</td>
                <td>{{ $tr->tarik }}</td>
                <td>{{ $jumlah }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
