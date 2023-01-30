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
        Schema::create('document_schemas', function (Blueprint $table) {
            $table -> id();
            $table -> char('fullname', 200)
                -> unique();
            $table -> jsonb('schema');
            $table -> boolean('readonly')
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
        Schema::dropIfExists('document_schemas');
    }
};
