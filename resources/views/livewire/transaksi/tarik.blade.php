<div class="container-fluid">


    <div class="row">
        <div class="col-xl-12 col-lg-10">
            <div class="card shadow mb-4">
                <!-- Card Header - Dropdown -->
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">Tarik Tunai</h6>
                </div>
                <!-- Card Body -->
                <div class="card-body px-5">



                    <form wire:submit.prevent="find">

                        @if (session()->has('message'))
                            <div class="alert alert-success">
                                {{ session('message') }}
                            </div>
                        @endif

                        @if (session()->has('danger'))
                            <div class="alert alert-danger">
                                {{ session('message') }}
                            </div>
                        @endif

                        <div class="input-group">

                            <input type="text" class="form-control @error('rekening') is-invalid @enderror" autofocus
                                wire:model="rekening" placeholder="Username" id="rekening" autofocus
                                aria-describedby="validationTooltipUsernamePrepend" required>
                            <div class="input-group-prepend">
                                <button class="btn btn-primary" type="submit"><i class="fa fa-search"
                                        aria-hidden="true"></i></button>
                            </div>
                            @error('rekening')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>



                    </form>

                </div>



            </div>
        </div>
    </div>

    @if ($nasabah)




        <div class="row">
            <div class="col-lg-5 col-sm-12">
                <div class="card shadow mb-4">
                    <!-- Card Header - Dropdown -->
                    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                        <h6 class="m-0 font-weight-bold text-primary">Tambah Nasabah</h6>
                        <div class="dropdown no-arrow">
                            <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fas fa-ellipsis-v fa-sm fa-fw text-gray-400"></i>
                            </a>
                        </div>
                    </div>
                    <!-- Card Body -->
                    <div class="card-body px-5">

                        <div class="d-flex justify-content-center overflow-hidden mb-3 ">
                            <img src="{{ asset('storage/' . $nasabah->foto) }}" alt=""
                                style="height:200px; width: 200px" class="rounded mx-auto border">
                        </div>



                        <form wire:submit.prevent="save">
                            <div class="form-group row">
                                <label for="staticEmail" class="col-sm-2 col-form-label">Nis</label>
                                <div class="col-sm-10">
                                    <input type="text" readonly class="form-control-plaintext"
                                        value="{{ $nasabah->rekening }}">
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="staticEmail" class="col-sm-2 col-form-label">Nama</label>
                                <div class="col-sm-10">
                                    <input type="text" readonly class="form-control-plaintext"
                                        value="{{ $nasabah->nama }}">
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="staticEmail" class="col-sm-3 col-form-label">Saldo</label>
                                <div class="col-sm-9">
                                    <input type="text" readonly class="form-control-plaintext"
                                        value="Rp. {{ number_format($nasabah->saldo, 2, ',', '.') }}">

                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="staticEmail" class="col-sm-3 col-form-label">Tanggal</label>
                                <div class="col-sm-9">
                                    <input type="datetime-local" step="any" class="form-control" wire:model="tanggal">
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="staticEmail" class="col-sm-3 col-form-label">Keterangan</label>
                                <div class="col-sm-9">
                                    <textarea class="form-control" id="exampleFormControlTextarea1"
                                        wire:model="keterangan" rows="3"></textarea>
                                </div>
                            </div>


                            <div class="form-group row">
                                <label for="setor" class="col-sm-3 col-form-label">Jumlah Tarik</label>
                                <div class="col-sm-9">
                                    <input type="number" class="form-control  @error('tarik') is-invalid @enderror"
                                        id="tarik" wire:model.debounce.500ms="tarik" autocomplete="off">
                                    @error('setor')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="staticEmail" class="col-sm-3 col-form-label">Sisa Saldo</label>
                                <div class="col-sm-9">
                                    <input type="text" readonly class="form-control-plaintext"
                                        value="Rp. {{ number_format($sisa, 2, ',', '.') }}">

                                </div>
                            </div>

                            <div class="form-group row">
                                <div class="col-sm-12">
                                    <button type="submit" class="btn btn-primary btn-lg btn-block mt-3">Tarik</button>
                                </div>
                            </div>

                        </form>






                    </div>



                </div>
            </div>

            <livewire:transaksi.histori :nasabah="$nasabah" />

        </div>

    @endif



    @if ($modal)
        <livewire:transaksi.modal :rekening="$nasabah_id" wire:key='{{ $rekening }}' />
    @endif

</div>



<script defer src="{{ asset('js/sweetalert2.js') }}"></script>

<script>
    Livewire.on('start', () => {
        Swal.fire('Berhasil Tarik Tunai').then(() => {
            document.getElementById('rekening').focus();
        });
    });
    Livewire.on('modal', () => {
        $('#myModal').modal('show')
        $('#password').focus()
    });

    window.addEventListener('modal', () => {
        $('#myModal').modal('show')
        $('#password').focus()
    })

    window.addEventListener('close', () => {
        $('#myModal').modal('tarik')
        @this.removeModal()
        document.getElementById('rekening').focus();
    })

    window.addEventListener('show', () => {
        $('#myModal').modal('hide')
        @this.removeModal()
        window.scrollTo(0, document.body.scrollHeight);
        document.getElementById('tarik').focus();
    })
</script>
