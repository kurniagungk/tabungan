<div class="grid grid-cols-1 md:grid-cols-8 gap-5">
    <x-card title="Detail" shadow class="col-span-5">
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
                <h3>: <a class="link link-success" href="https://api.whatsapp.com/send?phone=62{{ $nasabah->telepon }}"
                        target="_blank">{{ $nasabah->telepon }}</a></h3>

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

</div>
