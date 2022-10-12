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
        Schema::create('legacyScheduleFiles', function(Blueprint $table) {
            $table -> id();
            $table -> foreignId('schedule_id')
                -> references('id')
                -> on('legacySchedule');
            $table -> unsignedInteger('attachment_id');
            $table -> timestamps();
        });

        Schema::table('legacyScheduleFiles', function(Blueprint $table) {
            $table -> foreign('attachment_id')
                -> references('id')
                -> on('attachments');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('legacyScheduleFiles');
    }
};
