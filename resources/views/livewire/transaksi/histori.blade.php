<div class="overflow-x-auto">
    <table class="table table-zebra table-sm">
        <thead class="bg-base-200 text-base font-semibold">
            <tr>
                <th>#</th>
                <th>Tanggal</th>
                <th>Keterangan</th>
                <th>Setor</th>
                <th>Tarik</th>
                <th>Saldo</th>
            </tr>
        </thead>
        <tbody>
            <tr class="bg-base-100">
                <td></td>
                <td class="font-bold " colspan="2">Saldo Histori</td>

                <td>Rp. {{ number_format($saldoHistori['debit'], 2, ',', '.') }}</td>
                <td>Rp. {{ number_format($saldoHistori['credit'], 2, ',', '.') }}</td>
                <td>Rp. {{ number_format($saldoHistori['debit'] - $saldoHistori['credit'], 2, ',', '.') }}</td>

                <td></td>
            </tr>

            @php
                $saldo = $saldoHistori['debit'] - $saldoHistori['credit'];
            @endphp

            @foreach ($transaksi->reverse() as $tr)
                @php
                    $saldo += $tr->debit - $tr->credit;
                @endphp
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $tr->created_at }}</td>
                    <td>{{ $tr->keterangan }}</td>
                    <td>Rp. {{ number_format($tr->debit, 2, ',', '.') }}</td>
                    <td>Rp. {{ number_format($tr->credit, 2, ',', '.') }}</td>
                    <td>Rp. {{ number_format($saldo, 2, ',', '.') }}</td>


                </tr>
            @endforeach

            <tr class="bg-base-200 font-semibold">

                <td colspan="3" class="text-center  text-primary">Total</td>
                <td class="text-success ">Rp. {{ number_format($transaksi->sum('debit'), 2, ',', '.') }}</td>
                <td class="text-error ">Rp. {{ number_format($transaksi->sum('credit'), 2, ',', '.') }}</td>
                <td class="text-warning ">Rp. {{ number_format($saldo, 2, ',', '.') }}</td>

            </tr>
        </tbody>
    </table>
</div>
