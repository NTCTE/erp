<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AddEdDocsTypes extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('educational_doc_types') -> insert([
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
