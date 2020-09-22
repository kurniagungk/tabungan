<div class="row">
    <div class="col-xl col-lg">
        <div class="card shadow mb-4">
            <!-- Card Header - Dropdown -->
            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                <h5 class="m-0 font-weight-bold text-primary">Mutasi Santri</h5>
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

                <form wire:submit.prevent="cari">
                    <div class="input-group">
                        <input wire:model.lazy="search" autofocus type="text" id="cari" class="form-control " placeholder="Input Nomor Induk...">
                        <div class="input-group-append">
                            <button class="btn btn-primary" type="sumbit">
                                <i class="fas fa-search fa-sm"></i>
                            </button>
                        </div>
                    </div>
                </form>

                @if($santri)

                <br>
                <div class="row">
                    <div class="col-xl-7 col-lg-7">
                        <div class="card shadow mb-8">
                            <div class="card-body">

                                <form wire:submit.prevent="filter">

                                    <div class="form-group row">
                                        <label class="col-sm-3 col-form-label">Nama</label>
                                        <label class="col-sm-3 col-form-label">{{$santri->nama}}</label>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-sm-3 col-form-label">periode</label>
                                        <div class="col-sm-4">
                                            <input wire:model='awal' type="date" class="form-control @error('awal') is-invalid @enderror" id="">
                                            @error('awal')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                            @enderror
                                        </div>
                                        <div class="col-sm-4">
                                            <input wire:model='akhir' type="date" class="form-control @error('akhir') is-invalid @enderror" id="">
                                            @error('akhir')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label for="staticEmail2" class="col-sm-3 col-form-label">Jenis Transaksi</label>

                                        <div class="col-sm-9">
                                            <div class="form-check form-check-inline">
                                                <input wire:model="jenisTransaksi" class="form-check-input" type="radio" name="inlineRadioOptions" id="inlineRadio1" value="" checked>
                                                <label class="form-check-label" for="inlineRadio1">Semua</label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <input wire:model="jenisTransaksi" class="form-check-input" type="radio" name="inlineRadioOptions" id="inlineRadio2" value="setor">
                                                <label class="form-check-label" for="inlineRadio2">Setor</label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <input wire:model="jenisTransaksi" class="form-check-input" type="radio" name="inlineRadioOptions" id="inlineRadio3" value="tarik">
                                                <label class="form-check-label" for="inlineRadio3">Tarik</label>
                                            </div>
                                        </div>
                                    </div>


                                    <div class="form-group row">
                                        <label class="col-sm-3 col-form-label">mitra</label>
                                        <div class="col-sm-4">
                                            <select wire:model='selectMitra' id="inputState" class="form-control">
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
                                    <label for="staticEmail" class="col-sm-4 col-form-label">Total Setor : </label>
                                    <label for="staticEmail" class="col-sm-1 col-form-label">:</label>
                                    <label for="staticEmail" class="col-sm-7 col-form-label">{{$totalSetor??''}}</label>
                                </div>
                                <div class="form-group row">
                                    <label for="staticEmail" class="col-sm-4 col-form-label">Total Tarik : </label>
                                    <label for="staticEmail" class="col-sm-1 col-form-label">:</label>
                                    <label for="staticEmail" class="col-sm-7 col-form-label">{{$totalTarik??''}}</label>
                                </div>
                                <div class="form-group row">
                                    <label for="staticEmail" class="col-sm-4 col-form-label">Saldo : </label>
                                    <label for="staticEmail" class="col-sm-1 col-form-label">:</label>
                                    <label for="staticEmail" class="col-sm-3 col-form-label">{{$totalSetor - $totalTarik ??''}}</label>
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
                <br>
                <div class="table-responsive">
                    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>ID Transaksi</th>
                                <th>Tanggal</th>
                                <th>Rekening</th>
                                <th>Nama</th>
                                <th>Jenis</th>
                                <th>Sumber</th>
                                <th>Jumlah</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if($transaksi)
                            @forelse ($transaksi as $t)
                            <tr>
                                <td>{{$loop->index +1}}</td>
                                <td>{{substr($t->id,0,8)}}</td>
                                <td>{{$t->created_at}}</td>
                                <td>{{$t->nasabah->nis}}</td>
                                <td>{{$t->nasabah->nama}}</td>
                                <td>{{$t->jenis}}</td>
                                <td>{{$t->mitra->name}}</td>
                                <td>{{$t->jumlah}}</td>

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
                @endif
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script type="text/javascript">
    window.livewire.on('export', () => {
        window.open('{{route("export.mutasi")}}');
    })
</script>
@endpush
