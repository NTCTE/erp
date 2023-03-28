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
        Schema::create('executed_commands', function (Blueprint $table) {
            $table -> id();
            $table -> foreignId('machine_id')
                -> references('id')
                -> on('machines')
                -> cascadeOnDelete();
            $table -> foreignId('command_id')
                -> nullable()
                -> references('id')
                -> on('commands')
                -> nullOnDelete();
            $table -> unsignedInteger('exit_code');
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
        Schema::dropIfExists('executed_commands');
    }
};
