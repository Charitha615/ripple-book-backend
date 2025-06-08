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
        Schema::create('five_year_requests', function (Blueprint $table) {
            $table->id();
            $table->string('first_name');
            $table->string('last_name');
            $table->string('date_of_birth')->nullable();
            $table->string('gender');
            $table->string('street_address_line_1');
            $table->string('street_address_line_2')->nullable();
            $table->string('city')->nullable();
            $table->string('postal_code')->nullable();
            $table->string('country')->nullable();
            $table->string('mobile_number');
            $table->string('wt_number');
            $table->string('email');
            $table->boolean('5_land_plots')->default(false);
            $table->boolean('10_land_plots')->default(false);
            $table->boolean('20_land_plots')->default(false);
            $table->boolean('50_land_plots')->default(false);
            $table->string('query')->nullable();
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
        Schema::dropIfExists('five_year_requests');
    }
};
