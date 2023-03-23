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
        Schema::create('academic_leaves', function (Blueprint $table) {
            $table -> id();
            $table -> foreignId('administrative_document_id')
                -> references('id')
                -> on('administrative_documents');
            $table -> text('reason');
            $table -> date('expired_at');
            $table -> date('returned_at')
                -> nullable();
            $table -> char('vanilla_group_name', 10);
            $table -> foreignId('persons_groups_link_id')
                -> references('id')
                -> on('persons_groups_links');
            $table -> boolean('is_active')
                -> default(true);
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
        Schema::dropIfExists('academic_leaves');
    }
};
