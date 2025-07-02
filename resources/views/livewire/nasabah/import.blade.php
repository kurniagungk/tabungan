<div class="space-y-6">

    <x-card title="Data Nasabah">
        @if (session()->has('pesan'))
            <div class="alert alert-success shadow-sm rounded-md">{{ session('pesan') }}</div>
        @endif

        <div class="form-control w-full max-w-xl">
            <label class="label">
                <span class="label-text">File</span>
            </label>
            <input type="file" wire:model.live="file"
                class="file-input file-input-bordered w-full @error('file') input-error @enderror" />
            <span class="text-xs mt-1">* Sesuai ijazah</span>
            @error('file')
                <span class="text-error text-sm">{{ $message }}</span>
            @enderror
        </div>

        <div class="mt-4">
            <form wire:submit.prevent="store">
                <button class="btn btn-success btn-sm" type="submit">
                    Cek
                    <span wire:loading wire:target="store" class="loading loading-spinner loading-sm ml-2"></span>
                </button>
            </form>
        </div>
    </x-card>

    @if ($data)
        <x-card title="Preview Data Nasabah">
            @if (!$errors->any())
                <div class="flex justify-end mb-4">
                    <button class="btn btn-success btn-sm" wire:click="save">
                        Save
                        <span wire:loading wire:target="save" class="loading loading-spinner loading-sm ml-2"></span>
                    </button>
                </div>
            @endif

            <div class="overflow-x-auto">
                <table class="table table-zebra table-bordered w-full">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Rekening</th>
                            <th>Nama</th>
                            <th>Alamat</th>
                            <th>Saldo</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($data as $d)
                            <tr class="@error('data.' . $loop->index . '.no') bg-red-100 @enderror">
                                <td>{{ $loop->index + 1 }}</td>
                                <td>{{ $d['no'] }}</td>
                                <td>{{ $d['nama'] }}</td>
                                <td>{{ $d['alamat'] }}</td>
                                <td>{{ $d['saldo'] }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </x-card>
    @endif

</div>
