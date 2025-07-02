<?php

namespace App\Livewire\Nasabah;

use App\Models\Nasabah;

use Livewire\Component;
use Livewire\WithPagination;
use App\Exports\NasabahExport;
use Maatwebsite\Excel\Facades\Excel;


class Index extends Component
{
    use WithPagination;

    public $confirming;
    public $sortField = 'nama';
    public $search;
    public $perPage = 10;
    public $sortAsc = true;
    public $firstPage = 1;

    public array $sortBy = ['column' => 'nama', 'direction' => 'asc'];


    public function updatingPage($page)
    {
        $this->firstPage = $this->perPage * ($page - 1);
    }

    public function confirmDelete($id)
    {
        $this->confirming = $id;
    }

    public function kill($id)
    {
        Nasabah::destroy($id);
        session()->flash('pesan', 'Data mitra successfully deleted.');
    }

    public function export()
    {
        return Excel::download(new NasabahExport, 'Nasabah.xlsx');
    }


    public function render()
    {
        $nasabah = Nasabah::where('nama', 'like', '%' . $this->search . '%')
            ->orWhere('rekening', 'like', '%' . $this->search . '%')
            ->orWhere('alamat', 'like', '%' . $this->search . '%')
            ->orderBy(...array_values($this->sortBy))
            ->paginate($this->perPage);

        $headers = [
            ['key' => 'no', 'label' => 'No', 'sortable' => false],
            ['key' => 'photo', 'label' => 'Photo', 'class' => 'w-[100px]', 'sortable' => false],
            ['key' => 'rekening', 'label' => 'Rekening', 'sortable' => true],
            ['key' => 'nama', 'label' => 'Nama', 'sortable' => true],
            ['key' => 'alamat', 'label' => 'Alamat'],
            ['key' => 'saldo', 'label' => 'Saldo'],
        ];

        return view('livewire.nasabah.index', compact("nasabah", "headers"));
    }
}
