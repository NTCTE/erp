<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('persons', function (Blueprint $table) {
            $table -> id();
            $table -> uuid();
            $table -> foreignId('user_id')
                -> nullable()
                -> references('id')
                -> on('users');
            $table -> char('lastname', 100);
            $table -> char('firstname', 100);
            $table -> char('patronymic', 100)
                -> nullable();
            $table -> char('email', 150)
                -> unique()
                -> nullable();
            $table -> char('corp_email', 150)
                -> unique()
                -> nullable();
            $table -> char('tel', 20)
                -> unique()
                -> nullable();
            $table -> date('birthdate')
                -> nullable();
            $table -> char('snils', 20)
                -> unique()
                -> nullable();
            $table -> char('inn', 20)
                -> unique()
                -> nullable();
            $table -> char('hin', 20) # полис ОМС
                -> unique()
                -> nullable();
            $table -> unsignedTinyInteger('sex')
                -> default(1);
            $table -> foreignId('workplace_id')
                -> nullable()
                -> references('id')
                -> on('workplaces');
            $table -> foreignId('position_id')
                -> nullable()
                -> references('id')
                -> on('positions');
            $table -> timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('persons');
    }
};
