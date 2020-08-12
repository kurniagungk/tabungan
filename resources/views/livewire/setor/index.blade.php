<div class="col-xl col-lg">
    <div class="card shadow mb-4">
        <!-- Card Header - Dropdown -->

        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
            <h6 class="m-0 font-weight-bold text-primary">Setor Dana</h6>
            <div class="dropdown no-arrow">
                <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i class="fas fa-ellipsis-v fa-sm fa-fw text-gray-400"></i>
                </a>
            </div>
        </div>
        <!-- search -->


        <!-- Card Body -->
        <div class="card-body">
            <div class="row">



                <div class="col-xl-12 col-lg-12">

                    @if (session()->has('pesan'))
                    <div class="alert alert-success">
                        {{ session('pesan') }}
                    </div>
                    @endif

                    <div class="card shadow mb-4">
                        <div class="card-body">
                            <div class="input-group">
                                <input wire:model.lazy="search" type="text" class="form-control " autofocus placeholder="Search for..." autocomplete="new-password">
                                <div class="input-group-append">
                                    <button wire:click='cari' class="btn btn-primary" type="button">
                                        <i class="fas fa-search fa-sm"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>


                @if($setor)
                <div class="col-xl-4 col-lg-5">
                    <div class="card shadow mb-4">

                        <div class="card-body">
                            <div class="form-group row">
                                <label for="staticEmail" class="col-sm-4 col-form-label">Rekening : </label>
                                <label for="staticEmail" class="col-sm-8 col-form-label">{{$santri->nis}}</label>
                            </div>
                            <div class="form-group row">
                                <label for="staticEmail" class="col-sm-4 col-form-label">Nama : </label>
                                <label for="staticEmail" class="col-sm-8 col-form-label">{{$santri->nama}}</label>
                            </div>
                            <div class="form-group row">
                                <label for="staticEmail" class="col-sm-4 col-form-label">Sekolah : </label>
                                <label for="staticEmail" class="col-sm-8 col-form-label">{{$santri->sekolah}}</label>
                            </div>
                            <div class="form-group row">
                                <label for="staticEmail" class="col-sm-4 col-form-label">Saldo : </label>
                                <label for="staticEmail" class="col-sm-8 col-form-label">{{$santri->saldo}}</label>
                            </div>

                            <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">Rp.</span>
                                </div>
                                <input wire:model.lazy="jumlah" type="number" class="form-control" autocomplete="off">
                            </div>

                            <center>
                                <button wire:click="setor" class="btn btn-success btn-icon-split" href="#">
                                    <span class="icon text-white-50">
                                        <i class="fas fa-upload"></i>
                                    </span>
                                    <span class="text">Setor</span>
                                </button>
                            </center>

                        </div>
                    </div>
                </div>

                <div class="col-xl-8 col-lg-7">
                    <div class="card shadow mb-8">
                        <div class="card-body">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th scope="col">No</th>
                                        <th scope="col">Tanggal</th>
                                        <th scope="col">Setor</th>
                                        <th scope="col">Tarik</th>
                                        <th scope="col">Saldo</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($transaksi as $t)
                                    <tr>
                                        <td>{{$loop->index}}</td>
                                        <td>{{$t->created_at}}</td>
                                        <td>{{$t->jumlah}}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                @endif
            </div>

        </div>

    </div>
</div>

<div class="modal" tabindex="-1" id="myModal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Masukan Password</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label for="exampleInputPassword1">Password</label>
                    <input type="password" class="form-control" id="exampleInputPassword1">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary">Ok</button>
            </div>
        </div>
    </div>
</div>