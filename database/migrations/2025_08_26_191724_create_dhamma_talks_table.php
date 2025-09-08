<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('dhamma_talks', function (Blueprint $table) {
            $table->id();

            // Talk Information
            $table->string('sutta_title');
            $table->string('event_title');
            $table->string('retreat_no')->nullable();
            $table->string('event_location')->nullable();
            $table->date('event_start_date')->nullable();
            $table->date('event_end_date')->nullable();

            // Links (stored as JSON)
            $table->json('links')->nullable();

            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::dropIfExists('dhamma_talks');
    }
};
