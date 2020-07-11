<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUnitTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('units', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('apartment_id');
            $table->unsignedInteger('owner_id');
            $table->string('unit_number',20);
            $table->string('rent_price');
            $table->string('owner_rent_price');
            $table->unsignedInteger('charge')->nullable();
            $table->timestamps();

            $table->foreign('apartment_id')
                  ->references('id')->on('apartments')
                  ->onDelete('cascade')->onUpdate('cascade'); 
            $table->foreign('owner_id')
                  ->references('id')->on('owners')
                  ->onDelete('cascade')->onUpdate('cascade');             
        });

        Schema::create('calendar_events', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('unit_id');
            $table->dateTime('started_at');
            $table->dateTime('ended_at');
            $table->char('type',1); // 1 : Maintanance, 2 : Norma Blocking
            $table->unsignedInteger('user_id')->nullable();
            $table->string('note',50)->nullable();
            $table->timestamps(); 

            $table->foreign('unit_id')
                  ->references('id')->on('units')
                  ->onDelete('cascade')->onUpdate('cascade');             
            $table->foreign('user_id')
                  ->references('id')->on('users')
                  ->onDelete('set null')->onUpdate('cascade');                    
        });   

        Schema::create('mod_prices', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('unit_id');
            $table->dateTime('started_at');
            $table->dateTime('ended_at');
            $table->string('price');
            $table->string('owner_price');
            $table->string('note',40)->nullable();
            $table->unsignedInteger('user_id')->nullable();
            $table->timestamps();

            $table->foreign('unit_id')
                  ->references('id')->on('units')
                  ->onDelete('cascade')->onUpdate('cascade');            
        });  
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('mod_prices');
        Schema::dropIfExists('calendar_events');
        Schema::dropIfExists('units');
    }
}
