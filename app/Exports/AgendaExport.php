<?php

namespace App\Exports;

use App\Models\Surat;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class AgendaExport implements FromCollection, WithHeadings
{
    protected $bulan, $tahun;

    public function __construct($bulan, $tahun)
    {
        $this->bulan = $bulan;
        $this->tahun = $tahun;
    }

    public function collection()
    {
        return Surat::whereMonth('tanggal_surat', $this->bulan)
            ->whereYear('tanggal_surat', $this->tahun)
            ->orderBy('tanggal_surat', 'asc')
            ->get(['nomor_surat', 'asal_surat', 'tanggal_surat', 'perihal']);
    }

    public function headings(): array
    {
        return ["No Surat", "Pengirim", "Tanggal Surat", "Perihal"];
    }
}

