<table>
    <thead>
        <tr>
            <th>No</th>
            <th>ID. Transaksi</th>
            <th>Tanggal</th>
            <th>Nama Mitra</th>
            <th>Jumlah</th>
            <th>Keperluan</th>
        </tr>
    </thead>
    <tbody>
        @if($data['jurnal'])
        @forelse ($data['jurnal'] as $j)

        <tr>
            <td>{{$loop->index +1}}</td>
            <td>{{substr($j->id,0,8)}}</td>
            <td>{{$j->created_at}}</td>
            <td>{{$j->mitra->name}}</td>
            <td>{{$j->jumlah}}</td>
            <td>{{$j->keterangan}}</td>

        </tr>
        @empty
        @endforelse
        @else
        <tr>
            <td></td>
        </tr>
        @endif
    </tbody>
</table>
