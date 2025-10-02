<?php

namespace App\Exports;

use App\Models\Asset;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class AssetExport implements FromCollection, WithHeadings, WithMapping
{
    public function collection()
    {
        return Asset::with(['kategori', 'lokasi'])->get();
    }

    public function headings(): array
    {
        return [
            'ID',
            'Nama Asset',
            'Kode',
            'Kategori',
            'Lokasi',
            'Kondisi',
            'Tanggal Perolehan',
            'Created At',
            'Updated At'
        ];
    }

    public function map($asset): array
    {
        return [
            $asset->id,
            $asset->nama,
            $asset->kode,
            optional($asset->kategori)->nama_kategori,
            optional($asset->lokasi)->nama_lokasi,
            $asset->kondisi,
            $asset->tgl_perolehan,
            $asset->created_at,
            $asset->updated_at,
        ];
    }
}