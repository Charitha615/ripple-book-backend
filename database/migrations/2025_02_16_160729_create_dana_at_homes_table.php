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
        Schema::create('dana_at_homes', function (Blueprint $table) {
            $table->id();
            $table->string('first_name');
            $table->string('last_name');
            $table->string('mobile_number');
            $table->string('wt_number');
            $table->string('email');
            $table->string('specific_event')->nullable();
            $table->string('other')->nullable();
            $table->boolean('dana_for_morning')->default(false);
            $table->boolean('dana_for_lunch')->default(false);
            $table->boolean('birthday')->default(false);
            $table->boolean('sevenday')->default(false);
            $table->boolean('warming')->default(false);
            $table->boolean('threemonths')->default(false);
            $table->boolean('oneyear')->default(false);
            $table->boolean('annually')->default(false);
            $table->boolean('weddings')->default(false);
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
        Schema::dropIfExists('dana_at_homes');
    }
};
