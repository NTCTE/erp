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
        Schema::create('archive_taken_instances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('person_id')
                ->references('id')
                ->on('persons');
            $table->foreignId('instance_id')
                ->references('id')
                ->on('instances');
            $table->date('deadline')->nullable();
            $table->date('return_date');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('archive_taken_instances');
    }
};
