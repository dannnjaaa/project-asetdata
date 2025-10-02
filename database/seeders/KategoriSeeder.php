<?php

namespace Database\Seeders;

use App\Models\Kategori;
use Illuminate\Database\Seeder;

class KategoriSeeder extends Seeder
{
    public function run(): void
    {
        $kategoris = [
            ['nama_kategori' => 'Laptop'],
            ['nama_kategori' => 'Desktop'],
            ['nama_kategori' => 'Printer'],
            ['nama_kategori' => 'Monitor'],
            ['nama_kategori' => 'Network Equipment'],
        ];

        foreach ($kategoris as $kategori) {
            Kategori::create($kategori);
        }
    }
}