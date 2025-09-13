<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('land_lot_requests', function (Blueprint $table) {
            $table->id();

            // Personal Information
            $table->string('first_name');
            $table->string('last_name');
            $table->date('date_of_birth');
            $table->enum('gender', ['male', 'female', 'other']);

            // Address Information
            $table->text('address');
            $table->string('street_address');
            $table->string('street_address_line_2')->nullable();
            $table->string('city');
            $table->string('postal_code');
            $table->string('country');

            // Contact Information
            $table->string('whatsapp_number')->nullable();
            $table->string('mobile_number');
            $table->string('email_address');

            // Land Lot Details
            $table->json('land_lot_numbers');

            // Additional Information
            $table->text('queries')->nullable();

            // System Fields
            $table->string('ip_address')->nullable();
            $table->enum('status', ['Pending', 'Approved', 'Rejected', 'On hold'])->default('Pending');
            $table->text('status_reason')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('land_lot_requests');
    }
};
