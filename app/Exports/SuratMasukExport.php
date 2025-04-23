<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use App\Models\Surat;

class SuratMasukExport implements FromCollection, WithHeadings
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
                'Klasifikasi Surat'       => $item->klasifikasi->nama_klasifikasi ?? '-',
                'Bidang'                  => $item->klasifikasi->timKerja->bidang->nama_bidang ?? '-',
                'Tim Kerja'               => $item->klasifikasi->timKerja->nama_tim_kerja ?? '-',
                'Nomor Surat'             => $item->nomor_surat,
                'Nomor Agenda Umum'       => $item->nomor_agenda_umum,
                'Tanggal Surat'           => $item->tanggal_surat,
                'Perihal'                 => $item->perihal,
                'Isi Surat'               => $item->isi_surat,
                'Asal Surat'              => $item->asal_surat,
                'Status Surat'            => $item->status_surat,
                'Tujuan Disposisi'        => $item->user->name ?? '-', // jika relasi ada
                'Isi Disposisi'           => $item->isi_disposisi,
                'Tanggal Penerimaan'      => $item->tanggal_penerimaan_surat,
                'Tanggal Diterima Staf'   => $item->tanggal_diterima_staf,
                'Tanggal Ditindaklanjuti' => $item->tanggal_ditindaklanjuti_staf,
                'Tanggal Disposisi'       => $item->tanggal_disposisi,
                
               
                
                
                
            ];
        });
    }

    public function headings(): array
    {
        return [
            'Klasifikasi Surat',
            'Tim Kerja',
            'Bidang',
            'Nomor Surat',
            'Nomor Agenda Umum',
            'Tanggal Surat',
            'Perihal',
            'Isi Surat',
            'Asal Surat',
            'Status Surat',
            'Tujuan Disposisi',
            'Isi Disposisi',
            'Tanggal Penerimaan',
            'Tanggal Diterima Staf',
            'Tanggal Ditindaklanjuti',
            'Tanggal Disposisi',
            
            
            
            

        ];
    }
}

