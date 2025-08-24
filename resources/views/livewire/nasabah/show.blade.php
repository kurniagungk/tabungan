<div class="grid grid-cols-1 md:grid-cols-8 gap-5">
    <x-card title="Detail" shadow class="col-span-3">

        <x-slot:menu>
            <x-button icon="o-pencil" link="/nasabah/{{ $nasabah->id }}/edit" class="btn-sm btn-warning" />
        </x-slot:menu>

        <img src="{{ asset('storage/' . $nasabah->foto) }}" class=" h-50 rounded-lg mx-auto" />
        <div class="mx-5 my-10 space-y-2">
            <div class="flex">
                <h3 class="w-40 font-bold">NAMA</h3>
                <h3>: {{ $nasabah->nama }}</h3>
            </div>
            <div class="flex">
                <h3 class="w-40 font-bold">NISN</h3>
                <h3>: {{ $nasabah->nisn }}</h3>
            </div>
            <div class="flex">
                <h3 class="w-40 font-bold">REKENING</h3>
                <h3>: {{ $nasabah->rekening }}</h3>
            </div>
            <div class="flex">
                <h3 class="w-40 font-bold">SALDO</h3>
                <h3>: {{ Number::currency($nasabah->saldo, 'Rp.') }} </h3>
            </div>
            <div class="flex">
                <h3 class="w-40 font-bold">TANGGAL LAHIR</h3>
                <h3>: {{ $nasabah->tanggal_lahir }}</h3>
            </div>
            <div class="flex">
                <h3 class="w-40 font-bold">TEMPAT LAHIR</h3>
                <h3>: {{ $nasabah->tempat_lahir }}</h3>
            </div>
            <div class="flex">
                <h3 class="w-40 font-bold">ALAMAT</h3>
                <h3>: {{ $nasabah->alamat }}</h3>
            </div>

            <div class="flex">
                <h3 class="w-40 font-bold">JENIS KELAMIN</h3>
                <h3>: {{ $nasabah->jenis_kelamin }}</h3>
            </div>
            <div class="flex">
                <h3 class="w-40 font-bold">WALI</h3>
                <h3>: {{ $nasabah->wali }}</h3>
            </div>
            <div class="flex">
                <h3 class="w-40 font-bold">TELEPON</h3>
                <h3>:
                    <a class="link link-success"
                        href="https://api.whatsapp.com/send?phone={{ $nasabah->telepon_whatsapp }}" target="_blank">
                        {{ $nasabah->telepon_whatsapp }}
                    </a>
                </h3>
            </div>
            <div class="flex">
                <h3 class="w-40 font-bold">Notifikasi</h3>
                <h3>: {{ $nasabah->wa == 1 ? 'Aktif' : 'Non Aktif' }}</h3>

            </div>
            <div class="flex">
                <h3 class="w-40 font-bold">STATUS</h3>
                <h3>: {{ $nasabah->status }}</h3>
            </div>
            <div class="flex">
                <h3 class="w-40 font-bold">TAHUN</h3>
                <h3>: {{ $nasabah->tahun }}</h3>
            </div>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-2 mb-3">
            <x-button label="Data Nasabah" class="btn-primary w-full" link="/nasabah" />
            <x-button label="Tambah Nasabah Baru" class="btn-warning w-full" link="/nasabah/create" />
        </div>

        @if ($errors->any())
        <ul>
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
        @endif

    </x-card>

    <x-card title="Transaksi" shadow class="col-span-5">
        <table class="table table-zebra table-sm">
            <thead class="bg-base-200 text-base font-semibold">
                <tr>
                    <th>#</th>
                    <th>Tanggal</th>
                    <th>Keterangan</th>
                    <th>Setor</th>
                    <th>Tarik</th>
                    <th>Saldo</th>
                    <th>Pesan</th>
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
                    <td>
                        @if ($tr->whatsapp)
                        @if ($tr->whatsapp->status == 'gagal')
                        <x-button icon="o-arrow-path" class="btn-error btn-sm" wire:click="ulangi('{{ $tr->id }}')">
                        </x-button>
                        @elseif($tr->whatsapp->status == 'pending')
                        <x-badge value="pending" class="badge-warning" />
                        @else
                        <x-badge value="Berhasil" class="badge-success" />
                        @endif
                        @else
                        <x-button icon="o-paper-airplane" class="btn-primary btn-sm"
                            wire:click="kirimWa('{{ $tr->id }}')"></x-button>
                        @endif
                    <td>


                </tr>
                @endforeach

                <tr class="bg-base-200
                                    font-semibold">

                    <td colspan="3" class="text-center  text-primary">Total</td>
                    <td class="text-success ">Rp. {{ number_format($transaksi->sum('debit'), 2, ',', '.') }}</td>
                    <td class="text-error ">Rp. {{ number_format($transaksi->sum('credit'), 2, ',', '.') }}</td>
                    <td class="text-warning ">Rp. {{ number_format($saldo, 2, ',', '.') }}</td>

                </tr>
            </tbody>
        </table>

        <div class="mt-10 flex justify-end">
            <x-button label="Kirim 5 Mutasi Terakhir" class="btn-success" icon="o-chat-bubble-left-ellipsis"
                wire:click="mutasi" />
        </div>



    </x-card>

</div>