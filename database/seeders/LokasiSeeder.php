<?php

namespace Database\Seeders;

use App\Models\Lokasi;
use Illuminate\Database\Seeder;

class LokasiSeeder extends Seeder
{
    public function run(): void
    {
        $lokasis = [
            ['nama_lokasi' => 'Lantai 1', 'nama_divisi' => 'IT'],
            ['nama_lokasi' => 'Lantai 2', 'nama_divisi' => 'HR'],
            ['nama_lokasi' => 'Lantai 3', 'nama_divisi' => 'Finance'],
            ['nama_lokasi' => 'Lantai 4', 'nama_divisi' => 'Operations'],
            ['nama_lokasi' => 'Server Room', 'nama_divisi' => 'IT'],
        ];

        foreach ($lokasis as $lokasi) {
            Lokasi::create($lokasi);
        }
    }
}