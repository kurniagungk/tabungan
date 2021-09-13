<div class="container-fluid">
    <div class="row">
        <div class="col-xl col-lg">
            <div class="card shadow mb-4">
                <!-- Card Header - Dropdown -->
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h5 class="m-0 font-weight-bold text-primary">Setting Tabungan</h5>

                </div>


                <!-- Card Body -->
                <div class="card-body">

                    @if (session()->has('pesan'))
                        <div class="alert alert-success">
                            {{ session('pesan') }}
                        </div>
                    @endif

                    <div class="form-group row">
                        <label class="col-md-3 col-form-label">Tanggal Pengambilan Biaya Admin</label>
                        <div class="col-md-9">
                            <input wire:model="tanggal" class="form-control @error('tanggal') is-invalid @enderror"
                                type="number" name="tanggal" placeholder=". . ."><span class="help-block"></span>
                            @error('tanggal')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-3 col-form-label">Biaya Admin Per Bulan</label>
                        <div class="col-md-9">
                            <input wire:model="biaya" class="form-control @error('biaya') is-invalid @enderror"
                                id="text-input" name="biaya" placeholder="biaya admin" type="number">
                            @error('biaya')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-3 col-form-label">Saldo Minimal</label>
                        <div class="col-md-9">
                            <input wire:model="minimal" type="number" type="number"
                                class="form-control @error('minimal') is-invalid @enderror" id="text-input"
                                name="tempat_lahir" placeholder="minimal">
                            @error('minimal')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>



                </div>
                <div class="card-footer">
                    <form class="form-horizontal" wire:submit.prevent="store">
                        <button class="btn btn-sm btn-success" type="submit"> Simpan</button>
                        <a href='/nasabah'><button href="nasabah" class="btn btn-sm btn-danger" type="reset">
                                Cencel</button></a>
                    </form>
                </div>








            </div>
        </div>
    </div>
</div>
