<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    // In the migration file
    public function up()
    {
        Schema::table('dana_requests', function (Blueprint $table) {
            $table->boolean('is_breakfast')->default(false)->after('dana_event_date');
            $table->boolean('is_lunch')->default(false)->after('is_breakfast');
        });
    }

    public function down()
    {
        Schema::table('dana_requests', function (Blueprint $table) {
            $table->dropColumn(['is_breakfast', 'is_lunch']);
        });
    }
};
