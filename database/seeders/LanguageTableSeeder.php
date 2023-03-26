<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LanguageTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = array(
            array('fullname' => 'Русский язык', ),
            array('fullname' => 'Английский язык'),
            array('fullname' => 'Немецкий язык'),
            array('fullname' => 'Французский язык'),
            array('fullname' => 'Китайский язык'),
            array('fullname' => 'Испанский язык'),
            array('fullname' => 'Арабский язык'),
            array('fullname' => 'Португальский язык'),
            array('fullname' => 'Японский язык'),
            array('fullname' => 'Итальянский язык'),
        );

        DB::table('languages')->insert($data);
    }
}
