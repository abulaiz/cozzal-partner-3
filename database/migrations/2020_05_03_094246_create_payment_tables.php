<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePaymentTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('cash_mutation_id')->nullable();
            $table->string('reservations'); // as json encoded array
            $table->string('expenditures'); // as json encoded array
            $table->unsignedInteger('owner_id');
            $table->string('title',70);
            $table->string('description',70);
            $table->Integer('nominal');
            $table->Integer('nominal_paid');
            $table->boolean('is_accepted');
            $table->boolean('is_paid');
            $table->boolean('is_rejected')->default(false);
            $table->timestamps();

            $table->foreign('cash_mutation_id')
                  ->references('id')->on('cash_mutations')
                  ->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('owner_id')
                  ->references('id')->on('owners')
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
        Schema::dropIfExists('payments');
        Schema::dropIfExists('payment_groups');
    }
}
