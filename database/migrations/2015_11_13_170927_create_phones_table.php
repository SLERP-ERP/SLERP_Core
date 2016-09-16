<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePhonesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('phones', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('user_id');
            $table->string('country_code', 4)->nullable();
            $table->string('number', 10)->unique();
            $table->string('ext', 5)->nullable();
            $table->boolean('is_cell');
            $table->boolean('verified')->default(false);
            $table->string('verification_token', 6)->nullable();
            $table->unsignedInteger('mobile_carrier_id')->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('mobile_carrier_id')->references('id')->on('mobile_carriers')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('phones', function (Blueprint $table) {
            $table->dropForeign('phones_user_id_foreign');
            $table->dropForeign('phones_mobile_carrier_id_foreign');
        });

        Schema::drop('phones');
    }
}
