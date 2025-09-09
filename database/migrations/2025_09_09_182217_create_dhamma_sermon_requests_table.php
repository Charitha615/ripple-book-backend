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
        Schema::create('dhamma_sermon_requests', function (Blueprint $table) {
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
            $table->boolean('seven_day')->default(false);
            $table->boolean('three_day')->default(false);
            $table->boolean('one_year')->default(false);
            $table->boolean('annually')->default(false);
            $table->boolean('birthday')->default(false);
            $table->boolean('house_warming')->default(false);
            $table->boolean('weddings_anniversary')->default(false);

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
        Schema::dropIfExists('dhamma_sermon_requests');
    }
};
