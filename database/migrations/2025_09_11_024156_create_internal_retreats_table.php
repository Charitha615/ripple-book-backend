<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('internal_retreats', function (Blueprint $table) {
            $table->id();
            $table->string('request_for_retreat');
            $table->string('retreat_no')->unique();
            $table->string('course_type');
            $table->date('start_date');
            $table->date('end_date');
            $table->string('status');
            $table->string('teachers_name');
            $table->string('organiser_contact_no');
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::dropIfExists('internal_retreats');
    }
};
