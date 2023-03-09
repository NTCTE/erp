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
        Schema::create('students_actions', function (Blueprint $table) {
            $table -> id();
            $table -> foreignId('persons_groups_link_id')
                -> references('id')
                -> on('persons_groups_links');
            $table -> foreignId('group_id')
                -> references('id')
                -> on('groups');
            $table -> char('vanilla_name', 10);
            $table -> unsignedTinyInteger('type');
            $table -> text('additionals')
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
        Schema::dropIfExists('students_actions');
    }
};
