<div wire:ignore.self class="modal" tabindex="-1" id="cek">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="tarik" wire:submit.prevent="cekPasword">
                <div class="modal-header">
                    <h5 class="modal-title">Masukan Password</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="exampleInputPassword1">Password</label>
                        <input wire:model="password" type="password" class="form-control @error('password') is-invalid @enderror" id="paswordcek">
                        @error('password')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                        @enderror
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Ok</button>
                </div>
            </form>
        </div>
    </div>
</div>
