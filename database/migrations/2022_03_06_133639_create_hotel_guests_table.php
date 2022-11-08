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
        Schema::create('hotel_guests', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('hotel_id');
            $table->unsignedBigInteger('booking_id');
            $table->unsignedBigInteger('room_id');
            $table->string('first_name');
            $table->string('last_name');
            $table->string('email');
            $table->string('phone');
            $table->string('nid_no')->nullable();
            $table->string('passport_no')->nullable();
            $table->date('dob');
            $table->string('place_of_birth');
            $table->string('nationality');
            $table->dateTime('arrival_date');
            $table->dateTime('leaving_date')->nullable();
            $table->integer('length_of_stay')->nullable();
            $table->string('occupation');
            $table->string('other_info')->nullable();
            $table->string('guest_img_path')->nullable();
            $table->string('nid_img_path')->nullable();
            $table->string('status')->default('active');

            $table->timestamps();

            $table->foreign('hotel_id')->references('id')->on('hotels')->onDelete('cascade');
            $table->foreign('booking_id')->references('id')->on('bookings')->onDelete('cascade');
            $table->foreign('room_id')->references('id')->on('rooms')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('hotel_guests');
    }
};
