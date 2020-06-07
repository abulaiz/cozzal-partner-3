<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMasterDataTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('apartments', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name',60);
            $table->string('address',100)->nullable();
            $table->timestamps();
        });  

        Schema::create('banks', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name',40);
            $table->string('bank_code',50);
            $table->string('icon',20)->nullable();
            $table->timestamps();
        });  

        Schema::create('owners', function (Blueprint $table) {
            $table->unsignedInteger('id')->primary(); // Related to user_id
            // Name and email referenced on user table
            $table->string('name');
            $table->string('email')->unique();            
            $table->string('phone');
            $table->string('gender');
            $table->string('account_number');
            $table->string('account_name');
            $table->unsignedInteger('bank_id');
            $table->timestamps();

            // Gagal, bikin di model aja
            $table->foreign('id')
                  ->references('id')->on('users')
                  ->onDelete('cascade')->onUpdate('cascade');           

        });   

        Schema::create('tenants', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name',70);
            $table->string('email',35)->nullable();
            $table->string('phone',25);
            $table->string('gender', 50);
            $table->string('address',100);
            $table->string('last_stays');
            $table->timestamps();
        });  

        Schema::create('booking_vias', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name',100);
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
        Schema::dropIfExists('booking_vias');
        Schema::dropIfExists('tenants');
        Schema::dropIfExists('owners');
        Schema::dropIfExists('apartments');
        Schema::dropIfExists('banks');
    }
}
