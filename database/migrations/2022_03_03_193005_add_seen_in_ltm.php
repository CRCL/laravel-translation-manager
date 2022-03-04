<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

class AddSeenInLtm extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('ltm_translations', function(Blueprint $table)
        {
            $table->string('seen_in_file')->nullable();
            $table->string('seen_in_url')->nullable();
            $table->text('default_missing_value')->nullable();
            $table->string('default_missing_locale', 6)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('ltm_translations', function (Blueprint $table) {
            $table->dropColumn([
                'seen_in_file',
                'seen_in_url'
            ]);
        });
    }

}
