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
        Schema::create('paritta_at__home__request__forms', function (Blueprint $table) {
            $table->id();

            // Personal information
            $table->string('first_name');
            $table->string('last_name');
            $table->string('mobile_number');
            $table->string('whatsapp_number')->nullable();
            $table->string('email_address');

            // Date and time
            $table->date('date');
            $table->time('time');

            // Sanga members
            $table->integer('sanga_members_count')->default(0);

            // Event types (boolean flags)
            $table->boolean('birthday')->default(false);
            $table->boolean('new_business')->default(false);
            $table->boolean('house_warming')->default(false);
            $table->boolean('sick_in_need')->default(false);
            $table->boolean('exams')->default(false);
            $table->boolean('wedding_anniversary')->default(false);
            $table->boolean('weddings')->default(false);
            $table->boolean('pregnant_mums')->default(false);
            $table->boolean('new_born')->default(false);

            // Other event type
            $table->string('other_event')->nullable();

            // System fields
            $table->string('ip_address')->nullable();
            $table->enum('status', ['Pending', 'Approved', 'Rejected', 'On hold'])->default('Pending');
            $table->text('status_reason')->nullable();

            // Timestamps
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('paritta_at__home__request__forms');
    }
};
