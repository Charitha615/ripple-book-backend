<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('internal_retreat_organiser_registrations', function (Blueprint $table) {
            $table->id();
            $table->string('first_name');
            $table->string('last_name');
            $table->date('date_of_birth');
            $table->integer('age');
            $table->enum('gender', ['Male', 'Female', 'Other']);
            $table->text('address');
            $table->string('city');
            $table->string('postal_code');
            $table->string('mobile_number');
            $table->string('whatsapp_number');
            $table->string('email_address');
            $table->string('emergency_first_name');
            $table->string('emergency_last_name');
            $table->string('emergency_relationship');
            $table->string('emergency_mobile_number_1');
            $table->string('emergency_mobile_number_2')->nullable();
            $table->boolean('beginner')->default(false);
            $table->boolean('experienced_volunteer')->default(false);
            $table->boolean('months_experience')->default(false);
            $table->boolean('years_experience')->default(false);
            $table->integer('months')->nullable();
            $table->text('description')->nullable();
            $table->string('ip_address', 45)->nullable();
            $table->enum('status', ['Pending', 'Approved', 'Rejected', 'On hold'])->default('Pending');
            $table->text('status_reason')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('internal_retreat_organiser_registrations');
    }
};
