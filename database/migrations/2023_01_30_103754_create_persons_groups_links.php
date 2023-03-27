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
        Schema::create('persons_groups_links', function (Blueprint $table) {
            $table -> id();
            $table -> foreignId('person_id')
                -> references('id')
                -> on('persons')
                -> cascadeOnDelete();
            $table -> foreignId('group_id')
                -> references('id')
                -> on('groups')
                -> cascadeOnDelete();
            $table -> foreignId('enrollment_order_id')
                -> nullable()
                -> references('id')
                -> on('administrative_documents')
                -> nullOnDelete();
            $table -> boolean('is_academic_leave')
                -> default(false);
            $table -> boolean('budget')
                -> default(true);
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
        Schema::dropIfExists('groups_persons');
    }
};
