<body class="bg-gradient-primary">
    <div class="container">
        <div class="card o-hidden border-0 shadow-lg my-5">
            <div class="card-body p-0">
                <!-- Nested Row within Card Body -->
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h5 class="m-0 font-weight-bold text-primary">Riwayat Transaksi - WASERDA</h5>
                </div>
            </div>

            <div class="row">
                <div class="col-xl-8 col-lg-7">
                    <div class="card shadow mb-8">
                        <div class="card-body">

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
                            <br>
                            <form class="form-inline">
                                <div class="form-group mb-2">
                                    <a href="mitrapay" class="btn btn-success btn-icon-split">
                                        <span class="icon text-white-50">
                                            <i class="fas fa-arrow-right"></i>
                                        </span>
                                        <span class="text">Transaksi Baru</span>
                                    </a>
                                </div>
                                &nbsp; &nbsp; &nbsp; &nbsp;
                                <div class="form-group mx-sm-3 mb-2">
                                    <a class="btn btn-info btn-icon-split" href="#">
                                        <span class="icon text-white-50">
                                            <i class="fas fa-filter"></i>
                                        </span>
                                        <span class="text">Filter</span>
                                    </a>
                                </div>
                                &nbsp;
                                <div class="form-group mx-sm-3 mb-2">
                                    <a class="btn btn-warning btn-icon-split" href="#">
                                        <span class="icon text-white-50">
                                            <i class="fas fa-download"></i>
                                        </span>
                                        <span class="text">Export</span>
                                    </a>
                                </div>
                            </form>

                        </div>
                    </div>
                </div>

                <div class="col-xl-4 col-lg-5">
                    <div class="card shadow mb-4">
                        <div class="card-body">
                            <div class="form-group row">
                                <label for="staticEmail" class="col-sm-7 col-form-label">Jumlah Transaksi : </label>
                            </div>
                            <div class="form-group row">
                                <label for="staticEmail" class="col-sm-7 col-form-label">Pendapatan : </label>
                            </div>
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
                            <th>Nama Nasabah</th>
                            <th>Jumlah</th>
                            <th>Saldo</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>TR-001</td>
                            <td>23-09-2020 11.50</td>
                            <td>agung</td>
                            <td>Rp. 100.000</td>
                            <td>Rp. 1.000.000</td>
                        </tr>
                        <tr>
                            <td>TR-002</td>
                            <td>23-09-2020 11.50</td>
                            <td>abdul</td>
                            <td>Rp. 100.000</td>
                            <td>Rp. 1.100.000</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    </div>

    <!-- Bootstrap core JavaScript-->
    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- Core plugin JavaScript-->
    <script src="vendor/jquery-easing/jquery.easing.min.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="js/sb-admin-2.min.js"></script>

</body>