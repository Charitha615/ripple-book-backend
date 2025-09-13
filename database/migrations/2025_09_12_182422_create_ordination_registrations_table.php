<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('ordination_registrations', function (Blueprint $table) {
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
            $table->enum('marital_status', ['single', 'married']);
            $table->boolean('has_permission')->default(false);

            // Background Check
            $table->boolean('military_service')->default(false);
            $table->boolean('criminal_record')->default(false);

            // Ordination Details
            $table->enum('ordination_type', ['short_term', 'permanent']);
            $table->string('ordination_time'); // Month or Year
            $table->string('ordination_month')->nullable();
            $table->integer('ordination_year')->nullable();

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
        Schema::dropIfExists('ordination_registrations');
    }
};
