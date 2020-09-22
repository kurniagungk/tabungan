<table>
    <thead>
        <tr>
            <th>No</th>
            <th>ID. Transaksi</th>
            <th>Tanggal</th>
            <th>Nama Nasabah</th>
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
            <td>{{$t->mitra->name}}</td>
            <td>{{$t->jumlah}}</td>

        </tr>
        @empty
        <tr>
            <td colspan="5" class="text-center">tidak ada data</td>
        </tr>
        @endforelse
        @else

        @endif
    </tbody>
</table>
