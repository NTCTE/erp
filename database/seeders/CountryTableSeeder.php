<?php

namespace Database\Seeders;

use App\Models\System\Repository\Address\Country;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CountryTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = array_map('str_getcsv', file('https://raw.githubusercontent.com/sharindog/cities-regions-countries/main/country.csv'));
        array_shift($data); // удаляем заголовок

        foreach ($data as $row) {
            DB::table('countries')->insert([
                'id' => $row[0],
                'fullname' => $row[2],
            ]);
        }
    }
}
