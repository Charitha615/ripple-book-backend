<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('internal_retreat_requests', function (Blueprint $table) {
            $table->id();
            $table->string('retreat_no')->nullable();
            $table->foreignId('internal_retreat_id')->nullable()->constrained()->onDelete('set null');
            $table->string('first_name');
            $table->string('last_name');
            $table->string('mobile_number');
            $table->string('whatsapp_number')->nullable();
            $table->string('email_address');
            $table->enum('gender', ['male', 'female', 'other']);
            $table->string('interested_retreat_number');
            $table->json('preferred_dates');
            $table->text('queries')->nullable();
            $table->string('ip_address')->nullable();
            $table->enum('status', ['Pending', 'Approved', 'Rejected', 'On hold'])->default('Pending');
            $table->text('status_reason')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('internal_retreat_requests');
    }
};
