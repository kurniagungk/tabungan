<div class="container-fluid">


    <div class="row">
        <div class="col-xl-12 col-lg-12">
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

                    <form wire:submit.prevent="laporan">

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


                            <div class="input-group-prepend col-sm-12 col-lg-5">
                                <input type="date" class="form-control" wire:model="dari">
                            </div>

                            <div class="input-group-prepend col-sm-12 col-lg-5">
                                <input type="date" class="form-control" wire:model="sampai">
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

    </div>

    @if ($show)
        <div class="row">
            <div class="col-xl-12 col-lg-12">
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
                                    <th colspan="2" class="text">PERIODE</th>
                                    <th colspan="7">
                                        {{ $dari }} - {{ $sampai }}
                                    </th>
                                </tr>
                                <tr>
                                    <th colspan="2" class="text">Total Tarik</th>
                                    <th colspan="6">
                                        {{ $transaksi->sum('credit') }}
                                    </th>
                                </tr>
                                <tr>
                                    <th colspan="2" class="text">Total Setor</th>
                                    <th colspan="6">
                                        {{ $transaksi->sum('debit') }}
                                    </th>
                                </tr>
                                <tr>
                                    <th colspan="2" class="text">Total</th>
                                    <th colspan="6">
                                        {{ $transaksi->sum('debit') - $transaksi->sum('credit') }}
                                    </th>
                                </tr>
                                <tr>
                                    <th colspan="9"></th>
                                </tr>
                                <tr>
                                    <th>No</th>
                                    <th>Tanggal</th>
                                    <th>Rekening</th>
                                    <th>Nama</th>
                                    <th>Ambil</th>
                                    <th>Simpan</th>
                                    <th>Total</th>
                                    <th>Keterangan</th>
                                </tr>

                            </thead>

                            <tbody>

                                @php
                                    $jumlah = 0;
                                @endphp

                                @foreach ($transaksi as $tr)

                                    @php
                                        $jumlah += $tr->debit - $tr->credit;
                                    @endphp

                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $tr->created_at }}</td>
                                        <td>{{ $tr->nasabah->rekening }}</td>
                                        <td>{{ $tr->nasabah->nama }}</td>
                                        <td>{{ $tr->credit }}</td>
                                        <td>{{ $tr->debit }}</td>
                                        <td>{{ $jumlah }}</td>
                                        <td>{{ $tr->keterangan }}</td>
                                    </tr>


                                @endforeach
                            </tbody>


                        </table>

                    </div>



                </div>
            </div>
        </div>
    @endif











</div>
