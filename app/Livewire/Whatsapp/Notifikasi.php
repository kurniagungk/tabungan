<?php

namespace App\Livewire\Whatsapp;

use App\Models\Nasabah;
use App\Models\Saldo;
use App\Models\Setting;
use App\Services\Whatsapp\NotificationService;
use Illuminate\Database\Eloquent\Builder;
use Livewire\Component;
use Livewire\WithPagination;
use Mary\Traits\Toast;

class Notifikasi extends Component
{
    use WithPagination, Toast;

    public ?int $saldo_id = null;
    public string $message = '';
    public array $selected = [];
    public bool $broadcastAll = false;
    public int $perPage = 10;
    public string $search = '';

    public function mount(): void
    {
        $user = auth()->user();

        if ($user && !$user->hasRole('admin')) {
            $this->saldo_id = $user->saldo_id;
        }
    }

    protected function rules(): array
    {
        $rules = [
            'message' => 'required|string|min:3|max:1000',
        ];

        if (auth()->user()?->hasRole('admin')) {
            $rules['saldo_id'] = 'required|integer|exists:saldo,id';
        }

        if (!$this->broadcastAll) {
            $rules['selected'] = 'required|array|min:1';
            $rules['selected.*'] = 'uuid';
        }

        return $rules;
    }

    public function updatedSaldoId(): void
    {
        $this->resetPage();
        $this->selected = [];
    }

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    public function updatedBroadcastAll(): void
    {
        if ($this->broadcastAll) {
            $this->selected = [];
        }
    }

    public function send(NotificationService $service): void
    {
        $this->validate();

        if (!$this->saldo_id) {
            $this->error('Lembaga wajib dipilih.');
            return;
        }

        $whatsappActive = (int) Setting::where('nama', 'whatsapp_api')
            ->where('saldo_id', $this->saldo_id)
            ->value('isi');

        if ($whatsappActive !== 1) {
            $this->error('WhatsApp API belum aktif untuk lembaga ini.');
            return;
        }

        $query = Nasabah::query()->where('saldo_id', $this->saldo_id);

        if (!$this->broadcastAll) {
            $query->whereIn('id', $this->selected);
        }

        $query->whereNotNull('telepon')->where('wa', true);

        $count = $service->sendToNasabahs($query->cursor(), $this->message, 'notifikasi');

        if ($count === 0) {
            $this->error('Tidak ada nasabah yang valid untuk dikirimi pesan.');
            return;
        }

        $this->reset('message', 'selected');
        $this->broadcastAll = false;

        $this->success("Notifikasi berhasil dikirim ke {$count} nasabah.");
    }

    public function render()
    {
        $saldoId = $this->saldo_id;
        $isAdmin = auth()->user()?->hasRole('admin') ?? false;

        $nasabahQuery = Nasabah::query()
            ->when($saldoId, fn (Builder $q) => $q->where('saldo_id', $saldoId))
            ->when($this->search !== '', function (Builder $q) {
                $search = '%' . $this->search . '%';
                $q->where(function (Builder $sub) use ($search) {
                    $sub->where('nama', 'like', $search)
                        ->orWhere('rekening', 'like', $search)
                        ->orWhere('telepon', 'like', $search);
                });
            })
            ->orderBy('nama');

        if ($isAdmin && !$saldoId) {
            $nasabahQuery->whereRaw('1=0');
        }

        $nasabah = $nasabahQuery->paginate($this->perPage);

        $headers = [
            ['key' => 'nama', 'label' => 'Nama'],
            ['key' => 'rekening', 'label' => 'Rekening'],
            ['key' => 'telepon', 'label' => 'Telepon'],
            ['key' => 'saldo', 'label' => 'Saldo'],
        ];

        $dataSaldo = Saldo::all()->prepend((object) [
            'id' => null,
            'nama' => 'Pilih Lembaga',
        ]);

        return view('livewire.whatsapp.notifikasi', compact('nasabah', 'headers', 'dataSaldo'));
    }
}
