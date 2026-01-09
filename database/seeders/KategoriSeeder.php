<?php

namespace Database\Seeders;

use App\Models\Kategori;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class KategoriSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        $faker = Faker::create('id_ID');
        for ($i=0; $i < 5; $i++) { 
            Kategori::create([
                'nama_kategori' => $faker->word(),
                'slug' => $faker->slug(),
                'deskripsi' => $faker->sentence(),
            ]);
        }
    }
}
