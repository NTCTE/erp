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
        Schema::create('access_to_digitized_book_sets', function (Blueprint $table) {
            $table->id();
            $table->morphs('accessible', 'accessible_index');
            $table->foreignId('book_set_id')
                ->references('id')
                ->on('book_sets');
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
        Schema::dropIfExists('access_to_digitized_book_sets');
    }
};
