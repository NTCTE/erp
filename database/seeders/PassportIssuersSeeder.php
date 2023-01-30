<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PassportIssuersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $seeder = [];
        /**
         * Экспорт паспортных данных от Dadata.ru.
         * Экспорт данных идет с этой CSV-таблицы: https://raw.githubusercontent.com/hflabs/fms-unit/master/fms_unit.csv
        */

        $curl = curl_init();

        curl_setopt_array($curl, array(
        CURLOPT_URL => 'https://raw.githubusercontent.com/hflabs/fms-unit/master/fms_unit.csv',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'GET',
        ));

        $csv = [];
        $first_line = true;
        $lines = [];

        foreach (explode("\n", curl_exec($curl)) as $value) {
            if ($first_line) {
                $first_line = false;
                $lines = [
                    'code',
                    'fullname',
                ];
            } else {
                if (strlen($value)) {
                    $ins = [];
                    foreach (str_getcsv($value) as $k => $v)
                        $ins[$lines[$k]] = $v;
                    $csv[] = $ins;
                }
            }
        }
        curl_close($curl);

        DB::table('passport_issuers') -> insert($csv);
    }
}
