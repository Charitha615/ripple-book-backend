<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('solo_retreat_registrations', function (Blueprint $table) {
            $table->id();

            // Personal Information
            $table->string('full_name');
            $table->string('last_name');
            $table->date('date_of_birth');
            $table->integer('age');
            $table->enum('gender', ['Male', 'Female', 'Other'])->nullable();
            $table->text('address')->nullable();
            $table->string('city')->nullable();
            $table->string('postal_code')->nullable();
            $table->string('mobile_number')->nullable();
            $table->string('whatsapp_number')->nullable();
            $table->string('email_address');

            // Experience
            $table->boolean('is_beginner')->default(false);
            $table->boolean('is_experienced')->default(false);
            $table->boolean('from_meditation_teacher')->default(false);

            // Retreat Details
            $table->integer('number_of_days')->nullable();
            $table->date('arrival_date')->nullable();
            $table->date('departure_date')->nullable();

            // Emergency Contact
            $table->string('emergency_first_name')->nullable();
            $table->string('emergency_last_name')->nullable();
            $table->string('emergency_relationship')->nullable();
            $table->string('emergency_mobile_number_1')->nullable();
            $table->string('emergency_mobile_number_2')->nullable();

            // Health Information
            $table->boolean('has_schizophrenia_or_manic_depression')->default(false);
            $table->boolean('has_chronic_illness')->default(false);
            $table->text('health_complications')->nullable();
            $table->text('specific_questions')->nullable();

            // Documents
            $table->text('pdf_upload')->nullable(); // base64 encoded
            $table->string('sign_full_name')->nullable();
            $table->date('sign_date')->nullable();

            // System fields
            $table->string('ip_address', 45)->nullable();
            $table->enum('status', ['Pending', 'Approved', 'Rejected', 'On hold'])->default('Pending');
            $table->text('status_reason')->nullable();

            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::dropIfExists('solo_retreat_registrations');
    }
};
