<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('membership_requests', function (Blueprint $table) {
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

            // Donation Details
            $table->enum('donation_purpose', ['birthday', 'wedding_anniversary', 'punyanumoda', 'other']);
            $table->string('other_purpose')->nullable();
            $table->enum('donation_type', ['individual', 'group']);
            $table->enum('payment_method', ['online_payment', 'monthly_direct_debit', 'cash_deposit']);

            // Signature and Date
            $table->string('signature');
            $table->date('application_date');

            // System Fields
            $table->string('ip_address')->nullable();
            $table->enum('status', ['pending', 'approved', 'rejected', 'on_hold'])->default('pending');
            $table->text('status_reason')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('membership_requests');
    }
};
