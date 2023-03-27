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
        Schema::create('groups', function (Blueprint $table) {
            $table -> id();
            $table -> unsignedSmallInteger('training_period');
            $table -> foreignId('specialty_id')
                -> nullable()
                -> references('id')
                -> on('specialties')
                -> nullOnDelete();
            $table -> char('shortname', 10);
            $table -> foreignId('department_id')
                -> references('id')
                -> on('departments')
                -> cascadeOnDelete();
            $table -> foreignId('curator_id')
                -> nullable()
                -> references('id')
                -> on('persons')
                -> nullOnDelete();
            $table -> boolean('archived')
                -> default(false);
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
        Schema::dropIfExists('groups');
    }
};
