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
        Schema::table('groups', function(Blueprint $table) {
            $table -> char('number_of_order', 20)
                -> nullable();
        });

        Schema::table('groups_persons', function(Blueprint $table) {
            $table -> char('number_of_order', 20)
                -> nullable();
            $table -> boolean('is_academic_leave')
                -> default(false);
            $table -> unsignedTinyInteger('steps_counter');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('groups', function(Blueprint $table) {
            $table -> dropColumn('number_of_order');
        });

        Schema::table('groups_persons', function(Blueprint $table) {
            $table -> dropColumn('number_of_order');
            $table -> dropColumn('is_academic_leave');
            $table -> dropColumn('steps_counter');
        });
    }
};
