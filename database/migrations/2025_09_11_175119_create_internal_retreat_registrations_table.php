<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('internal_retreat_registrations', function (Blueprint $table) {
            $table->id();

            // Basic Information
            $table->string('access_code')->nullable();
            $table->string('first_name');
            $table->string('last_name');
            $table->integer('age');
            $table->string('religion');
            $table->enum('gender', ['male', 'female', 'other']);
            $table->text('address');
            $table->string('street_address');
            $table->string('street_address_line_2')->nullable();
            $table->string('city');
            $table->string('postal_code');
            $table->string('mobile_number');
            $table->string('whatsapp_number')->nullable();
            $table->string('email_address');
            $table->boolean('is_experienced_meditator')->default(false);

            // Emergency Contact
            $table->string('emergency_first_name');
            $table->string('emergency_last_name');
            $table->string('emergency_email');
            $table->string('emergency_relationship');
            $table->string('emergency_mobile_1');
            $table->string('emergency_mobile_2')->nullable();

            // Medical History
            $table->boolean('has_mental_disorder_history')->default(false);
            $table->boolean('has_contagious_disease')->default(false);
            $table->text('other_health_complications')->nullable();

            // Retreat Attendance Information
            $table->string('retreat_no');
            $table->boolean('attend_full_retreat')->default(true);
            $table->string('preferred_roommate_name')->nullable();
            $table->integer('number_of_days');
            $table->date('arrival_date');
            $table->date('departure_date');
            $table->enum('monastic_status', ['none', 'bhikkhu', 'nun', 'samanera', 'anagarika'])->default('none');

            // PDF Upload
            $table->text('pdf_base64')->nullable();
            $table->string('pdf_filename')->nullable();

            // Declaration
            $table->string('declaration_full_name');
            $table->date('declaration_date');

            // System Fields
            $table->string('ip_address')->nullable();
            $table->enum('status', ['Pending', 'Approved', 'Rejected', 'On hold'])->default('Pending');
            $table->text('status_reason')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('internal_retreat_registrations');
    }
};
