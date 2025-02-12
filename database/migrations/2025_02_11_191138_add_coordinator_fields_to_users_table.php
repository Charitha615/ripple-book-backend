<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->tinyInteger('is_events_coordinator')->default(0);
            $table->tinyInteger('is_community_service_coordinator')->default(0);
            $table->tinyInteger('is_dana_coordinator')->default(0);
            $table->tinyInteger('is_meditate_with_us_coordinator')->default(0);
            $table->tinyInteger('is_dhamma_talks_coordinator')->default(0);
            $table->tinyInteger('is_arama_poojawa_coordinator')->default(0);
            $table->tinyInteger('is_build_up_hermitage_coordinator')->default(0);
            $table->tinyInteger('is_donation_coordinator')->default(0);
            $table->string('gender');
            $table->string('nic');
        });
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'is_events_coordinator',
                'is_community_service_coordinator',
                'is_dana_coordinator',
                'is_meditate_with_us_coordinator',
                'is_dhamma_talks_coordinator',
                'is_arama_poojawa_coordinator',
                'is_build_up_hermitage_coordinator',
                'is_donation_coordinator',
            ]);
        });
    }
};
