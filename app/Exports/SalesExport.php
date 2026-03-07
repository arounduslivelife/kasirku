<?php

namespace App\Exports;

use App\Models\Penjualan;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class SalesExport implements FromCollection, WithHeadings, WithMapping
{
    protected $startDate;
    protected $endDate;

    public function __construct($startDate, $endDate)
    {
        $this->startDate = $startDate;
        $this->endDate = $endDate;
    }

    public function collection()
    {
        return Penjualan::with(['pelanggan', 'user'])
            ->whereBetween('tanggal', [$this->startDate, $this->endDate])
            ->latest()
            ->get();
    }

    public function headings(): array
    {
        return [
            'No. Invoice',
            'Tanggal',
            'Pelanggan',
            'Kasir',
            'Total Harga',
            'Metode Bayar',
            'Status Bayar',
            'Sisa Piutang',
        ];
    }

    public function map($penjualan): array
    {
        return [
            $penjualan->nomor_invoice,
            $penjualan->tanggal,
            $penjualan->pelanggan->nama ?? 'Umum',
            $penjualan->user->name,
            $penjualan->total_harga,
            $penjualan->metode_bayar,
            $penjualan->status_bayar,
            $penjualan->sisa_piutang,
        ];
    }
}
