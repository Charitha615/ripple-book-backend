<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('maintenance_requests', function (Blueprint $table) {
            $table->id();

            // Personal Information
            $table->string('first_name');
            $table->string('last_name');

            // Address Information
            $table->text('address');
            $table->string('city');
            $table->string('postal_code');

            // Contact Information
            $table->string('mobile_number');
            $table->string('phone_with_country_code');
            $table->string('email_address');

            // Maintenance Details
            $table->text('maintenance_type'); // Type of maintenance activity
            $table->integer('number_of_volunteers');
            $table->text('preferred_time');

            // System Fields
            $table->string('ip_address')->nullable();
            $table->enum('status', ['pending', 'approved', 'rejected', 'on_hold'])->default('pending');
            $table->text('status_reason')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('maintenance_requests');
    }
};
