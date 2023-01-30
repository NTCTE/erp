<?php

namespace Database\Seeders;

use App\Orchid\Screens\System\Repository\DocumentSchemaScreen;
use App\Orchid\Screens\System\Repository\PassportIssuerScreen;
use App\Orchid\Screens\System\Repository\PositionScreen;
use App\Orchid\Screens\System\Repository\RelationTypeScreen;
use App\Orchid\Screens\System\Repository\WorkplaceScreen;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RepositorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('repository') -> insert([
            [
                'name' => 'Схемы документов',
                'class_type' => DocumentSchemaScreen::class,
                'path' => 'system.repository.documentSchemas',
                'uri' => 'document-schemas',
            ],
            [
                'name' => 'Места выдачи паспортов',
                'class_type' => PassportIssuerScreen::class,
                'path' => 'system.repository.passportIssuers',
                'uri' => 'passport-issuers',
            ],
            [
                'name' => 'Должности',
                'class_type' => PositionScreen::class,
                'path' => 'system.repository.positions',
                'uri' => 'positions',
            ],
            [
                'name' => 'Рабочие места',
                'class_type' => WorkplaceScreen::class,
                'path' => 'system.repository.workplaces',
                'uri' => 'relation-types',
            ],
            [
                'name' => 'Родственные связи',
                'class_type' => RelationTypeScreen::class,
                'path' => 'system.repository.relationTypes',
                'uri' => 'workplaces',
            ],
        ]);

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

        DB::table('relation_types') -> insert([
            [
                'fullname' => 'Мать',
            ],
            [
                'fullname' => 'Отец',
            ],
            [
                'fullname' => 'Сын',
            ],
            [
                'fullname' => 'Дочь',
            ],
            [
                'fullname' => 'Муж',
            ],
            [
                'fullname' => 'Жена',
            ],
            [
                'fullname' => 'Бабушка',
            ],
            [
                'fullname' => 'Дедушка',
            ],
            [
                'fullname' => 'Опекун',
            ],
        ]);
    }
}
