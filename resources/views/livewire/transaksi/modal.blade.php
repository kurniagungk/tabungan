<div class="modal" wire:ignore.self id="myModal">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Modal title</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" wire:click="$emit('close')">
                    <span aria-hidden=" true">&times;</span>
                </button>
            </div>
            <form wire:submit.prevent="send">
                <div class="modal-body">
                    <div class="form-group row">
                        <label for="staticEmail" class="col-sm-4 col-form-label">No Rekening</label>
                        <div class="col-sm-8">
                            <input type="text" readonly class="form-control-plaintext" value="{{$rekening}}">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="staticEmail" class="col-sm-4 col-form-label">Nama</label>
                        <div class="col-sm-8">
                            <input type="text" readonly class="form-control-plaintext" value="{{$nama}}">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="staticEmail" class="col-sm-4 col-form-label">Password</label>
                        <div class="col-sm-8">
                            <input type="password" step="any" autofocus
                                class="form-control  @error('password') is-invalid @enderror" id="password"
                                wire:model="password">
                            @error('password')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                            @enderror
                        </div>

                    </div>


                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Save changes</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal"
                        wire:click="$emit('close')">Close</button>
                </div>
            </form>
        </div>
    </div>
</div>