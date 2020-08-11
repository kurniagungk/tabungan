@extends('layouts.dashboard')

@section('content')
<div class="row">
    <div class="col-xl col-lg">
        <div class="card shadow mb-4">
            <!-- Card Header - Dropdown -->
            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                <h5 class="m-0 font-weight-bold text-primary">Jurnal Mitra</h5>
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

                                <!-- <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">
                                            <i class="fas fa-calendar-alt"></i>
                                        </span>
                                    </div>
                                    <input type="number" class="form-control" autocomplete="off" placeholder="Periode . . .">
                                </div> -->


                                <form class="form-inline">
                                    <div class="form-group mb-2">
                                        <label class="sr-only">Email</label>
                                        <input type="text" class="form-control-plaintext" value="Periode">
                                    </div>
                                    <div class="form-group mx-sm-3 mb-2">
                                        <label class="sr-only">Password</label>
                                        <input type="date" class="form-control" id="inputPassword2" placeholder="Awal">
                                    </div>
                                    <div class="form-group mx-sm-3 mb-2">

                                        <label class="sr-only">Password</label>
                                        <input type="date" class="form-control" id="inputPassword2" placeholder="Ahir">
                                    </div>
                                </form>


                                <form class="form-inline">
                                    <div class="form-group mb-2">
                                        <label for="staticEmail2" class="sr-only">Mitra</label>
                                        <input type="text" readonly class="form-control-plaintext" value="Mitra">
                                    </div>
                                    <div class="form-group col-md-4">
                                        <select id="inputState" class="form-control">
                                            <option selected>Semua </option>
                                            <option>Koperasi</option>
                                            <option>Warnet</option>
                                        </select>
                                    </div>
                                </form>

                                <br>
                                <form>
                                    <center>
                                        <a class="btn btn-info btn-icon-split" href="#">
                                            <span class="icon text-white-50">
                                                <i class="fas fa-filter"></i>
                                            </span>
                                            <span class="text">Filter</span>
                                        </a>
                                    </center>
                                </form>

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
                                    <label for="staticEmail" class="col-sm-7 col-form-label">Total Tarik : </label>
                                </div>
                                <div class="form-group row">
                                    <label for="staticEmail" class="col-sm-7 col-form-label">Saldo : </label>
                                </div>
                                <form>
                                    <center>
                                        <a class="btn btn-warning btn-icon-split" href="#">
                                            <span class="icon text-white-50">
                                                <i class="fas fa-download"></i>
                                            </span>
                                            <span class="text">Export</span>
                                        </a>
                                    </center>
                                </form>
                            </div>
                        </div>
                    </div>


                </div>

                <div class="table-responsive">
                    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>ID. Transaksi</th>
                                <th>Tanggal</th>
                                <th>Nama Mitra</th>
                                <th>Jumlah</th>
                                <th>Keperluan</th>
                                <th>Saldo</th>
                                <th>
                                    <center>Action</center>
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>TR-001</td>
                                <td>23-09-2020 11.50</td>
                                <td>Koperasi</td>
                                <td>Rp. 100.000</td>
                                <td>Setor Ndalem</td>
                                <td>Rp. 1.000.000</td>
                                <td>
                                    <center>
                                        <a href="#" class="btn btn-success btn-circle btn-sm">
                                            <i class="fas fa-edit"></i>
                                        </a>

                                        <a href="#" class="btn btn-danger btn-circle btn-sm">
                                            <i class="fas fa-trash"></i>
                                        </a></center>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection