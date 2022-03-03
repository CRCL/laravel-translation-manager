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
            $table->text('seen_in_files')->nullable();
            $table->text('seen_in_urls')->nullable();
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
                'seen_in_files',
                'seen_in_urls'
            ]);
        });
	}

}
