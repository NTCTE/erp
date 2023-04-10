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
        Schema::create('authors_book_sets_links', function (Blueprint $table) {
            $table->id();
            $table->foreignId('author_id')
                ->references('id')
                ->on('authors');
            $table->foreignId('authorship_type_id')
                ->references('id')
                ->on('authorship_types');
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
        Schema::dropIfExists('authors_book_sets_links');
    }
};
