<?php

namespace Database\Seeders;

use App\Models\System\Repository\Address\City;
use App\Models\System\Repository\Address\Country;
use App\Models\System\Repository\Address\Region;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CityTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = array_map('str_getcsv', file('https://raw.githubusercontent.com/sharindog/cities-regions-countries/main/city.csv'));
        array_shift($data); // удаляем заголовок

        foreach ($data as $row) {
            DB::table('cities')->insert([
                'id' => $row[0],
                'fullname' => $row[3],
                'region_id' => $row[2],
            ]);
        }
    }
}
