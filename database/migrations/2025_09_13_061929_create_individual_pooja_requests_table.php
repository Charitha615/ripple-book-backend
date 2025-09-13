<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('individual_pooja_requests', function (Blueprint $table) {
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

            // Pooja Purpose (Multiple selections possible)
            $table->boolean('for_birthday')->default(false);
            $table->boolean('for_wedding_anniversary')->default(false);
            $table->boolean('for_punyanumoda')->default(false);
            $table->boolean('for_other')->default(false);
            $table->string('other_purpose')->nullable();

            // Additional Information
            $table->text('queries')->nullable();

            // System Fields
            $table->string('ip_address')->nullable();
            $table->enum('status', ['pending', 'approved', 'rejected', 'on_hold'])->default('pending');
            $table->text('status_reason')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('individual_pooja_requests');
    }
};
