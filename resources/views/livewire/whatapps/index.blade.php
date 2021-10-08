<div class="container-fluid">


    <div class="row">
        <div class="col-xl-3 col-lg-12">
            <div class="card shadow mb-4">
                <!-- Card Header - Dropdown -->
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">Whatapps</h6>
                    <div class="dropdown no-arrow">
                        <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown"
                            aria-haspopup="true" aria-expanded="false">
                            <i class="fas fa-ellipsis-v fa-sm fa-fw text-gray-400"></i>
                        </a>
                    </div>
                </div>
                <!-- Card Body -->
                <div class="card-body px-5 flex item-center">

                    @if ($status == 'ready')
                        <h2>Server Ready</h2>
                        <button onclick="start()" type="button" class="btn btn-primary btn-lg btn-block">start</button>
                    @endif

                    @if ($status == 'qr')
                        <img src="" alt="" id="qrcode" class="Responsive image">
                        <p class="text-center">Scann Untuk Whatapps Bot</p>
                    @endif

                    @if ($status == 'tidak')
                        <h1>Server Tidak Terhubung</h1>
                    @endif

                    @if ($status == 'terhubung')
                        <h1>Whatapps Terhubung</h1>
                        <button onclick="stop()" type="button" class="btn btn-danger btn-lg btn-block">Stop</button>
                    @endif

                </div>



            </div>
        </div>
        <div class="col-xl-9 col-lg-10">
            <div class="card shadow mb-4">
                <!-- Card Header - Dropdown -->
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">Histori Whatapps</h6>
                </div>
                <!-- Card Body -->
                <div class="card-body px-5">



                    <table class="table">
                        <thead>
                            <tr>
                                <th scope="col">No</th>
                                <th>HP</th>
                                <th>Nasabah</th>
                                <th>jenis</th>
                                <th>Status</th>
                                <th>Tanggal</th>
                            </tr>

                        </thead>
                        <tbody>
                            @forelse ( $whatapps->reverse() as $data )
                                <tr>
                                    <td scope="col">{{ $loop->index }}</td>
                                    <td>{{ $data->nomer }}</td>
                                    <td>{{ $data->nama }}</td>
                                    <td>{{ $data->jenis }}</td>
                                    <td>
                                        <span
                                            class="badge badge-pill @if ($data->status == 'berhasil')  badge-success  @else badge-danger @endif">{{ $data->status }}</span>
                                    </td>
                                    <td>{{ $data->created_at }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center">Tidak Ada Data</td>
                                </tr>
                            @endforelse

                        </tbody>
                    </table>

                </div>



            </div>
        </div>
    </div>


</div>


@push('scripts')

    <script src="https://cdn.socket.io/4.1.2/socket.io.min.js"
        integrity="sha384-toS6mmwu70G0fw54EGlWWeA4z3dyJ+dlXBtSURSKN4vyRFOcxd3Bzjj/AoOwY+Rg" crossorigin="anonymous">
    </script>

    <script>
        const socket = io("{{ $url?->isi }}");
        socket.on("connect_error", () => {
            console.log('connect_error')
            Livewire.emit("status", "tidak")
            socket.connect();
        });

        function start() {
            socket.emit("start", "whatapps");
            @this.status = "qr"
            timeout = false
        }

        function stop() {
            socket.emit("stop", "whatapps");
            @this.status = "ready"
        }

        function status(pesan) {
            @this.status = pesan
        }

        socket.on('status', function(pesan) {

            let data = pesan ? "terhubung" : "ready";
            console.log(data)

            status(data)

        })
        socket.on('pesan', function(pesan) {
            Livewire.emit('pesan')
        })

        socket.on('qr', function(src) {
            let gambar = document.getElementById("qrcode");
            if (gambar)
                gambar.setAttribute('src', src)
        });
    </script>

@endpush
