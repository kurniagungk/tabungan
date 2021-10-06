<?php

namespace App\Http\Livewire\Nasabah;

use Livewire\Component;
use Livewire\WithPagination;

use App\Nasabah;

class Index extends Component
{
    use WithPagination;


    protected $paginationTheme = 'bootstrap';

    public $confirming;
    public $sortField = 'nama';
    public $search;
    public $page = 1;
    public $perpage = 10;
    public $sortAsc = true;

    protected $updatesQueryString = [
        'search' => ['except' => ''],
        'page' => ['except' => 1],
    ];

    public function sortBy($field)
    {
        if ($this->sortField === $field) {
            $this->sortAsc = !$this->sortAsc;
        } else {
            $this->sortAsc = true;
        }

        $this->sortField = $field;
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingperpage()
    {
        $this->resetPage();
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


    public function render()
    {
        $nasabah = Nasabah::where('nama', 'like', '%' . $this->search . '%')
            ->orWhere('rekening', 'like', '%' . $this->search . '%')
            ->orWhere('alamat', 'like', '%' . $this->search . '%')
            ->orderBy($this->sortField, $this->sortAsc ? 'asc' : 'desc')
            ->paginate($this->perpage);

        return view('livewire.nasabah.index', compact("nasabah"));
    }
}
