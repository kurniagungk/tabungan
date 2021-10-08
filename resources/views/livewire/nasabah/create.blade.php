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

                    <div class="form-group row">
                        <label class="col-md-3 col-form-label">Nama Lengkap</label>
                        <div class="col-md-9">
                            <input wire:model="nama" class="form-control @error('nama') is-invalid @enderror"
                                type="text" name="nama" placeholder=". . ."><span class="help-block">* Sesuai
                                ijazah</span>
                            @error('nama')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-3 col-form-label">tempat Lahir</label>
                        <div class="col-md-9">
                            <input wire:model="tempat_lahir"
                                class="form-control @error('tempat_lahir') is-invalid @enderror" id="text-input"
                                name="tempat_lahir" placeholder="date">
                            @error('tempat_lahir')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-3 col-form-label">Tanggal Lahir</label>
                        <div class="col-md-9">
                            <input wire:model="tanggal_lahir"
                                class="form-control @error('tanggal_lahir') is-invalid @enderror" id="date-input"
                                type="date" name="tgl_lahir" placeholder="date">
                            @error('tanggal_lahir')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-3 col-form-label">Alamat</label>
                        <div class="col-md-9">
                            <textarea wire:model="alamat" class="form-control @error('alamat') is-invalid @enderror"
                                id="textarea-input" name="alamat" rows="3" placeholder=""></textarea>
                            @error('alamat')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror

                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-md-3 col-form-label">Jenis Kelamin</label>
                        <div class="col-md-9 col-form-label">
                            <div class="form-check form-check-inline mr-1">
                                <input wire:model="jenis_kelamin"
                                    class="form-check-input @error('jenis_kelamin') is-invalid @enderror"
                                    id="inline-radio1" type="radio" value="Laki-Laki" name="jenis_kelamin">
                                <label class="form-check-label" for="inline-radio1">Laki-laki</label>
                            </div>
                            <div class="form-check form-check-inline mr-1">
                                <input wire:model="jenis_kelamin"
                                    class="form-check-input @error('jenis_kelamin') is-invalid @enderror"
                                    id="inline-radio2" type="radio" value="Perempuan" name="jenis_kelamin">
                                <label class="form-check-label" for="inline-radio2">Perempuan</label>
                            </div>
                            @error('jenis_kelamin')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-md-3 col-form-label">Orang Tua / Wali</label>
                        <div class="col-md-9">
                            <input wire:model="nama_wali" class="form-control @error('nama_wali') is-invalid @enderror"
                                id="text-input" type="text" name="nama_wali" placeholder="Ex. Ahmad . . .">
                            @error('nama_wali')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                    </div>
                    <div class="form-group row">
                        <label class="col-md-3 col-form-label">No Telp / Hp</label>
                        <div class="col-md-9">
                            <input wire:model="telepon" class="form-control @error('telepon') is-invalid @enderror"
                                id="text-input" type="text" name="telepon" placeholder="08128888xxxx">
                            @error('telepon')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>
                    @if ($telepon)
                        <div class="form-group row">
                            <label class="col-md-3 col-form-label">Otomatis Wa</label>
                            <div class="col-md-9">
                                <input type="checkbox" id="wa" wire:model="wa">
                                @error('telepon')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>
                    @endif


                    <div class="form-group row">
                        <label class="col-md-3 col-form-label">Pasword</label>
                        <div class="col-md-9">
                            <input wire:model="pasword" class="form-control @error('pasword') is-invalid @enderror"
                                id="text-input" type="number">
                            @error('pasword')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-md-3 col-form-label">Saldo</label>
                        <div class="col-md-9">
                            <input wire:model="saldo" class="form-control @error('saldo') is-invalid @enderror"
                                id="text-input" type="number">
                            @error('saldo')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-md-3 col-form-label">Pas Foto</label>
                        <div class="col-md-9">
                            <input wire:model="photo" type="file" name="foto"
                                class="form-control-file @error('photo') is-invalid @enderror"><span
                                class="help-block">*
                                Ukuran (3x4) Format .jpg</span>
                            @error('photo')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                    </div>

                    @if ($photo)
                        @php
                            try {
                                $url = $photo->temporaryUrl();
                                $photoStatus = true;
                            } catch (RuntimeException $exception) {
                                $this->photoStatus = false;
                            }
                        @endphp
                        @if ($photoStatus)
                            <div class="row">
                                <label for="foto" class="col-md-3 col-form-label"></label>

                                <div class="col-md-9">

                                    <img src=" {{ $url }}" alt="..." class="img-fluid"
                                        style="height:100px;">

                                </div>

                            </div>
                        @else
                        @endif
                    @endif



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
