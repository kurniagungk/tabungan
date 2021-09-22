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
                <div class="card-body px-5">
                    @if ($io)
                        @if ($status)
                            <h1>Whatapps Terhubung</h1>
                        @else
                            <img src="" alt="" id="qrcode">
                        @endif
                    @else
                        <h1>Server Tidak Terhubung</h1>
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
                                <th>Tanggal</th>
                                <th>Status</th>
                            </tr>

                        </thead>
                        <tbody>
                            @forelse ( $whatapps->reverse() as $data )
                                <tr>
                                    <td scope="col">{{ $loop->index }}</td>
                                    <td>{{ $data->nomer }}</td>
                                    <td>{{ $data->nama }}</td>
                                    <td>{{ $data->created_at }}</td>
                                    <td>
                                        <span
                                            class="badge badge-pill @if ($data->status == 'berhasil')  badge-success  @else badge-danger @endif">{{ $data->status }}</span>
                                    </td>
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
        const socket = io("{{ $url?->isi }}", {

        });
        socket.on("connect_error", () => {
            @this.io = false
            socket.connect();
        });

        socket.on('status', function(status) {

            @this.io = true
            @this.status = status

        })
        socket.on('pesan', function(pesan) {

            Livewire.emit('pesan')

        })

        socket.on('qr', function(src) {
            document.getElementById("qrcode").setAttribute('src', src);
        });
    </script>

@endpush
