<body class="bg-primary">
    <div class="row justify-content-center align-self-center">
        <div class="col-lg-10">
            <div class="card o-hidden border-0 shadow-lg my-5 card-block">
                <div class="card-body p-0">
                    <!-- Nested Row within Card Body -->

                    <div class="row">
                        <div class="col-lg-7">
                            <div class="p-5">
                                <div class="text-center">
                                    <h1 class="h5 text-gray-900 mb-4">Payment - {{$user}}</h1>
                                </div>

                                <div>
                                    @if (session()->has('message'))
                                    <div class="alert alert-success">
                                        {{ session('message') }}
                                    </div>
                                    @endif
                                </div>

                                <div>
                                    @if (session()->has('danger'))
                                    <div class="alert alert-danger">
                                        {{ session('danger') }}
                                    </div>
                                    @endif
                                </div>

                                <form wire:submit.prevent="bayar">

                                    <div class="input-group ">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">Rp.</span>
                                        </div>
                                        <input wire:model.lazy="jumlah" id="jumlah" type="number" autofocus class="form-control form-control-user @error('jumlah') is-invalid @enderror">
                                        @error('jumlah')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                        @enderror
                                    </div>
                                    <br>

                                    <!-- camera code -->
                                    <div class="input-group row @if($video) @else d-none @endif">
                                        <div class="embed-responsive embed-responsive-4by3">
                                            <video id="preview"></video>
                                        </div>
                                    </div>

                                    @if($video)
                                    <br>
                                    @endif

                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span wire:click="video" class="input-group-text"><i class="fas fa-user"></i></span>
                                        </div>
                                        <input value="" wire:model.debounce.500ms="nis" type="text" class="form-control form-control-user @error('nis') is-invalid @enderror" id="nis" placeholder="NIS Santri">
                                        @error('nis')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                        @enderror
                                    </div>
                                    <br>

                                    @if($nasabah)

                                    <div class="form-group row">
                                        <label class="col-sm-12 col-form-label">Nama : {{$nasabah->nama}}</label>

                                    </div>

                                    @endif

                                    <div class="form-group row">
                                        <div class="col-sm-12 mb-12 mb-sm-0">
                                            <input wire:model="password" type="password" pattern="[0-9]*" inputmode="numeric" id="pin" class="form-control form-control-user @error('password') is-invalid @enderror" placeholder="PIN">
                                            @error('password')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                            @enderror
                                        </div>

                                    </div>
                                    <br>
                                    <button type="submit" class="btn btn-primary btn-user btn-block">
                                        Verifikasi Bayar
                                    </button>
                                </form>
                                <hr>
                                <div class="text-center">
                                    <a class="small" href="/">Back to admin?</a>
                                </div>
                                <div class="text-center">
                                    <a class="small" href="/riwayat">Riwayat transaksi!</a>
                                </div>
                            </div>
                        </div>
                        <!-- link gambar -->
                        <div class="col-lg-5 d-none d-lg-block">
                            <img src="assets/logo-transaksi.jpg">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

@push('scripts')
<script type="text/javascript" src="https://rawgit.com/schmich/instascan-builds/master/instascan.min.js"></script>
<script type="text/javascript">
    document.addEventListener('DOMContentLoaded', function() {

        window.livewire.on('nasabah', () => {
            document.getElementById("pin").focus();
        });

        window.livewire.on('berhasil', () => {
            document.getElementById("jumlah").focus();
        });

        let opts = {
            continuous: true,
            video: document.getElementById('preview'),
            mirror: true,
            captureImage: false,
            backgroundScan: true,
            refractoryPeriod: 5000,
            scanPeriod: 1
        };

        let scanner = new Instascan.Scanner(opts);

        scanner.addListener('scan', function(content) {
            @this.set('nis', content)
        });
        Instascan.Camera.getCameras().then(function(cameras) {

            if (cameras.length > 0) {
                if (cameras.length > 1) {
                    scanner.start(cameras[1]);
                } else {
                    scanner.start(cameras[0]);
                }

            } else {
                console.error('No cameras found.');
            }
        }).catch(function(e) {

        });





    })
</script>

@endpush