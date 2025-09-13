<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('future_plans_requests', function (Blueprint $table) {
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
            $table->string('whatsapp_number')->nullable();
            $table->string('email_address');

            // Project Details
            $table->text('project_type'); // Type of project or materials to support
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
        Schema::dropIfExists('future_plans_requests');
    }
};
