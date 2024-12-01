<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id')->unsigned()->index();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->integer('serviceprovider_id')->unsigned()->index();
            $table->foreign('serviceprovider_id')->references('id')->on('service_providers')->onDelete('cascade');
            $table->decimal('total', 8, 2);
            $table->integer('status');
            $table->integer('type');
            $table->string('date_time');
            $table->integer('duration_hours')->unsigned()->default(0);
            $table->integer('duration_minutes')->unsigned()->default(0);
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
        Schema::dropIfExists('orders');
    }
}
