<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class ProdukSeeder extends Seeder
{
    public function run()
    {
        helper('text'); // Pastikan helper url_title tersedia

        $sayur = ['Bayam', 'Kangkung', 'Sawi Putih', 'Selada', 'Wortel'];
        $buah = ['Jeruk', 'Pisang', 'Mangga', 'Semangka', 'Apel'];
        $rempah = ['Kunyit', 'Jahe', 'Lengkuas', 'Serai', 'Cabe Rawit'];

        $dataProduk = [];

        // Kategori: Sayur (1)
        foreach ($sayur as $nama) {
            $slug = url_title($nama, '-', true);
            $dataProduk[] = [
                'nama_produk'  => $nama,
                'slug'         => $slug,
                'deskripsi'    => 'Segar dan langsung dari kebun lokal.',
                'harga'        => rand(3000, 8000),
                'stok'         => rand(10, 100),
                'satuan'       => 'ikat',
                'kategori_id'  => 1,
                'gambar'       => $slug . '.jpg',
                'link_wa'      => 'https://wa.me/628116556192?text=Saya%20ingin%20beli%20' . $slug,
                'created_at'   => date('Y-m-d H:i:s'),
                'updated_at'   => date('Y-m-d H:i:s'),
            ];
        }

        // Kategori: Buah (2)
        foreach ($buah as $nama) {
            $slug = url_title($nama, '-', true);
            $dataProduk[] = [
                'nama_produk'  => $nama,
                'slug'         => $slug,
                'deskripsi'    => 'Buah segar kaya vitamin dari petani lokal.',
                'harga'        => rand(5000, 15000),
                'stok'         => rand(20, 120),
                'satuan'       => 'kg',
                'kategori_id'  => 2,
                'gambar'       => $slug . '.jpg',
                'link_wa'      => 'https://wa.me/628116556192?text=Saya%20ingin%20beli%20' . $slug,
                'created_at'   => date('Y-m-d H:i:s'),
                'updated_at'   => date('Y-m-d H:i:s'),
            ];
        }

        // Kategori: Rempah-rempah (3)
        foreach ($rempah as $nama) {
            $slug = url_title($nama, '-', true);
            $dataProduk[] = [
                'nama_produk'  => $nama,
                'slug'         => $slug,
                'deskripsi'    => 'Rempah pilihan untuk bumbu dapur Anda.',
                'harga'        => rand(1000, 10000),
                'stok'         => rand(5, 50),
                'satuan'       => 'bungkus',
                'kategori_id'  => 3,
                'gambar'       => $slug . '.jpg',
                'link_wa'      => 'https://wa.me/628116556192?text=Saya%20ingin%20beli%20' . $slug,
                'created_at'   => date('Y-m-d H:i:s'),
                'updated_at'   => date('Y-m-d H:i:s'),
            ];
        }

        // Simpan ke database
        $this->db->table('produk')->insertBatch($dataProduk);
    }
}
