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
        Schema::create('sermon_requests', function (Blueprint $table) {
            $table->id();
            $table->string('first_name');
            $table->string('last_name');
            $table->string('mobile_number');
            $table->string('wt_number');
            $table->string('email');
            $table->string('date');
            $table->string('time');
            $table->string('count')->nullable();
            $table->string('option')->nullable();
            $table->boolean('birthday')->default(false);
            $table->boolean('sevenday')->default(false);
            $table->boolean('warming')->default(false);
            $table->boolean('threemonths')->default(false);
            $table->boolean('oneyear')->default(false);
            $table->boolean('annually')->default(false);
            $table->boolean('weddings')->default(false);
            $table->string('ip_address',45)->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('sermon_requests');
    }
};
