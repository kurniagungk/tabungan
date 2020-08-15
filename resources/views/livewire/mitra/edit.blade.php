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
                <form wire:submit.prevent="update">
                    <div class="form-group row">


                        <label for="inputEmail3" class="col-sm-2 col-form-label">Nama</label>
                        <div class="col-sm-10">
                            <input wire:model="nama" type="text" class="form-control @error('nama') is-invalid @enderror">
                        </div>
                        @error('nama')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                        @enderror

                    </div>
                    <div class="form-group row">
                        <label for="inputEmail3" class="col-sm-2 col-form-label">Email</label>
                        <div class="col-sm-10">
                            <input wire:model="email" type="email" class="form-control @error('email') is-invalid @enderror">
                        </div>
                        @error('email')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                        @enderror
                    </div>
                    <div class="form-group row">
                        <label for="inputEmail3" class="col-sm-2 col-form-label">Password</label>
                        <div class="col-sm-10">
                            <input wire:model="password" type="password" class="form-control @error('password') is-invalid @enderror">
                        </div>
                        @error('password')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                        @enderror
                    </div>

                    <div class="form-group row">
                        <label for="inputEmail3" class="col-sm-2 col-form-label">Confirm Password</label>
                        <div class="col-sm-10">
                            <input wire:model="repassword" type="password" class="form-control @error('repassword') is-invalid @enderror">
                        </div>
                        @error('repassword')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                        @enderror
                    </div>

                    <br>
                    <button class="btn btn-success btn-icon-split" type="submit">
                        <span class="icon text-white-50">
                            <i class="fas fa-plus"></i>
                        </span>
                        <span class="text">Add Data</span>
                    </button>
                    <a class="btn btn-danger btn-icon-split" href="/mitra">
                        <span class="icon text-white-50">
                            <i class="fas fa-times"></i>
                        </span>
                        <span class="text">Cencel</span>
                    </a>

                </form>







            </div>
        </div>
    </div>
</div>
