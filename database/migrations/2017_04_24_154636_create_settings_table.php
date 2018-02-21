<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('settings', function (Blueprint $table) {
            $table->increments('id');
            $table->bigInteger('steamid')->unique();
            $table->boolean('confirm_big_bets')->default(1);
            $table->boolean('enable_sounds')->default(1);
            $table->boolean('in_valuta')->default(0);
            $table->string('language')->default('en');
            $table->boolean('hide_profile_link')->default(0);
            $table->string('timezone')->default('Europe/Berlin');
            $table->string('tradelink')->default('');
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
        Schema::dropIfExists('settings');
    }
}
