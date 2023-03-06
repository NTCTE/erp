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
        Schema::create('social_statuses_links', function (Blueprint $table) {
            $table -> id();
            $table -> foreignId('social_status_id')
                -> references('id')
                -> on('social_statuses');
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
        Schema::dropIfExists('social_statuses_links');
    }
};
