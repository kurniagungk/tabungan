<div class="container-fluid">
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
                    <a class="btn btn-primary btn-icon-split" href="{{ route('nasabah.create') }}">
                        <span class="icon text-white-50">
                            <i class="fas fa-plus"></i>
                        </span>
                        <span class="text">Add Data</span>
                    </a>
                    <a class="btn btn-warning btn-icon-split" href="{{ route('nasabah.import') }}">
                        <span class="icon text-white-50">
                            <i class="fas fa-upload"></i>
                        </span>
                        <span class="text">Import</span>
                    </a>


                    <button class="btn btn-success btn-icon-split" wire:click="export">
                        <span class="icon text-white-50">
                            <i class="fas fa-download"></i>
                        </span>
                        <span class="text">Export</span>
                    </button>

                    <div class="my-2"></div>

                    <br>


                    @if (session()->has('pesan'))
                    <div class="alert alert-success">
                        {{ session('pesan') }}
                    </div>
                    @endif
                    <div class="row">
                        <div class="col-lg-2">
                            <div class="form-group">
                                <select wire:model="perpage" class="form-control" id="exampleFormControlSelect1">
                                    <option value="10">10</option>
                                    <option value="20">20</option>
                                    <option value="50">50</option>
                                    <option value="75">75</option>
                                    <option value="100">100</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-3">
                            <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text" id="basic-addon1">
                                        <i class="fas fa-search"></i>
                                    </span>
                                </div>
                                <input wire:model="search" type="text" class="form-control" placeholder="Username" aria-label="Username" aria-describedby="basic-addon1">
                            </div>
                        </div>

                    </div>


                    <div class="table-responsive">
                        <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th style="width:100px">Image</th>
                                    <th> <a wire:click.prevent="sortBy('nis')" role="button">Rekening <i class="fas fa-arrows-alt-v" @if ($sortField=='no_induk' ) style="color:red" @endif></i></a>
                                    </th>
                                    <th><a wire:click.prevent="sortBy('nama')" role="button">Nama <i class="fas fa-arrows-alt-v" @if ($sortField=='nama' ) style="color:red" @endif></i></a>
                                    </th>
                                    <th>Alamat</th>
                                    <th>Saldo</th>
                                    <th>
                                        <center>Action</center>
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($nasabah as $n)
                                <tr>
                                    <td>{{ $loop->index + 1 }}</td>
                                    <td>
                                        <img src="{{ asset('storage/' . $n->foto) }}" width="100px" class="img-thumbnail" alt="...">
                                    </td>
                                    <td>{{ $n->rekening }}</td>
                                    <td>{{ $n->nama }}</td>
                                    <td>
                                        {{ $n->alamat }}
                                    </td>
                                    <td>{{ $n->saldo }}</td>
                                    <td>
                                        <center>
                                            <a href="{{ route('nasabah.edit', $n->id) }}" class="btn btn-success btn-circle btn-sm">
                                                <i class="fas fa-edit"></i>
                                            </a>

                                            @if ($confirming == $n->id)
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
                        {{ $nasabah->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
