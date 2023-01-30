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
                'class_type' => DocumentSchemaScreen::class,
                'path' => 'system.repository.documentSchemas',
            ],
            [
                'class_type' => PassportIssuerScreen::class,
                'path' => 'system.repository.passportIssuers',
            ],
            [
                'class_type' => PositionScreen::class,
                'path' => 'system.repository.positions',
            ],
            [
                'class_type' => RelationTypeScreen::class,
                'path' => 'system.repository.relationTypes',
            ],
            [
                'class_type' => WorkplaceScreen::class,
                'path' => 'system.repository.workplaces',
            ],
        ]);
    }
}
