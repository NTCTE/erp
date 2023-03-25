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
        Schema::create('book_sets', function (Blueprint $table) {
                $table->id();
                $table->text('title');

                $table->unsignedInteger('cover_id')->nullable();
                $table->foreign('cover_id')
                    ->references('id')
                    ->on('attachments');

                $table->unsignedInteger('digitized_id')->nullable();
                $table->foreign('digitized_id')
                    ->references('id')
                    ->on('attachments');

                $table->unsignedInteger('cost');
                $table->foreignId('book_set_type_id')
                    ->references('id')
                    ->on('book_set_types');
                $table->foreignId('pertaining_title_information_id')
                    ->references('id')
                    ->on('pertaining_title_informations');
                $table->year('publishing_year');
                $table->foreignId('publication_information_id')
                    ->references('id')
                    ->on('publication_informations');
                $table->foreignId('publisher_id')
                    ->references('id')
                    ->on('publishers');
                $table->char('isbn', 50)->unique();
                $table->unsignedInteger('pages_number');
                $table->text('annotation');
                $table->foreignId('subject_headline_id')
                    ->nullable()
                    ->references('id')
                    ->on('subject_headlines');
                $table->foreignId('language_id')
                    ->references('id')
                    ->on('languages');
                $table->foreignId('basic_doc_id')
                    ->references('id')
                    ->on('administrative_documents');
                $table->char('barcode', 100)->nullable();
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
        Schema::dropIfExists('book_sets');
    }
};
