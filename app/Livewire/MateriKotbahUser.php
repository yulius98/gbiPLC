<?php

namespace App\Livewire;

use App\Models\TblMateriKotbah;
use Livewire\Component;
use Illuminate\Support\Facades\Storage;

class MateriKotbahUser extends Component
{
    public $selectedDate = '';
    public $selectedKotbah = null;
    public $availableDates = [];

    public function mount()
    {
        // Ambil semua tanggal kotbah yang tersedia
        $availableDatesCollection = TblMateriKotbah::select('tgl_kotbah', 'judul')
            ->whereNotNull('tgl_kotbah')
            ->orderBy('tgl_kotbah', 'desc')
            ->get()
            ->map(function ($item) {
                $judul = $item->judul ?: 'Materi Kotbah';
                return [
                    'date' => $item->tgl_kotbah,
                    'label' => \Carbon\Carbon::parse($item->tgl_kotbah)->format('d M Y') . ' - ' . $judul
                ];
            });

        $this->availableDates = $availableDatesCollection->toArray();

        // Set tanggal terbaru sebagai default
        if ($availableDatesCollection->isNotEmpty()) {
            $this->selectedDate = $availableDatesCollection->first()['date'];
            $this->loadKotbah();
        }
    }

    public function updatedSelectedDate()
    {
        $this->loadKotbah();
    }

    public function loadKotbah()
    {
        if ($this->selectedDate) {
            $this->selectedKotbah = TblMateriKotbah::where('tgl_kotbah', $this->selectedDate)->first();
        }
    }

    public function formatBytes($size, $precision = 2)
    {
        if ($size <= 0) return '0 B';
        
        $base = log($size, 1024);
        $suffixes = array('B', 'KB', 'MB', 'GB', 'TB');
        return round(pow(1024, $base - floor($base)), $precision) . ' ' . $suffixes[floor($base)];
    }

    public function render()
    {
        return view('livewire.materi-kotbah-user');
    }
}