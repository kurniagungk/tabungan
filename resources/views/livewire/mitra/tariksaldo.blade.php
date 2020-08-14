<div class="row">
    <div class="col-xl col-lg">
        <div class="card shadow mb-4">
            <!-- Card Header - Dropdown -->
            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                <h5 class="m-0 font-weight-bold text-primary">Tarik Dana Tunai Mitra</h5>
                <div class="dropdown no-arrow">
                    <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="fas fa-ellipsis-v fa-sm fa-fw text-gray-400"></i>
                    </a>
                    <div class="dropdown-menu dropdown-menu-right shadow animated--fade-in" aria-labelledby="dropdownMenuLink">
                        <div class="dropdown-header">Dropdown Header:</div>
                        <a class="dropdown-item" href="#">Action</a>
                        <a class="dropdown-item" href="#">Another action</a>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item" href="#">Something else here</a>
                    </div>
                </div>
            </div>
            <!-- Card Body -->
            <div class="card-body">

                <div>
                    @if (session()->has('pesan'))
                    <div class="alert alert-success">
                        {{ session('pesan') }}
                    </div>
                    @endif
                </div>


                <div class="form-group row">
                    <label for="staticEmail2" class="col-sm-2 col-form-label">Mitra</label>
                    <div class="form-group mx-sm-3 mb-2">
                        <select wire:model="mitraId" id="inputState" class="form-control @error('mitraId') is-invalid @enderror">
                            <option value=""> -Pilih Salah Satu- </option>
                            @foreach($mitra as $m)
                            <option value="{{$m->id}}"> {{$m->nama}} </option>
                            @endforeach

                        </select>
                        @error('mitraId')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                        @enderror
                    </div>
                </div>


                <div class="form-group row">
                    <label class="col-sm-2 col-form-label">Jumlah</label>
                    <div class="form-group mx-sm-3 mb-2">
                        <input wire:model="jumlah" type="text" class="form-control @error('jumlah') is-invalid @enderror" id="inputPassword2" placeholder="Rp.">
                    </div>
                    @error('jumlah')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                    @enderror
                </div>



                <div class="form-group row">
                    <label class="col-sm-2 col-form-label">Keterangan</label>
                    <div class="form-group mx-sm-3 mb-2">
                        <textarea wire:model="keterangan" class="form-control @error('keterangan') is-invalid @enderror" id="exampleFormControlTextarea1" rows="3"></textarea>
                    </div>
                    @error('keterangan')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                    @enderror
                </div>


                <center>
                    <button wire:click="tarik" class="btn btn-warning btn-icon-split" type="button">
                        <span class="icon text-white-50">
                            <i class="fas fa-download"></i>
                        </span>
                        <span class="text">Tarik Dana</span>
                    </button>
                    <div class="my-2"></div>
                </center>
                <br>

                <div class="table-responsive">
                    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>ID. Transaksi</th>
                                <th>Tanggal</th>
                                <th>Nama Mitra</th>
                                <th>Jumlah</th>
                                <th>Keperluan</th>
                                <th>
                                    <center>Action</center>
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($jurnal as $j)
                            <tr>
                                <td>{{substr($j->id,0,8)}}</td>
                                <td>{{$j->created_at}}</td>
                                <td>{{$j->mitra->nama}}</td>
                                <td>{{$j->jumlah}}</td>
                                <td>{{$j->keterangan}}</td>
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
                            @empty
                            <tr>
                                <td colspan="6" class="text-center">Tidak ada data</td>
                            </tr>

                            @endforelse


                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
