<?php

namespace App\Livewire\Nasabah;

use App\Models\Saldo;

use Mary\Traits\Toast;
use App\Models\Nasabah;
use Livewire\Component;
use Livewire\WithPagination;
use App\Exports\NasabahExport;
use Maatwebsite\Excel\Facades\Excel;


class Index extends Component
{
    use WithPagination;
    use Toast;

    public $confirming;
    public $sortField = 'nama';
    public $search;
    public $perPage = 10;
    public $sortAsc = true;
    public $firstPage = 1;
    public $status = 'aktif'; // Default status filter
    public $saldo_id = 'semua'; // Default saldo filter

    public array $sortBy = ['column' => 'nama', 'direction' => 'asc'];

    public function mount()
    {
        $user = auth()->user();
        $admin = $user->hasRole('admin');
        if (!$admin)
            $this->saldo_id =  $user->saldo_id;
    }


    public function updatingPage($page)
    {
        $this->firstPage = $this->perPage * ($page - 1);
    }

    public function confirmDelete($id)
    {
        $this->confirming = $id;
    }

    public function statusNasabah($id)
    {
        $nasabah = Nasabah::find($id);

        $nasabah = Nasabah::find($id);

        if (!$nasabah) {
            return back()->with('pesan', 'Data nasabah tidak ditemukan.');
        }

        $nasabah->status = $nasabah->status === 'aktif' ? 'nonaktif' : 'aktif';

        $nasabah->save();

        $this->success('Berhasil', 'Status nasabah telah diperbarui.', 'toast-top toast-end', 'o-check-circle', 'alert-success');
    }

    public function export()
    {
        return Excel::download(new NasabahExport, 'Nasabah.xlsx');
    }


    public function render()
    {
        $saldo_id = $this->saldo_id;
        $status = $this->status;


        $nasabah = Nasabah::when($saldo_id !== "semua", function ($query) use ($saldo_id) {
            $query->where('saldo_id', $saldo_id);
        })
            ->when($this->status !== 'semua', function ($query) use ($status) {
                $query->where('status', $status);
            })
            ->where(function ($query) {
                $query->where('nama', 'like', '%' . $this->search . '%')
                    ->orWhere('rekening', 'like', '%' . $this->search . '%')
                    ->orWhere('alamat', 'like', '%' . $this->search . '%');
            })
            ->orderBy(...array_values($this->sortBy))
            ->paginate($this->perPage);

        $headers = [
            ['key' => 'no', 'label' => 'No', 'sortable' => false],
            ['key' => 'photo', 'label' => 'Photo', 'class' => 'w-[100px]', 'sortable' => false],
            ['key' => 'rekening', 'label' => 'Rekening', 'sortable' => true],
            ['key' => 'nama', 'label' => 'Nama', 'sortable' => true],
            ['key' => 'alamat', 'label' => 'Alamat'],
            ['key' => 'saldo', 'label' => 'Saldo'],
            ['key' => 'status', 'label' => 'Status', 'sortable' => true],

        ];

        $saldos = Saldo::select('id', 'nama')->get()->prepend((object)[
            'id' => 'semua',
            'nama' => 'Semua Lembaga'
        ]);



        return view('livewire.nasabah.index', compact("nasabah", "headers", "saldos"));
    }
}
