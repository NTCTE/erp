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
        Schema::create('passports', function (Blueprint $table) {
            $table -> id();
            $table -> char('series', 10)
                -> nullable();
            $table -> char('number', 20);
            $table -> foreignId('passport_issuer_id')
                -> references('id')
                -> on('passport_issuers');
            $table -> date('date_of_issue');
            $table -> text('birthplace')
                -> nullable();
            $table -> boolean('is_main')
                -> default(true);
            $table -> foreignId('person_id')
                -> references('id')
                -> on('persons');
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
        Schema::dropIfExists('passports');
    }
};
