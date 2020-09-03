<div class="container">

    <div class="card o-hidden border-0 shadow-lg my-5">
        <div class="card-body p-0">
            <!-- Nested Row within Card Body -->
            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                <h5 class="m-0 font-weight-bold text-primary">Riwayat Transaksi - WASERDA</h5>


            </div>

            <div class="row">
                <div class="col-xl-8 col-lg-7">
                    <div class="card shadow mb-8">
                        <div class="card-body">

                            <div class="form-group row">
                                <label class="col-sm-2 col-form-label">periode</label>
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
                            <br>
                            <div class="row">
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
                                    <button wire:click='filter' class="btn btn-info btn-icon-split">
                                        <span class="icon text-white-50">
                                            <i class="fas fa-filter"></i>
                                        </span>
                                        <span class="text">Filter</span>
                                    </button>
                                </div>
                                &nbsp;
                                <div class="form-group mx-sm-3 mb-2">
                                    <button wire:click='export' class="btn btn-warning btn-icon-split" href="#">
                                        <span class="icon text-white-50">
                                            <i class="fas fa-download"></i>
                                        </span>
                                        <span class="text">Export</span>
                                    </button>
                                </div>

                            </div>



                        </div>
                    </div>
                </div>

                <div class="col-xl-4 col-lg-5">
                    <div class="card shadow mb-4">
                        <div class="card-body">
                            <div class="form-group row">
                                <label for="staticEmail" class="col-sm-6 col-form-label">Jumlah Transaksi</label>
                                <label for="staticEmail" class="col-sm-1 col-form-label">:</label>
                                <label for="staticEmail" class="col-sm-4 col-form-label">{{$transaksi->count()}}</label>
                            </div>
                            <div class="form-group row">
                                <label for="staticEmail" class="col-sm-6 col-form-label">Pendapatan : </label>
                                <label for="staticEmail" class="col-sm-1 col-form-label">:</label>
                                <label for="staticEmail" class="col-sm-4 col-form-label">{{$transaksi->sum('jumlah')}}</label>
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
                            <th>ID. Transaksi</th>
                            <th>Tanggal</th>
                            <th>Nama Nasabah</th>
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
                            <td>{{$t->nasabah->nama}}</td>
                            <td>{{$t->jumlah}}</td>

                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center">tidak ada data</td>
                        </tr>
                        @endforelse
                        @else

                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script type="text/javascript">
    document.addEventListener('DOMContentLoaded', function() {

        window.livewire.on('export', () => {
            window.open('{{route("riwayat.export")}}');
        })

    })
</script>
@endpush