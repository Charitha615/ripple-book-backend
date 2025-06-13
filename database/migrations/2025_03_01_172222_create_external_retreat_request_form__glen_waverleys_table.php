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
        Schema::create('external_retreat_request_form__glen_waverleys', function (Blueprint $table) {
            $table->id();
            $table->string('first_name');
            $table->string('last_name');
            $table->string('mobile_number');
            $table->string('wt_number');
            $table->string('email');
            $table->integer('number_of_people');
            $table->string('ip_address',45)->nullable();
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
        Schema::dropIfExists('external_retreat_request_form__glen_waverleys');
    }
};
