<div class="container-fluid">


    <div class="row">
        <div class="col-xl-6 col-lg-12">
            <div class="card shadow mb-4">
                <!-- Card Header - Dropdown -->
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">Laporan Biaya</h6>
                    <div class="dropdown no-arrow">
                        <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown"
                            aria-haspopup="true" aria-expanded="false">
                            <i class="fas fa-ellipsis-v fa-sm fa-fw text-gray-400"></i>
                        </a>
                    </div>
                </div>
                <!-- Card Body -->
                <div class="card-body px-5">

                    <form wire:submit="laporan">

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


                            <div class="input-group-prepend col-sm-12 col-lg-10">
                                <input type="month" class="form-control" wire:model.live="tanggal">
                            </div>

                            <div class="input-group-prepend col-sm-12 col-lg-2">
                                <button class="btn btn-primary" type="submit"><i class="fa fa-search"
                                        aria-hidden="true"></i> Lihat</button>
                            </div>
                            @error('nis')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>



                    </form>

                </div>



            </div>
        </div>
        <div class="col-xl-6 col-lg-10">
            <div class="card shadow mb-4">
                <!-- Card Header - Dropdown -->
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">Data</h6>
                    <div class="dropdown no-arrow">
                        <button class="btn btn-success" wire:click="export">Export</button>
                    </div>
                </div>
                <!-- Card Body -->
                <div class="card-body px-5">



                    <table class="table">
                        <thead>
                            <tr>
                                <th scope="col">LAPORAN TABUNGAN AL-KAHFI {{ $tanggal }}</th>
                                <th></th>
                            </tr>

                        </thead>
                        <tbody>
                            <tr>
                                <td>Tanggal Cetak</td>
                                <td>{{ $cetak }}</td>
                            </tr>
                            <tr>
                                <td>Total Saldo Awal</td>
                                <td>Rp. {{ number_format($saldo + $biaya, 2, ',', '.') }}</td>
                            </tr>
                            <tr>
                                <td>Jumlah Nasabah</td>
                                <td>{{ $nasabah }}</td>
                            </tr>
                            <tr>
                                <td>BIAYA ADMIN</td>
                                <td>Rp. {{ number_format($biaya, 2, ',', '.') }}</td>
                            </tr>
                            <tr>
                                <td>Total Saldo Akhir</td>
                                <td>Rp. {{ number_format($saldo, 2, ',', '.') }}</td>
                            </tr>

                        </tbody>
                    </table>

                </div>



            </div>
        </div>
    </div>













</div>
