<table>
    <thead>

        <tr>
            <th>No</th>
            <th>ID Transaksi</th>
            <th>Tanggal</th>
            <th>Rekening</th>
            <th>Nama</th>
            <th>Jenis</th>
            <th>Sumber</th>
            <th>Jumlah</th>
        </tr>

    </thead>
    <tbody>
        @if($data['transaksi'])
        @forelse ($data['transaksi'] as $t)
        <tr>
            <td>{{$loop->index +1}}</td>
            <td>{{substr($t->id,0,8)}}</td>
            <td>{{$t->created_at}}</td>
            <td>{{$t->nasabah->nis}}</td>
            <td>{{$t->nasabah->nama}}</td>
            <td>{{$t->jenis}}</td>
            <td>{{$t->mitra->name}}</td>
            <td>{{$t->jumlah}}</td>
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
