<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class SuratKeluarExport implements FromCollection, WithHeadings
{
    protected $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function collection()
    {
        return $this->data->map(function ($item) {
            return [
                'Nomor Surat' => $item->nomor_surat,
                'Klasifikasi' => $item->klasifikasi->nama_klasifikasi ?? '-',
                'Tim Kerja' => $item->klasifikasi->timKerja->nama_tim_kerja ?? '-',
                'Bidang' => $item->klasifikasi->timKerja->bidang->nama_bidang ?? '-',
                'Tanggal Surat' => $item->tanggal_surat,
                'Perihal' => $item->perihal,
                'Tujuan Surat' => $item->tujuan_surat,
                'Isi Surat' => $item->isi_surat,
                'Catatan' => $item->catatan,
            ];
        });
    }

    public function headings(): array
    {
        return [
            'Nomor Surat',
            'Klasifikasi',
            'Tim Kerja',
            'Bidang',
            'Tanggal Surat',
            'Perihal',
            'Tujuan Surat',
            'Isi Surat',
            'Catatan',
        ];
    }
}
