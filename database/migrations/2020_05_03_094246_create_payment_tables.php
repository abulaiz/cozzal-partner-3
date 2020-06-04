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
        Schema::create('payment_groups', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('cash_mutation_id')->nullable();
            $table->unsignedInteger('owner_id');
            $table->string('title',70);
            $table->string('description',70);
            $table->Integer('nominal');
            $table->Integer('nominal_paid');
            $table->boolean('is_accepted');
            $table->boolean('is_paid');
            $table->timestamps();

            $table->foreign('cash_mutation_id')
                  ->references('id')->on('cash_mutations')
                  ->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('owner_id')
                  ->references('id')->on('owners')
                  ->onDelete('cascade')->onUpdate('cascade');
        });

        Schema::create('payments', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('payment_group_id');
            $table->unsignedInteger('expenditure_id')->nullable();
            $table->unsignedInteger('reservation_id')->nullable();
            $table->unsignedInteger('nominal');
            $table->boolean('is_income');
            $table->timestamps();   

            $table->foreign('payment_group_id')
                  ->references('id')->on('payment_groups')
                  ->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('expenditure_id')
                  ->references('id')->on('expenditures')
                  ->onDelete('set null')->onUpdate('cascade');
            $table->foreign('reservation_id')
                  ->references('id')->on('reservations')
                  ->onDelete('set null')->onUpdate('cascade');                       
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
