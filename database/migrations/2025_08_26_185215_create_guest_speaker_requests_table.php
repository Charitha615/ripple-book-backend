<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('guest_speaker_requests', function (Blueprint $table) {
            $table->id();

            // Organiser Information
            $table->string('organiser_full_name');
            $table->enum('organiser_gender', ['Male', 'Female', 'Other'])->nullable();
            $table->string('organiser_mobile_number')->nullable();
            $table->string('organiser_whatsapp_number')->nullable();
            $table->string('organiser_email_address');

            // Guest Speaker Information
            $table->string('guest_full_name');
            $table->string('guest_first_name');
            $table->string('guest_last_name');
            $table->date('guest_date_of_birth')->nullable();
            $table->enum('guest_gender', ['Male', 'Female', 'Other'])->nullable();
            $table->string('guest_email_address')->nullable();

            // Residence Information
            $table->string('aranya_temple_name')->nullable();
            $table->string('country_of_residence')->nullable();

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
        Schema::dropIfExists('guest_speaker_requests');
    }
};
