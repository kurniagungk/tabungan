<div class="mt-10">

    <x-card title="Status WhatsApp API" shadow separator>

        <x-slot:menu>
            <span class="badge badge-success">Online</span>
            <span class="badge badge-error">Offline</span>

            <x-button icon="o-arrow-path" class="btn-circle btn-sm" wire:click="refreshCache" spinner />


        </x-slot:menu>



        <div class="overflow-x-auto">
            <table class="table">
                <thead>
                    <tr>
                        <th>Nama</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($sessions as $s)
                    @php
                    $isOnline = ($s['status'] ?? '') === 'online';
                    $dotClass = $isOnline ? 'bg-success' : 'bg-error';
                    $badgeClass = $isOnline ? 'badge-success' : 'badge-error';
                    $label = $isOnline ? 'Online' : 'Offline';
                    @endphp
                    <tr class="hover">
                        <td>
                            <div class="flex items-center gap-2">
                                <div class="w-2 h-2 rounded-full {{ $dotClass }}"></div>
                                <span>{{ $s['name'] ?? '-' }}</span>
                            </div>
                        </td>

                        <td><span class="badge {{ $badgeClass }}">{{ $label }}</span></td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="text-center opacity-60">Belum ada data session.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-3 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2 text-sm">
            <div class="flex gap-2">
                <span class="badge badge-ghost">Total: {{ $total }}</span>
                <span class="badge badge-success">Online: {{ $online }}</span>
                <span class="badge badge-error">Offline: {{ $offline }}</span>
            </div>
            @if($cachedAt)
            <div class="opacity-60">Terakhir diperiksa: {{ \Carbon\Carbon::parse($cachedAt)->diffForHumans() }}</div>
            @endif
        </div>
    </x-card>




    <!-- Pie Chart -->

</div>

@script

<script>
    document.addEventListener('livewire:navigated', () => {
          $wire.loadData();
        }, {
            once: true
        })
</script>
@endscript