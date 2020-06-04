<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCashTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cashes', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->integer('balance');
            $table->timestamps();
        });

        Schema::create('cash_mutations', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('cash_id');
            $table->integer('fund');
            $table->integer('type_mutation');
            // $table->dateTime('date_mutation')->nullable();
            $table->string('description',5)->nullable();
            // $table->string('note',50)->nullable();
            $table->timestamps(); 

            $table->foreign('cash_id')
                  ->references('id')->on('cashes')
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
        Schema::dropIfExists('cash_mutations');
        Schema::dropIfExists('cashs');
    }
}
