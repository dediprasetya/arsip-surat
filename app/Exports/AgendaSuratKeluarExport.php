<?php
namespace App\Exports;

use App\Models\SuratKeluar;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class AgendaSuratKeluarExport implements FromCollection, WithHeadings, WithMapping
{
    protected $bulan, $tahun;

    public function __construct($bulan, $tahun)
    {
        $this->bulan = $bulan;
        $this->tahun = $tahun;
    }

    public function collection()
    {
        $query = SuratKeluar::with('klasifikasi.timKerja.bidang', 'user');

        if ($this->bulan) {
            $query->whereMonth('tanggal_surat', $this->bulan);
        }

        if ($this->tahun) {
            $query->whereYear('tanggal_surat', $this->tahun);
        }

        return $query->get();
    }

    public function map($surat): array
    {
        return [
            $surat->nomor_surat,
            $surat->tanggal_surat,
            $surat->perihal,
            $surat->tujuan_surat,
            $surat->klasifikasi->nama_klasifikasi ?? '-',
            $surat->klasifikasi->timKerja->nama_tim_kerja ?? '-',
            $surat->klasifikasi->timKerja->bidang->nama_bidang ?? '-',
            $surat->user->name ?? '-',
        ];
    }

    public function headings(): array
    {
        return [
            'Nomor Surat',
            'Tanggal Surat',
            'Perihal',
            'Tujuan Surat',
            'Klasifikasi',
            'Tim Kerja',
            'Bidang',
            'Pembuat',
        ];
    }
}
