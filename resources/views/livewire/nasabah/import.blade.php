<div class="container-fluid">
    <div class="row">
        <div class="col-xl col-lg">
            <div class="card shadow mb-4">
                <!-- Card Header - Dropdown -->
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h5 class="m-0 font-weight-bold text-primary">Data Nasabah</h5>

                </div>
                <!-- Card Body -->
                <div class="card-body">

                    @if (session()->has('pesan'))
                        <div class="alert alert-success">
                            {{ session('pesan') }}
                        </div>
                    @endif

                    <div class="form-group row">
                        <label class="col-md-3 col-form-label">File</label>
                        <div class="col-md-9">
                            <input wire:model="file" class="form-control @error('file') is-invalid @enderror"
                                type="file" name="file" placeholder=". . ."><span class="help-block">* Sesuai
                                ijazah</span>
                            @error('file')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>

                    <div class="row">
                        <label class="col-md-3 col-form-label"></label>
                        <form class="form-horizontal" wire:submit.prevent="store">
                            <button class="btn btn-sm btn-success" type="submit"> Cek

                                <div class="spinner-border" role="status" wire:loading wire:target="store">
                                    <span class="sr-only">Loading...</span>
                                </div>

                            </button>

                        </form>
                    </div>









                </div>
            </div>
        </div>
    </div>
    @if ($data)
        <div class="row">
            <div class="col-xl col-lg">
                <div class="card shadow mb-4">
                    <!-- Card Header - Dropdown -->
                    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                        <h5 class="m-0 font-weight-bold text-primary">Data Nasabah</h5>
                        @if (!$errors->any())
                            <button class="btn btn-sm btn-success" type="save" wire:click="save">
                                Save
                                <div class="spinner-border" role="status" wire:loading wire:target="save">
                                    <span class="sr-only">Loading...</span>
                                </div>
                            </button>
                        @endif

                    </div>
                    <!-- Card Body -->
                    <div class="card-body">




                        <div class="table-responsive">
                            <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Rekening</th>
                                        <th>Rekening</th>
                                        <th>Alamat</th>
                                        <th>Saldo</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($data as $d)
                                        <tr class="@error('data.' . $loop->index . '.' . 'no') table-danger @enderror">
                                            <td>{{ $loop->index + 1 }}</td>
                                            <td>{{ $d['no'] }}</td>
                                            <td>{{ $d['nama'] }}</td>
                                            <td>{{ $d['alamat'] }}</td>
                                            <td>{{ $d['saldo'] }}</td>
                                        </tr>
                                    @endforeach

                                </tbody>
                            </table>

                        </div>









                    </div>
                </div>
            </div>
        </div>
    @endif

</div>
