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

                <form class="form-horizontal" wire:submit.prevent="store" enctype="multipart/form-data">

                    <div class="form-group row">
                        <label class="col-md-3 col-form-label" for="text-input">NIS</label>
                        <div class="col-md-9">
                            <input wire:model="nis" class="form-control @error('nis') is-invalid @enderror" id="no_induk" type="text" placeholder="Nomor Induk Pondok . . .">

                            @error('nis')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>

                    </div>
                    <div class="form-group row">
                        <label class="col-md-3 col-form-label" for="text-input">Nama Lengkap</label>
                        <div class="col-md-9">
                            <input wire:model="nama" class="form-control @error('nama') is-invalid @enderror" type="text" name="nama" placeholder=". . ."><span class="help-block">* Sesuai ijazah</span>
                            @error('nama')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-3 col-form-label" for="date-input">tempat Lahir</label>
                        <div class="col-md-9">
                            <input wire:model="tempat_lahir" class="form-control @error('tempat_lahir') is-invalid @enderror" id="text-input" name="tempat_lahir" placeholder="date">
                            @error('tempat_lahir')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-3 col-form-label" for="date-input">Tanggal Lahir</label>
                        <div class="col-md-9">
                            <input wire:model="tanggal_lahir" class="form-control @error('tanggal_lahir') is-invalid @enderror" id="date-input" type="date" name="tgl_lahir" placeholder="date">
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
                            <select wire:model="provinsi" class="custom-select @error('provinsi') is-invalid @enderror" id="provinsi">
                                <option value="">- Provinsi -</option>
                                @foreach($dataProvinsi as $prov)
                                <option value="{{$prov->kode}}">{{$prov->nama}}</option>
                                @endforeach

                            </select>
                            @error('provinsi')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                            @enderror
                            @if($provinsi)
                            <br>
                            <br>
                            <select wire:model="kabupaten" class="custom-select @error('kabupaten') is-invalid @enderror" id="kabupaten">
                                <option value="">- Kota / Kabupaten -</option>
                                @foreach ($dataKabupaten as $kab)
                                <option value="{{$kab->kode}}">{{$kab->nama}}</option>
                                @endforeach
                            </select>
                            @error('kabupaten')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                            @enderror
                            @endif
                            @if($kabupaten)
                            <br>
                            <br>
                            <select wire:model="kecamatan" class="custom-select @error('kecamatan') is-invalid @enderror">
                                <option value="">- Kecamatan -</option>
                                @foreach ($dataKecamatan as $kec)
                                <option value="{{$kec->kode}}">{{$kec->nama}}</option>
                                @endforeach
                            </select>
                            @error('kecamatan')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                            @enderror
                            @endif
                            @if($kecamatan)
                            <br>
                            <br>
                            <select wire:model="desa" class="custom-select @error('desa') is-invalid @enderror">
                                <option value="">- Desa / Kelurahan -</option>
                                @foreach ($dataDesa as $des)
                                <option value="{{$des->kode}}">{{$des->nama}}</option>
                                @endforeach
                            </select>
                            @error('desa')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                            @enderror
                            @endif
                            @if($desa)
                            <br>
                            <br>
                            <textarea wire:model="alamat" class="form-control @error('alamat') is-invalid @enderror" id="textarea-input" name="alamat" rows="3" placeholder=""></textarea>
                            @error('alamat')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                            @enderror
                            @endif
                        </div>
                    </div>


                    <div class="form-group row">
                        <label class="col-md-3 col-form-label">Jenis Kelamin</label>
                        <div class="col-md-9 col-form-label">
                            <div class="form-check form-check-inline mr-1">
                                <input wire:model="jenis_kelamin" class="form-check-input @error('jenis_kelamin') is-invalid @enderror" id="inline-radio1" type="radio" value="Laki-Laki" name="jenis_kelamin">
                                <label class="form-check-label" for="inline-radio1">Laki-laki</label>
                            </div>
                            <div class="form-check form-check-inline mr-1">
                                <input wire:model="jenis_kelamin" class="form-check-input @error('jenis_kelamin') is-invalid @enderror" id="inline-radio2" type="radio" value="Perempuan" name="jenis_kelamin">
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
                        <label class="col-md-3 col-form-label" for="text-input">Orang Tua / Wali</label>
                        <div class="col-md-9">
                            <input wire:model="nama_wali" class="form-control @error('nama_wali') is-invalid @enderror" id="text-input" type="text" name="nama_wali" placeholder="Ex. Ahmad . . .">
                            @error('nama_wali')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>

                    </div>
                    <div class="form-group row">
                        <label class="col-md-3 col-form-label" for="text-input">No Telp / Hp</label>
                        <div class="col-md-9">
                            <input wire:model="telepon" class="form-control @error('telepon') is-invalid @enderror" id="text-input" type="text" name="telepon" placeholder="08128888xxxx">
                            @error('telepon')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-3 col-form-label" for="text-input">Pasword</label>
                        <div class="col-md-9">
                            <input wire:model="pasword" class="form-control @error('pasword') is-invalid @enderror" id="text-input" type="text">
                            @error('pasword')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-3 col-form-label" for="file-input">Pas Foto</label>
                        <div class="col-md-9">
                            <input wire:model="photo" type="file" name="foto" class="form-control-file @error('photo') is-invalid @enderror"><span class="help-block">* Ukuran (3x4) Format .jpg</span>
                            <br>
                            @if ($photo)

                            <img src="{{ $photo->temporaryUrl() }}" height="200px" width="200px" class="img-thumbnail" alt="...">
                            @endif

                            @error('photo')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>

                    </div>



            </div>
            <div class="card-footer">
                <button class="btn btn-sm btn-success" type="submit"> Simpan</button>
                <button class="btn btn-sm btn-danger" type="reset"> Reset</button>
                </form>
            </div>








        </div>
    </div>
</div>
