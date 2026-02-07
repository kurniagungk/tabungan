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
                    <div class="col-xl-7 col-lg-7">
                        <div class="card shadow mb-8">
                            <div class="card-body">

                                <form wire:submit="filter">

                                    <div class="form-group row">
                                        <label class="col-sm-2 col-form-label">periode</label>
                                        <div class="col-sm-4">
                                            <input wire:model.live='awal' type="date" class="form-control @error('awal') is-invalid @enderror" id="">
                                            @error('awal')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                            @enderror
                                        </div>
                                        <div class="col-sm-4">
                                            <input wire:model.live='akhir' type="date" class="form-control @error('akhir') is-invalid @enderror" id="">
                                            @error('akhir')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                            @enderror
                                        </div>
                                    </div>




                                    <div class="form-group row">
                                        <label class="col-sm-2 col-form-label">mitra</label>
                                        <div class="col-sm-4">
                                            <select wire:model.live='selectMitra' id="inputState" class="form-control">
                                                <option value="">Semua </option>
                                                @foreach($mitra as $m)
                                                <option value="{{$m->id}}">{{$m->name}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>



                                    <br>

                                    <center>
                                        <button type="submit" class="btn btn-info btn-icon-split">
                                            <span class="icon text-white-50">
                                                <i class="fas fa-filter"></i>
                                            </span>
                                            <span class="text">Filter</span>
                                        </button>
                                    </center>

                                </form>
                            </div>
                        </div>
                    </div>

                    <div class="col-xl-5 col-lg-5">
                        <div class="card shadow mb-4">
                            <div class="card-body">
                                <div class="form-group row">
                                    <label for="staticEmail" class="col-sm-4 col-form-label">Periode</label>
                                    <label for="staticEmail" class="col-sm-1 col-form-label">:</label>
                                    <label for="staticEmail" class="col-sm-3 col-form-label">{{$awal??''}}</label>
                                    <label for="staticEmail" class="col-sm-1 col-form-label"> - </label>
                                    <label for="staticEmail" class="col-sm-3 col-form-label">{{$akhir??''}}</label>
                                </div>
                                <div class="form-group row">
                                    <label for="staticEmail" class="col-sm-4 col-form-label">Total Tarik : </label>
                                    <label for="staticEmail" class="col-sm-1 col-form-label">:</label>
                                    <label for="staticEmail" class="col-sm-7 col-form-label">{{$totalTarik??''}}</label>
                                </div>


                                <center>
                                    <button wire:click='export' class="btn btn-warning btn-icon-split">
                                        <span class="icon text-white-50">
                                            <i class="fas fa-download"></i>
                                        </span>
                                        <span class="text">Export</span>
                                    </button>
                                </center>

                            </div>
                        </div>
                    </div>


                </div>

                <div class="table-responsive">
                    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>ID. Transaksi</th>
                                <th>Tanggal</th>
                                <th>Nama Mitra</th>
                                <th>Jumlah</th>
                                <th>Keperluan</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if($jurnal)

                            @forelse ($jurnal as $j)
                            <tr>
                                <td>{{$loop->index +1}}</td>
                                <td>{{substr($j->id,0,8)}}</td>
                                <td>{{$j->created_at}}</td>
                                <td>{{$j->mitra->name}}</td>
                                <td>{{$j->jumlah}}</td>
                                <td>{{$j->keterangan}}</td>

                            </tr>
                            @empty
                            @endforelse
                            @else
                            <tr>
                                <td></td>
                            </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')

@script

<script type="text/javascript">
    Livewire.on('export', () => {
        window.open('{{route("export.mitra")}}');
    })
</script>
@endscript

@endpush
