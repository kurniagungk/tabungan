@extends('layouts.dashboard')

@section('content')
<div class="row">
    <div class="col-xl col-lg">
        <div class="card shadow mb-4">
            <!-- Card Header - Dropdown -->
            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                <h5 class="m-0 font-weight-bold text-primary">Jurnal Umum</h5>
                <div class="dropdown no-arrow">
                    <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="fas fa-ellipsis-v fa-sm fa-fw text-gray-400"></i>
                    </a>
                    <div class="dropdown-menu dropdown-menu-right shadow animated--fade-in" aria-labelledby="dropdownMenuLink">
                        <div class="dropdown-header">Export File :</div>
                        <a class="dropdown-item" href="#">PDF</a>
                        <a class="dropdown-item" href="#">Excell</a>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item" href="#">Something else here</a>
                    </div>
                </div>
            </div>
            <!-- Card Body -->
            <div class="card-body">

                <div class="row">
                    <div class="col-xl-8 col-lg-7">
                        <div class="card shadow mb-8">
                            <div class="card-body">
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">
                                            <i class="fas fa-calendar-alt"></i>
                                        </span>
                                    </div>
                                    <input type="number" class="form-control" autocomplete="off" placeholder="Periode . . .">
                                </div>

                            </div>
                        </div>
                    </div>

                    <div class="col-xl-4 col-lg-5">
                        <div class="card shadow mb-4">
                            <div class="card-body">
                                <div class="form-group row">
                                    <label for="staticEmail" class="col-sm-5 col-form-label">Periode : </label>
                                </div>
                                <div class="form-group row">
                                    <label for="staticEmail" class="col-sm-7 col-form-label">Setor : </label>
                                </div>
                                <div class="form-group row">
                                    <label for="staticEmail" class="col-sm-7 col-form-label">Tarik : </label>
                                </div>
                                <div class="form-group row">
                                    <label for="staticEmail" class="col-sm-7 col-form-label">Saldo : </label>
                                </div>
                            </div>
                        </div>
                    </div>


                </div>

                <div class="table-responsive">
                    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Tanggal</th>
                                <th>Rekening</th>
                                <th>Nama</th>
                                <th>Sekolah</th>
                                <th>Jenis</th>
                                <th>Sumber</th>
                                <th>Jumlah</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>1</td>
                                <td>12-11-2020 15.20</td>
                                <td>NB-001</td>
                                <td>Abdul</td>
                                <td>SMK</td>
                                <td>Tarik</td>
                                <td>Koperasi</td>
                                <td>50.000</td>
                            </tr>
                            <tr>
                                <td>2</td>
                                <td>12-11-2020 10:15</td>
                                <td>NB-001</td>
                                <td>Abdul</td>
                                <td>SMK</td>
                                <td>Setor</td>
                                <td>Tunai</td>
                                <td>100.000</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
