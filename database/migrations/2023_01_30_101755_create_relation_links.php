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
        Schema::create('relation_links', function (Blueprint $table) {
            $table -> id();
            $table -> foreignId('person_id')
                -> references('id')
                -> on('persons')
                -> cascadeOnDelete();
            $table -> foreignId('relative_id')
                -> references('id')
                -> on('persons')
                -> cascadeOnDelete();
            $table -> foreignId('relation_type_id')
                -> references('id')
                -> on('relation_types')
                -> cascadeOnDelete();
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
        Schema::dropIfExists('relation_links');
    }
};
