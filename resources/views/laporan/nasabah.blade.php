<table>
    <thead>
        <tr>
            <th colspan="2">PERIODE</th>
            <th colspan="4">{{ $tanggal_dari }} - {{ $tanggal_sampai }}</th>
        </tr>
        <tr>
            <th colspan="2">NASABAH</th>
            <th colspan="4">{{ $nasabah->nama }} ({{ $nasabah->rekening }})</th>
        </tr>
        <tr>
            <th colspan="2">Total Setor</th>
            <th colspan="4">{{ Number::currency($transaksi->sum('debit'), 'Rp.') }}</th>
        </tr>
        <tr>
            <th colspan="2">Total Tarik</th>
            <th colspan="4">{{ Number::currency($transaksi->sum('credit'), 'Rp.') }}</th>
        </tr>
        <tr>
            <th colspan="2">Total</th>
            <th colspan="4">{{ Number::currency($transaksi->sum('debit') - $transaksi->sum('credit'), 'Rp.') }}</th>
        </tr>
        <tr>
            <th colspan="6"></th>
        </tr>
        <tr>
            <th>No</th>
            <th>Tanggal</th>
            <th>Simpan</th>
            <th>Ambil</th>
            <th>Total</th>
            <th>Keterangan</th>
            <th>Petugas</th>
        </tr>
    </thead>
    <tbody>
        @php $total = 0; @endphp
        @foreach($transaksi as $tr)
        @php
        $total += $tr->debit - $tr->credit;
        @endphp
        <tr>
            <td>{{ $loop->iteration }}</td>
            <td>{{ $tr->created_at }}</td>
            <td>{{ Number::currency($tr->debit, 'Rp.') }}</td>
            <td>{{ Number::currency($tr->credit, 'Rp.') }}</td>
            <td>{{ Number::currency($total, 'Rp.') }}</td>
            <td>{{ $tr->keterangan ?? '-' }}</td>
            <td>{{ $tr->user ? $tr->user->name : '-' }}</td>
        </tr>
        @endforeach
    </tbody>
</table>