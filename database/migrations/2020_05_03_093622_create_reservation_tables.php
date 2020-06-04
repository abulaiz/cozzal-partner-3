<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReservationTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('reservations', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('tenant_id');
            $table->unsignedInteger('unit_id');
            $table->unsignedInteger('booking_via_id');
            $table->dateTime('check_in');
            $table->dateTime('check_out');
            $table->unsignedInteger('guest');
            $table->string('notes', 200)->nullable();
            $table->string('rent_prices')->nullable();
            $table->string('owner_rent_prices');
            $table->unsignedInteger('charge');
            $table->unsignedInteger('discount');
            $table->unsignedInteger('amount_bill');
            $table->boolean('is_confirmed');
            $table->boolean('is_paid');
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('tenant_id')
                  ->references('id')->on('tenants')
                  ->onUpdate('cascade');
            $table->foreign('unit_id')
                  ->references('id')->on('units')
                  ->onUpdate('cascade');
            $table->foreign('booking_via_id')
                  ->references('id')->on('booking_vias')
                  ->onUpdate('cascade');           
        });

        Schema::create('reservation_payments', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('reservation_id');
            $table->boolean('is_dp');
            $table->boolean('is_deposite');
            $table->Integer('nominal');
            $table->unsignedInteger('cash_mutation_id');
            $table->Integer('settlement')->nullable();
            $table->timestamps();
            $table->softDeletes();   

            $table->foreign('reservation_id')
                  ->references('id')->on('reservations')
                  ->onUpdate('cascade');
            $table->foreign('cash_mutation_id')
                  ->references('id')->on('cash_mutations')
                  ->onUpdate('cascade');                         
        });        
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('reservation_payments');
        Schema::dropIfExists('reservations');
    }
}
