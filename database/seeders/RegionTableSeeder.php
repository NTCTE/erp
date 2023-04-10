<?php

namespace Database\Seeders;

use App\Models\System\Repository\Address\Country;
use App\Models\System\Repository\Address\Region;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

class RegionTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = array_map('str_getcsv', file('https://raw.githubusercontent.com/sharindog/cities-regions-countries/main/region.csv'));
        array_shift($data); // удаляем заголовок

        foreach ($data as $row) {
            DB::table('regions')->insert([
                'id' => $row[0],
                'fullname' => $row[3],
                'country_id' => $row[1],
            ]);
        }
    }
}

