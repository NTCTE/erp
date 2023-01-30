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
        Schema::create('groups_persons', function (Blueprint $table) {
            $table -> foreignId('group_id')
                -> references('id')
                -> on('groups');
            $table -> foreignId('person_id')
                -> references('id')
                -> on('persons');
            $table -> date('enrollment_date')
                -> nullable();
            $table -> date('expillied_date')
                -> nullable();
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
        Schema::dropIfExists('groups_persons');
    }
};
