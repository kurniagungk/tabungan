<div class="card shadow mb-4">
    <div class="card-header py-2">
        <h6 class="m-0 font-weight text-primary">
            <b>
                Account
            </b>
        </h6>
    </div>
    <div class="card-body">

        @if (session()->has('status'))
        <div class="alert alert-success">
            {{ session('status') }}
        </div>
        @endif



        <div class="form-group row">
            <label for="staticEmail" class="col-sm-2 col-form-label">Name</label>
            <div class="col-sm-10">
                <!--   -->
                <input wire:model="name" type="text" class="form-control form-control-user  @error('name') is-invalid @enderror">
                @error('name')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
                @enderror
            </div>
        </div>

        <div class="form-group row">
            <label for="staticEmail" class="col-sm-2 col-form-label">Email</label>
            <div class="col-sm-10">
                <!--   -->
                <input wire:model="email" type="text" class="form-control  @error('email') is-invalid @enderror">
                @error('email')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
                @enderror
            </div>
        </div>


        <div class="form-group row">
            <label for="staticEmail" class="col-sm-2 col-form-label  ">Old Password</label>
            <div class="col-sm-10">
                <!--  -->
                <input wire:model="OldPassword" autocomplete="off" type="Password" class="form-control @error('OldPassword') is-invalid @enderror">
                @error('OldPassword')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
                @enderror
            </div>
        </div>



        <div class="form-group row">
            <label for="staticEmail" class="col-sm-2 col-form-label  ">New Password</label>
            <div class="col-sm-10">
                <!--   -->
                <input wire:model="NewPassword" type="Password" class="form-control @error('NewPassword') is-invalid @enderror">
                @error('NewPassword')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
                @enderror
            </div>
        </div>



        <div class="form-group row">
            <label for="staticEmail" class="col-sm-2 col-form-label">Verify New Password</label>
            <div class="col-sm-10">
                <!--  -->
                <input wire:model="NewPasswordVerifikasi" autocomplete="off" type="Password" class="form-control  @error('NewPasswordVerifikasi') is-invalid @enderror">
                @error('NewPasswordVerifikasi')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
                @enderror
            </div>
        </div>



        <button wire:click="Update" type="button" class="btn btn-primary">Submit</button>
        <a href='/'><button wire:click="Update" type="button" href="/" class="btn btn-danger">Cencel</button></a>
    </div>
</div>
