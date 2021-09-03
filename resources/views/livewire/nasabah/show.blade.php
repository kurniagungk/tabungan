<div class="row">
    <div class="col-lg-5 col-sm-12">
        <div class="card shadow mb-4">
            <!-- Card Header - Dropdown -->
            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-primary">Nasabah</h6>
            </div>
            <!-- Card Body -->
            <div class="card-body px-5">

                <div class="d-flex justify-content-center overflow-hidden mb-3 ">
                    <img src="https://inwepo.co/wp-content/uploads/2020/03/Tampilkan-token.jpg" alt=""
                        style="height:200px; width: 200px" class="rounded mx-auto border">
                </div>



                <form wire:submit.prevent="save">
                    <div class="form-group row">
                        <label for="staticEmail" class="col-sm-2 col-form-label">Nis</label>
                        <div class="col-sm-10">
                            <input type="text" readonly class="form-control-plaintext" value="{{$nasabah->rekening}}">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="staticEmail" class="col-sm-2 col-form-label">Nama</label>
                        <div class="col-sm-10">
                            <input type="text" readonly class="form-control-plaintext" value="{{$nasabah->nama}}">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="staticEmail" class="col-sm-2 col-form-label">Saldo</label>
                        <div class="col-sm-10">
                            <input type="text" readonly class="form-control-plaintext" value="{{$nasabah->saldo}}">

                        </div>
                    </div>

                </form>






            </div>



        </div>
    </div>


</div>