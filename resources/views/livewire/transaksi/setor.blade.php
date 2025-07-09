<div>
    <x-card title="Setor Tunai" shadow class="col-span-5">
        <form wire:submit.prevent="find">
            <x-input label="No Rekening" wire:model="rekening" autofocus autocomplete="off">
                <x-slot:append>
                    <x-button icon="o-qr-code" class="join-item btn-primary" wire:click="$js.startScan" />
                    <x-button icon="o-magnifying-glass" class="join-item btn-primary" type="submit" />
                </x-slot:append>
            </x-input>
        </form>


        <div id="reader" class="mt-4 w-full max-w-md mx-auto rounded-lg overflow-hidden" wire:ignore></div>
    </x-card>
    @if ($transaksi)
        <div class="mt-5 grid grid-cols-1 md:grid-cols-5 gap-2 pb-5" wire:key="{{ $nasabah->id }}">
            <x-card title="Setor Tunai" shadow class="col-span-1 md:col-span-2">
                <form wire:submit="save">
                    <img src="{{ asset('storage/' . $nasabah->foto) }}" class=" h-50 rounded-lg mx-auto" />
                    <div class="mx-5 my-10 space-y-2">
                        <div class="flex">
                            <h3 class="w-40 font-bold">NAMA</h3>
                            <h3>: {{ $nasabah->nama }}</h3>
                        </div>
                        <div class="flex">
                            <h3 class="w-40 font-bold">NISN</h3>
                            <h3>: {{ $nasabah->nisn }}</h3>
                        </div>
                        <div class="flex">
                            <h3 class="w-40 font-bold">REKENING</h3>
                            <h3>: {{ $nasabah->rekening }}</h3>
                        </div>
                        <div class="flex">
                            <h3 class="w-40 font-bold">SALDO</h3>
                            <h3>: {{ Number::currency($nasabah->saldo, 'Rp.') }}</h3>
                        </div>

                        <x-uang model="setor" placeholder="Masukkan nominal" label="Nominal" />

                        <x-textarea label="Keterangan" wire:model="keterangan" placeholder="Here ..."
                            hint="Max 1000 chars" rows="5" />

                        <div class="flex">
                            <h3 class="w-40 font-bold">SALDO AKHIR</h3>
                            <h3>: {{ Number::currency($sisa, 'Rp.') }}</h3>
                        </div>
                        <div class="flex justify-end mt-5">
                            <x-button label="Setor" class="btn-primary" type="submit" />
                        </div>

                    </div>
                </form>
            </x-card>



            <x-card title="Transaksi" shadow class="col-span-1 md:col-span-3 ">
                <livewire:transaksi.histori :nasabah="$nasabah" wire:key="{{ $nasabah->id }}" />
            </x-card>


        </div>
    @endif

    <x-modal wire:model="modal" title="Konfirmasi Password" @close="$wire.close()" class="backdrop-blur">
        @if ($nasabah)
            <div class="flex">
                <h3 class="w-40 font-bold">NAMA</h3>
                <h3>: {{ $nasabah->nama }}</h3>
            </div>
            <div class="flex">
                <h3 class="w-40 font-bold">NISN</h3>
                <h3>: {{ $nasabah->nisn }}</h3>
            </div>
            <div class="flex mb-5">
                <h3 class="w-40 font-bold">REKENING</h3>
                <h3>: {{ $nasabah->rekening }}</h3>
            </div>
            <form wire:submit="cekPassword">
                <x-input label="password" type="password" placeholder="masukan password" autofocus
                    wire:model="password" />

            </form>
        @endif

        <x-slot:actions>
            <x-button label="Cancel" wire:click="close" class="btn-error" />
            <x-button label="Cek" wire:click="cekPassword" class="btn-success" />
        </x-slot:actions>
    </x-modal>

</div>

@assets
    <script script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>
@endassets

@script
    <script>
        $wire.on('modal', () => {
            setTimeout(function() {
                const passwordInput = document.querySelector('input[type="password"]');

                // Fokuskan ke input password
                passwordInput.focus();

            }, 1000); //delay is in milliseconds 
        });
        $wire.on('transaksi', () => {
            setTimeout(function() {
                const setorInput = document.querySelector('input[data-model="setor"]');

                setorInput.scrollIntoView({
                    behavior: 'smooth',
                    block: 'center'
                });


                setorInput.focus();

            }, 1000); // delay dalam milidetik
        });


        $wire.on('start', () => {
            setTimeout(function() {
                const setorInput = document.querySelector(
                    'input[wire\\:model="rekening"]');

                setorInput.scrollIntoView({
                    behavior: 'smooth',
                    block: 'center'
                });


                setorInput.focus();

            }, 1000); // delay dalam milidetik
        });

        let html5QrCode = null;
        let isScanning = false;
        let rekening = null;


        $js('startScan', () => {
            const qrRegionId = "reader";

            if (!html5QrCode) {
                html5QrCode = new Html5Qrcode(qrRegionId);
            }

            if (isScanning) {
                html5QrCode.stop().then(() => {
                    document.getElementById(qrRegionId).innerHTML = "";
                    isScanning = false;
                    console.log("QR scanning stopped.");
                }).catch(err => {
                    console.error("Stop failed", err);
                });
            } else {
                html5QrCode.start({
                        facingMode: "environment"
                    }, {
                        fps: 10,
                        qrbox: 250
                    },
                    (decodedText, decodedResult) => {



                        if (rekening != decodedText)

                            if ($wire.nasabah == null) {
                                rekening = decodedText;
                                $wire.set('rekening', decodedText);
                                $wire.find();
                            }

                        setTimeout(() => {
                            rekening = null;
                        }, 10000);


                    },
                    (errorMessage) => {
                        // console.warn(`QR error: ${errorMessage}`);
                    }
                ).then(() => {
                    isScanning = true;
                    console.log("QR scanning started.");
                }).catch(err => {
                    alert("Camera start error: " + err);
                });
            }
        });
    </script>
@endscript
