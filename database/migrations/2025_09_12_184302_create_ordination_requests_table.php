<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('ordination_requests', function (Blueprint $table) {
            $table->id();

            // Personal Information
            $table->string('first_name');
            $table->string('last_name');
            $table->date('date_of_birth');
            $table->integer('age');
            $table->enum('gender', ['male', 'female', 'other']);
            $table->text('address');
            $table->string('city');
            $table->string('postal_code');
            $table->string('mobile_number');
            $table->string('whatsapp_number')->nullable();
            $table->string('email_address');

            // Ordination Details
            $table->enum('ordination_type', ['short_term', 'permanent']);
            $table->string('ordination_month');
            $table->integer('ordination_year');

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
        Schema::dropIfExists('ordination_requests');
    }
};
