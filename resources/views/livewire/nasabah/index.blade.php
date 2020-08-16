<div class="row">
    <div class="col-xl col-lg">
        <div class="card shadow mb-4">
            <!-- Card Header - Dropdown -->
            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                <h5 class="m-0 font-weight-bold text-primary">Data Nasabah</h5>
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
                <a class="btn btn-primary btn-icon-split" href="{{route('nasabah.create')}}">
                    <span class="icon text-white-50">
                        <i class="fas fa-plus"></i>
                    </span>
                    <span class="text">Add Data</span>
                </a>
                <a class="btn btn-success btn-icon-split" href="#">
                    <span class="icon text-white-50">
                        <i class="fas fa-upload"></i>
                    </span>
                    <span class="text">Import</span>
                </a>
                <a class="btn btn-warning btn-icon-split" href="#">
                    <span class="icon text-white-50">
                        <i class="fas fa-download"></i>
                    </span>
                    <span class="text">Export</span>
                </a>
                <div class="my-2"></div>
                <br>

                @if (session()->has('pesan'))
                <div class="alert alert-success">
                    {{ session('pesan') }}
                </div>
                @endif

                <div class="table-responsive">
                    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th style="width:100px">Image</th>
                                <th>Nis</th>
                                <th>Nama</th>
                                <th>Alamat</th>
                                <th>Saldo</th>
                                <th>
                                    <center>Action</center>
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($nasabah as $n)
                            <tr>
                                <td>
                                    <img src="{{asset('storage/'.$n->foto)   }}" width="100px" class="img-thumbnail" alt="...">
                                </td>
                                <td>{{$n->nis}}</td>
                                <td>{{$n->nama}}</td>
                                <td>
                                    {{$n->desa->nama}},
                                    {{$n->kecamatan->nama}},
                                    {{$n->kabupaten->nama}},
                                    {{$n->provinsi->nama}}
                                </td>
                                <td>{{$n->saldo}}</td>
                                <td>
                                    <center>
                                        <a href="{{route('nasabah.edit', $n->id)}}" class="btn btn-success btn-circle btn-sm">
                                            <i class="fas fa-edit"></i>
                                        </a>

                                        @if($confirming == $n->id)
                                        <button wire:click="kill('{{ $n->id }}')" type="button" class="btn btn-danger btn-sm">Sure?</button>

                                        @else
                                        <button wire:click="confirmDelete( '{{ $n->id }}' )" type="button" class="btn btn-danger btn-circle btn-sm">
                                            <i class="fas fa-trash"></i>
                                        </button>


                                        @endif

                                    </center>
                                </td>
                            </tr>
                            @endforeach

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>