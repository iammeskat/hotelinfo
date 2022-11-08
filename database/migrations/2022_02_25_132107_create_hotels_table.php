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
        Schema::create('hotels', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->string('name_en');
            $table->string('name_bn');
            $table->integer('star_level')->nullable();
            $table->unsignedBigInteger('police_station_id');
            $table->text('address');
            $table->string('hotel_phone_number')->nullable();
            $table->string('hotel_email')->nullable();
            $table->string('website')->nullable();
            $table->date('estd')->nullable();
            $table->string('facebook')->nullable();
            $table->string('other_social_id')->nullable();
            $table->string('hotel_license_no')->nullable();
            $table->date('hotel_license_reg_date')->nullable();
            $table->string('trade_license_no')->nullable();
            $table->string('tin_no')->nullable();
            $table->string('vat_no')->nullable();
            $table->string('bin_no')->nullable();
            $table->string('environment_certificate')->nullable();
            $table->string('fireservice_certificate')->nullable();
            $table->tinyInteger('manager')->nullable(); // 0=local, 1=foreigner
            $table->text('description_of_foreign_investment')->nullable();
            $table->integer('no_of_room')->nullable();
            // $table->string('services')->nullable(); 24
            $table->integer('no_of_officer')->nullable();
            $table->integer('no_of_employee')->nullable();
            // $table->string('foreigner_employee_information')->nullable(); 27
            $table->integer('no_of_cc_camera')->nullable();
            $table->tinyInteger('parking')->nullable(); //0=no, 1=yes
            $table->tinyInteger('emergency_exit')->nullable(); //0=no, 1=yes
            $table->tinyInteger('firefighting_system')->nullable(); //0=no, 1=yes
            $table->date('last_date_of_firefighting_ex')->nullable(); //0=no, 1=yes
            $table->tinyInteger('generator')->nullable(); //0=no, 1=yes
            $table->tinyInteger('owners_asso_membership')->nullable(); //0=no, 1=yes
            $table->text('review')->nullable();
            $table->text('other_info')->nullable();
            $table->string('remark')->nullable();
            $table->timestamps();

            $table->foreign('police_station_id')->references('id')->on('police_stations')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('hotels');
    }
};
