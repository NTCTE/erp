<?php

namespace Database\Seeders;

use App\Orchid\Screens\System\Repository\DocumentSchemaScreen;
use App\Orchid\Screens\System\Repository\PassportIssuerScreen;
use App\Orchid\Screens\System\Repository\PositionScreen;
use App\Orchid\Screens\System\Repository\RelationTypeScreen;
use App\Orchid\Screens\System\Repository\WorkplaceScreen;
use App\Orchid\Screens\System\Repository\AddressesScreen;
use App\Orchid\Screens\System\Repository\AdministrativeDocumentsScreen;
use App\Orchid\Screens\System\Repository\EducationalDocsIssuersScreen;
use App\Orchid\Screens\System\Repository\EducationalDocsTypesScreen;
use App\Orchid\Screens\System\Repository\SocialStatuses;
use App\Orchid\Screens\System\Repository\SpecialtiesScreen;
use App\Orchid\Screens\System\Repository\LanguagesScreen;
use App\Orchid\Screens\System\Repository\Address\CityScreen;
use App\Orchid\Screens\System\Repository\Address\CountryScreen;
use App\Orchid\Screens\System\Repository\Address\RegionScreen;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Сидер языков
        $this->call([
                LanguageTableSeeder::class
            ]
        );

        DB::table('repository')->insert([
            [
                'name' => 'Схемы документов',
                'class_type' => DocumentSchemaScreen::class,
                'path' => 'system.repository.documentSchemas',
                'uri' => 'persons/document-schemas',
            ],
            [
                'name' => 'Места выдачи паспортов',
                'class_type' => PassportIssuerScreen::class,
                'path' => 'system.repository.passportIssuers',
                'uri' => 'persons/passport-issuers',
            ],
            [
                'name' => 'Должности',
                'class_type' => PositionScreen::class,
                'path' => 'system.repository.positions',
                'uri' => 'persons/positions',
            ],
            [
                'name' => 'Рабочие места',
                'class_type' => WorkplaceScreen::class,
                'path' => 'system.repository.workplaces',
                'uri' => 'persons/relation-types',
            ],
            [
                'name' => 'Родственные связи',
                'class_type' => RelationTypeScreen::class,
                'path' => 'system.repository.relationTypes',
                'uri' => 'persons/workplaces',
            ],
            [
                'name' => 'Типы документов об образовании',
                'class_type' => EducationalDocsTypesScreen::class,
                'path' => 'system.repository.edDocsTypes',
                'uri' => 'students/ed-docs/types',
            ],
            [
                'name' => 'Места выдачи документов об образовании',
                'class_type' => EducationalDocsIssuersScreen::class,
                'path' => 'system.repository.edDocsIssuers',
                'uri' => 'students/ed-docs/issuers',
            ],
            [
                'name' => 'Адреса',
                'class_type' => AddressesScreen::class,
                'path' => 'system.repository.addresses',
                'uri' => 'addresses',
            ],
            [
                'name' => 'Социальные статусы',
                'class_type' => SocialStatuses::class,
                'path' => 'system.repository.socialStatuses',
                'uri' => 'person/socials',
            ],
            [
                'name' => 'Административные документы',
                'class_type' => AdministrativeDocumentsScreen::class,
                'path' => 'system.repository.adminDocs',
                'uri' => 'admin-docs',
            ],
            [
                'name' => 'Специальности',
                'class_type' => SpecialtiesScreen::class,
                'path' => 'system.repository.specialties',
                'uri' => 'students/specialties'

            ],
            [
                'name' => 'Языки',
                'class_type' => LanguagesScreen::class,
                'path' => 'system.repository.languages',
                'uri' => 'languages'

            ],
            [
                'name' => 'Страны',
                'class_type' => CountryScreen::class,
                'path' => 'system.repository.countries',
                'uri' => 'address/countries'

            ],
            [
                'name' => 'Регионы',
                'class_type' => RegionScreen::class,
                'path' => 'system.repository.regions',
                'uri' => 'address/regions'

            ],
            [
                'name' => 'Города',
                'class_type' => CityScreen::class,
                'path' => 'system.repository.cities',
                'uri' => 'address/cities'

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
        DB::table('passport_issuers')->insert($csv);


        DB::table('relation_types')->insert([
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

        DB::table('educational_doc_types')->insert([
            [
                'fullname' => 'Свидетельство о неполном среднем образовании',
            ],
            [
                'fullname' => 'Аттестат об общем среднем образовании',
            ],
            [
                'fullname' => 'Аттестат об основном общем образовании',
            ],
            [
                'fullname' => 'Аттестат о среднем (полном) общем образовании',
            ],
            [
                'fullname' => 'Диплом о присвоении квалификации (разряда, класса, категории) по профессии и получении общего среднего образования',
            ],
            [
                'fullname' => 'Диплом о присвоении квалификации по технической специальности',
            ],
            [
                'fullname' => 'Диплом о начальном профессиональном образовании',
            ],
            [
                'fullname' => 'Диплом о среднем профессиональном образовании',
            ],
            [
                'fullname' => 'Диплом специалиста с высшим профессиональным образованием',
            ],
            [
                'fullname' => 'Диплом специалиста',
            ],
            [
                'fullname' => 'Диплом бакалавра',
            ],
            [
                'fullname' => 'Диплом магистра',
            ],
        ]);
    }
}
