<div class="card shadow mb-4">
    <div class="card-header py-2">
        <h6 class="m-0 font-weight text-primary">
            <b>
                Account
            </b>
        </h6>
    </div>
    <div class="card-body">

        <!-- @if (session()->has('status')) -->
        <div class="alert alert-success">
            <!-- {{ session('status') }} -->
        </div>
        <!-- @endif -->


        <div class="form-group row">
            <label for="staticEmail" class="col-sm-2 col-form-label">Name</label>
            <div class="col-sm-10">
                <!--   -->
                <input wire:model="name" type="text" class="form-control form-control-user">
            </div>
        </div>
        <!-- @error('name') -->
        <div class="alert alert-danger" role="alert">
            <!-- {{ $message }} -->
        </div>
        <!-- @enderror -->
        <div class="form-group row">
            <label for="staticEmail" class="col-sm-2 col-form-label">Email</label>
            <div class="col-sm-10">
                <!--   -->
                <input wire:model="email" type="text" class="form-control">
            </div>
        </div>

        <!-- @error('email') -->
        <div class="alert alert-danger" role="alert">
            <!-- {{ $message }} -->
        </div>
        <!-- @enderror -->

        <div class="form-group row">
            <label for="staticEmail" class="col-sm-2 col-form-label">Old Password</label>
            <div class="col-sm-10">
                <!--  -->
                <input wire:model="OldPassword" autocomplete="off" type="Password" class="form-control">
            </div>
        </div>

        <!-- @error('OldPassword') -->
        <div class="alert alert-danger" role="alert">
            <!-- {{ $message }} -->
        </div>
        <!-- @enderror -->

        <div class="form-group row">
            <label for="staticEmail" class="col-sm-2 col-form-label">New Password</label>
            <div class="col-sm-10">
                <!--   -->
                <input wire:model="NewPassword" type="Password" class="form-control">
            </div>
        </div>

        <!-- @error('NewPassword') -->
        <div class="alert alert-danger" role="alert">
            <!-- {{ $message }} -->
        </div>
        <!-- @enderror -->

        <div class="form-group row">
            <label for="staticEmail" class="col-sm-2 col-form-label">Verify New Password</label>
            <div class="col-sm-10">
                <!--  -->
                <input wire:model="OldPassword" autocomplete="off" type="Password" class="form-control">
            </div>
        </div>

        <!-- @error('OldPassword') -->
        <div class="alert alert-danger" role="alert">
            <!-- {{ $message }} -->
        </div>
        <!-- @enderror -->

        <button wire:click="Update" type="button" class="btn btn-primary">Submit</button>
        <a href='/'><button wire:click="Update" type="button" href="/" class="btn btn-danger">Cencel</button></a>
    </div>
</div>