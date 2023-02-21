<?php

namespace Database\Seeders;

use App\Orchid\Screens\System\Repository\AddressesScreen;
use App\Orchid\Screens\System\Repository\EducationalDocsIssuersScreen;
use App\Orchid\Screens\System\Repository\EducationalDocsTypesScreen;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UpdateRepositorySeeder extends Seeder
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
                'name' => 'Типы документов об образовании',
                'class_type' => EducationalDocsTypesScreen::class,
                'path' => 'system.repository.edDocsTypes',
                'uri' => 'ed-docs/types',
            ],
            [
                'name' => 'Места выдачи документов об образовании',
                'class_type' => EducationalDocsIssuersScreen::class,
                'path' => 'system.repository.edDocsIssuers',
                'uri' => 'ed-docs/issuers',
            ],
            [
                'name' => 'Адреса',
                'class_type' => AddressesScreen::class,
                'path' => 'system.repository.addresses',
                'uri' => 'addresses',
            ],
        ]);
    }
}
