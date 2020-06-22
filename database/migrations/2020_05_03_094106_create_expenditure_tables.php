<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateExpenditureTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('expenditures', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('cash_mutation_id')->nullable();
            $table->unsignedInteger('unit_id')->nullable();
            $table->Integer('price');
            $table->unsignedInteger('qty');
            $table->string('description',100)->nullable();
            $table->boolean('is_billing');
            $table->boolean('is_paid');
            $table->boolean('has_paid')->default(false);
            $table->boolean('is_approved');
            $table->dateTime('due_at')->nullable();
            $table->timestamps();

            $table->foreign('cash_mutation_id')
                  ->references('id')->on('cash_mutations')
                  ->onDelete('cascade')->onUpdate('cascade');
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
        Schema::dropIfExists('expenditures');
    }
}
