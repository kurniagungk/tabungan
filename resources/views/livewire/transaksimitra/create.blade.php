<div class="row justify-content-center align-self-center ">
    <div class="col-lg-6">
        <div class="card o-hidden border-0 shadow-lg my-5 card-block">
            <div class="card-body p-0">
                <!-- Nested Row within Card Body -->


                <div class="p-5">
                    <div class="text-center">
                        <h1 class="h4 text-gray-900 mb-4">Payment - WASERDA</h1>
                    </div>

                    <div>
                        @if (session()->has('message'))
                        <div class="alert alert-success">
                            {{ session('message') }}
                        </div>
                        @endif
                    </div>

                    <div>
                        @if (session()->has('danger'))
                        <div class="alert alert-danger">
                            {{ session('danger') }}
                        </div>
                        @endif
                    </div>

                    <form wire:submit.prevent="bayar">
                        <div class="input-group ">
                            <div class="input-group-prepend">
                                <span class="input-group-text">Rp.</span>
                            </div>
                            <input wire:model.lazy="jumlah" id="jumlah" type="number" autofocus class="form-control form-control-user @error('jumlah') is-invalid @enderror">
                            @error('jumlah')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>
                        <br>
                        <div class="form-group row">
                            <div class="col-sm-12 mb-12 mb-sm-0">
                                <input value="" wire:model.debounce.500ms="nis" type="text" class="form-control form-control-user @error('nis') is-invalid @enderror" id="exampleInputPassword" placeholder="NIS Santri">
                                @error('nis')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                                @enderror
                            </div>

                        </div>

                        @if($nasabah)

                        <div class="form-group row">
                            <label class="col-sm-5 col-form-label">Nama : </label>
                            <label class="col-sm-5 col-form-label">{{$nasabah->nama}}</label>
                        </div>

                        @endif

                        <div class="form-group row">
                            <div class="col-sm-12 mb-12 mb-sm-0">
                                <input wire:model="password" type="password" pattern="[0-9]*" inputmode="numeric" id="pin" class="form-control form-control-user @error('password') is-invalid @enderror" placeholder="PIN">
                                @error('password')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                                @enderror
                            </div>

                        </div>
                        <br>
                        <button type="submit" class="btn btn-primary btn-user btn-block">
                            Verifikasi Bayar
                        </button>
                    </form>
                    <hr>
                    <div class="text-center">
                        <a class="small" href="/">Back to admin?</a>
                    </div>
                    <div class="text-center">
                        <a class="small" href="/riwayat">Riwayat transaksi!</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script type="text/javascript">
    document.addEventListener('DOMContentLoaded', function() {

        window.livewire.on('nasabah', () => {
            document.getElementById("pin").focus();

        });

        window.livewire.on('berhasil', () => {
            document.getElementById("jumlah").focus();
        });
    })
</script>
@endpush