<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('guest_speaker_registrations', function (Blueprint $table) {
            $table->id();

            // Organiser Details
            $table->string('organiser_full_name');
            $table->date('organiser_dob');
            $table->integer('organiser_age');
            $table->string('organiser_mobile_number');
            $table->string('organiser_whatsapp_number')->nullable();
            $table->string('organiser_email');

            // Guest Speaker Details
            $table->string('speaker_first_name');
            $table->string('speaker_last_name');
            $table->date('speaker_dob');
            $table->integer('speaker_age');
            $table->enum('speaker_gender', ['male', 'female', 'other']);

            // Speaker Type
            $table->enum('speaker_type', ['upasampada_monk', 'samanera_monk', 'nun', 'layman']);
            $table->integer('vassa_years')->nullable(); // For Upasampada Monk
            $table->integer('samanera_years')->nullable(); // For Samanera Monk
            $table->integer('nun_years')->nullable(); // For Nun

            // Residence Details
            $table->string('monastery_name');
            $table->string('country_of_residence');
            $table->text('address');
            $table->string('city');
            $table->string('postal_code');
            $table->string('country');
            $table->string('speaker_mobile_number');
            $table->string('speaker_phone_with_country_code');
            $table->string('speaker_email');

            // Experience Details
            $table->enum('experience_level', ['beginner', 'experienced_teacher']);
            $table->integer('retreat_experience_value');
            $table->enum('retreat_experience_unit', ['months', 'years']);

            // Retreat Program Details
            $table->enum('retreat_duration', ['1_day', '4_days', '7_days', '10_days']);
            $table->string('preferred_days');
            $table->string('preferred_month');
            $table->integer('preferred_year');
            $table->integer('expected_participants');

            // Additional Information
            $table->text('queries')->nullable();

            // System Fields
            $table->string('ip_address')->nullable();
            $table->enum('status', ['pending', 'approved', 'rejected', 'processing'])->default('pending');
            $table->text('status_reason')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('guest_speaker_registrations');
    }
};
