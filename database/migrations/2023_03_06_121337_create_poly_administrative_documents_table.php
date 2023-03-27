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
        Schema::create('poly_administrative_documents', function (Blueprint $table) {
            $table -> id();
            $table -> foreignId('administrative_document_id')
                -> nullable()
                -> references('id')
                -> on('administrative_documents')
                -> cascadeOnDelete();
            $table -> morphs('signed');
            $table -> text('description')
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
        Schema::dropIfExists('poly_administrative_documents');
    }
};
