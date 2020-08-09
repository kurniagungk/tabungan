@extends('layouts.dashboard')

@section('content')

<div class="col-xl col-lg">
    <div class="card shadow mb-4">
        <!-- Card Header - Dropdown -->
        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
            <h6 class="m-0 font-weight-bold text-primary">Tarik Dana</h6>
            <div class="dropdown no-arrow">
                <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i class="fas fa-ellipsis-v fa-sm fa-fw text-gray-400"></i>
                </a>
            </div>
        </div>
        <!-- Card Body -->
        <div class="card-body">
            <div class="row">
                <div class="col-xl-4 col-lg-5">
                    <div class="card shadow mb-4">
                        <div class="card-header py-2">
                            <h6 class="m-0 font-weight text-primary">
                                <center><button type="button" class="btn btn-primary btn-icon-split" data-toggle="modal" data-target="#exampleModal">
                                        <span class="icon text-white-50">
                                            <i class="fas fa-user"></i>
                                        </span>
                                        <span class="text">Input Nasabah</span>
                                    </button></center>
                            </h6>
                        </div>
                        <div class="card-body">
                            <div class="form-group row">
                                <label for="staticEmail" class="col-sm-5 col-form-label">Rekening : </label>
                            </div>
                            <div class="form-group row">
                                <label for="staticEmail" class="col-sm-7 col-form-label">Nama : </label>
                            </div>
                            <div class="form-group row">
                                <label for="staticEmail" class="col-sm-7 col-form-label">Sekolah : </label>
                            </div>
                            <div class="form-group row">
                                <label for="staticEmail" class="col-sm-7 col-form-label">Saldo : </label>
                            </div>
                            <div class="form-group row">
                                <input type="search" class="form-control form-control-sm" placeholder="Rp. " aria-controls="dataTable">
                            </div>
                            <center>
                                <a class="btn btn-warning btn-icon-split" href="#">
                                    <span class="icon text-white-50">
                                        <i class="fas fa-download"></i>
                                    </span>
                                    <span class="text">Tarik</span>
                                </a>
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
                                    <tr>
                                        <td>2</td>
                                        <td>2020-08-03 19:53</td>
                                        <td>Rp. 0</td>
                                        <td>Rp. 50.000 </td>
                                        <td>Rp. 50.000</td>
                                    </tr>
                                    <tr>
                                        <td>1</td>
                                        <td>2020-08-02 19:53</td>
                                        <td>Rp. 100.000</td>
                                        <td>Rp. 0 </td>
                                        <td>Rp. 100.000</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection