<div class="grid grid-cols-1 md:grid-cols-5 gap-5 -mt-2">
    <x-card title="Whatsapp api" shadow separator class="col-span-1 md:col-span-2">

        @role('admin')
            <x-select label="Lembaga" wire:model.live="saldo_id" :options="$dataSaldo" option-value="id" option-label="nama" />
            <x-select label="Whatsapp Session"
                hint="jika ingin menggunakan 1 WhatsApp untuk beberapa laembaga maka samakan WhatsApp Session"
                :options="$dataSaldo" wire:model="whatsappSession" option-value="nama" option-label="nama">
                <x-slot:append>
                    {{-- Add `join-item` to all appended elements --}}
                    <x-button label="save" wire:click="saveSessionNama" class="join-item btn-primary" />
                </x-slot:append>
            </x-select>
        @endrole

        @if ($server && $saldo_id)
            @role('petugas')
                <x-input label="Whatsapp Session" disabled value="{{ $whatsappSession }}">
                    <x-slot:append>
                        {{-- Add `join-item` to all appended elements --}}
                        <x-button label="Reset" icon="o-arrow-path" wire:click="resetSesionNama"
                            class="join-item btn-primary" />
                    </x-slot:append>
                </x-input>
            @endrole

            <div class="">
                <div>
                    <label class="label mt-5">Status</label>
                    <label class="cursor-pointer label justify-start gap-3">
                        <input type="checkbox" class="toggle toggle-success" wire:model.live="status">
                        <span class="label-text">Aktifkan WhatsApp Session</span>
                    </label>
                </div>



                @if ($status)
                    <div class="mt-6 space-y-3">
                        <h4 class="text-lg font-semibold">To use WhatsApp on your computer:</h4>
                        <ul class="list-disc pl-5 text-sm space-y-1">
                            <li>Open WhatsApp on your phone</li>
                            <li>
                                Tap <strong>Menu</strong> or <strong>Settings</strong> and select <strong>Linked
                                    Devices</strong>
                            </li>
                            <li>Point your phone to this screen to capture the code</li>
                        </ul>


                    </div>



                    @if ($qr)
                        <div class="flex justify-center items-center mt-5">
                            <img src="{{ $qr }}" alt="QR Code"
                                class="rounded-lg shadow-lg w-72 border border-base-300">
                        </div>


                        <div class="flex  items-center">
                            <x-button label=" refresh QR" wire:click="findSesion"
                                class="btn btn-error btn-sm mt-4 mx-auto" spinner />
                        </div>
                    @else
                        <div id="loading" class="flex justify-center h-96 ">
                            <x-loading class="loading-infinity loading-xl" />
                        </div>
                    @endif

                    @if ($sesion)
                        <div class="flex  items-center">
                            <x-button label="Ganti Nomor" wire:click="dc" class="btn btn-error btn-sm mt-4 mx-auto"
                                spinner />

                        </div>
                    @endif
                @endif
            </div>
        @else
            <div class="alert alert-error mt-4 shadow-lg">
                <svg xmlns="http://www.w3.org/2000/svg" class="stroke-current shrink-0 h-6 w-6" fill="none"
                    viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M10.29 3.86L1.82 18a1 1 0 0 0 .9 1.5h18.56a1 1 0 0 0 .9-1.5L13.71 3.86a1 1 0 0 0-1.72 0zM12 9v4m0 4h.01" />
                </svg>
                <span>Server WhatsApp API sedang DOWN</span>
            </div>
        @endif

    </x-card>

    <x-card title="Pesan" shadow separator class="col-span-1 md:col-span-3">

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div class="col-span-1">
                <x-select label="Status Pesan" wire:model.live="statusId" :options="$statusSelect" option-value="value"
                    option-label="label" />

            </div>

            @if ($selected)
                <div class="col-span-1 flex items-end">
                    <x-button label="Ulangi {{ count($selected) }}" class="btn-warning" icon="o-check"
                        wire:click="ulangiAll" spinner />
                </div>
            @endif
        </div>



        <x-table :headers="$headers" :rows="$pesan" striped class="mt-5" with-pagination per-page="perPage"
            wire:model.live="selected" selectable :per-page-values="[10, 15, 20]">

            @scope('cell_status', $pesan)
                @if ($pesan->status == 'pending')
                    <span class="badge badge-warning">Pending</span>
                @endif
                @if ($pesan->status == 'gagal')
                    <span class="badge badge-error">Gagal</span>
                @endif
                @if ($pesan->status == 'berhasil')
                    <span class="badge badge-success">Berhasil</span>
                @endif
            @endscope

            @scope('actions', $pesan)
                @if ($pesan->status == 'gagal')
                    <x-button icon="o-arrow-path" wire:click="ulangi('{{ $pesan->id }}')" spinner
                        class="btn-sm btn-warning" />
                @endif
            @endscope

            @scope('cell_nasabah.nama', $pesan)
                <a class="link link-info" href="{{ route('nasabah.show', $pesan->nasabah->id) }}"
                    wire:navigate>{{ $pesan->nasabah->nama }}</a>
            @endscope


        </x-table>
    </x-card>



</div>

@script
    <script>
        document.addEventListener('livewire:navigated', () => {
            $wire.findSesion();

            $wire.on('qr', () => {
                $wire.findSesion();
            })

            let status = $wire.status

            console.log(status);
            if (status) {

                let count = 0;
                const interval = setInterval(() => {
                    if (count < 2) {
                        $wire.findSesion();
                        count++;
                    } else {
                        clearInterval(interval); // stop pengulangan
                        $wire.$set('qr', null);
                    }
                }, 55000); // setiap 30 detik


            } else {
                setInterval(() => {
                    $wire.findSesion()
                }, 30000)
            }

        }, {
            once: true
        });
    </script>
@endscript
