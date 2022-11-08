<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('hotel_services', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('hotel_id');
            $table->tinyInteger('restaurant')->default(0); //0=no, 1=yes
            $table->tinyInteger('bar')->default(0); //0=no, 1=yes
            $table->tinyInteger('gym')->default(0); //0=no, 1=yes
            $table->tinyInteger('swimming_pool')->default(0); //0=no, 1=yes
            $table->tinyInteger('conference_hall')->default(0); //0=no, 1=yes
            $table->tinyInteger('massage_center')->default(0); //0=no, 1=yes
            $table->timestamps();

            $table->foreign('hotel_id')->references('id')->on('hotels')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('hotel_services');
    }
};
