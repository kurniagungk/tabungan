<?php

namespace App\Livewire\Dasbord;

use App\Models\Nasabah;
use Livewire\Component;

/**
 * Komponen Livewire untuk menampilkan dashboard.
 *
 * Komponen ini menampilkan statistik terkait nasabah, termasuk jumlah total nasabah,
 * total saldo, jumlah saldo nasabah aktif, dan jumlah saldo nasabah tidak aktif.
 * Data yang ditampilkan difilter berdasarkan role user yang login:
 * - Admin: Dapat melihat semua data.
 * - User Biasa: Hanya dapat melihat data yang terkait dengan saldo_id mereka.
 */
class Index extends Component
{
    /**
     * Merender view komponen.
     *
     * Mengambil data statistik nasabah dan meneruskannya ke view `livewire.dasbord.index`.
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function render()
    {
        // Mendapatkan user yang sedang login.
        $user = auth()->user();

        // Memeriksa apakah user memiliki role 'admin'.
        $admin = $user->hasRole('admin');

        // Menghitung jumlah total saldo nasabah (difilter berdasarkan saldo_id jika user bukan admin).
        $saldo = Nasabah::when(!$admin, function ($query) use ($user) {
            return $query->where('saldo_id', $user->saldo_id);
        })->sum('saldo');

        // Menghitung jumlah total saldo nasabah dengan status 'aktif' (difilter berdasarkan saldo_id jika user bukan admin).
        $aktif = Nasabah::when(!$admin, function ($query) use ($user) {
            return $query->where('saldo_id', $user->saldo_id);
        })->where('status', 'aktif')->sum('saldo');

        // Menghitung jumlah total saldo nasabah dengan status 'tidak' (difilter berdasarkan saldo_id jika user bukan admin).
        $tidak = Nasabah::when(!$admin, function ($query) use ($user) {
            return $query->where('saldo_id', $user->saldo_id);
        })->where('status', 'tidak')->sum('saldo');

        // Menghitung jumlah total nasabah (difilter berdasarkan saldo_id jika user bukan admin).
        $nasabah = Nasabah::when(!$admin, function ($query) use ($user) {
            return $query->where('saldo_id', $user->saldo_id);
        })->count();

        // Membuat array asosiatif yang berisi data statistik nasabah.
        $data = [
            'jumlahNasaba' => $nasabah,
            'saldo' => $saldo,
            'aktif' => $aktif,
            'tidak' => $tidak,
        ];

        // Merender view 'livewire.dasbord.index' dan meneruskan data ke view.
        return view('livewire.dasbord.index', ['data' => $data]);
    }
}
