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
        Schema::create('book_sets_keywords_links', function (Blueprint $table) {
            $table->id();
            $table->foreignId('book_sets_keyword_id')
                ->references('id')
                ->on('book_sets_keywords');
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
        Schema::dropIfExists('book_sets_keywords_links');
    }
};
