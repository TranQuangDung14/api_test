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
        // Schema::create('provinces_districts_wards', function (Blueprint $table) {
        //     $table->id();
        //     $table->timestamps();
        // });
        Schema::create('administrative_units', function (Blueprint $table) {
            $table->id();
            $table->string('full_name',255)->nullable();
            $table->string('full_name_en',255)->nullable();
            $table->string('short_name',255)->nullable();
            $table->string('short_name_en',255)->nullable();
            $table->string('code_name',255)->nullable();
            $table->string('code_name_en',255)->nullable();
            $table->timestamps();
        });


        Schema::create('provinces', function (Blueprint $table) {
            $table->id();
            $table->string('code', 20);
            $table->string('name', 255);
            $table->string('name_en', 255)->nullable();
            $table->string('full_name', 255);
            $table->string('full_name_en', 255)->nullable();
            $table->string('code_name', 255)->nullable();
            $table->integer('administrative_unit_id')->nullable();
            $table->timestamps();
        });

        Schema::create('districts', function (Blueprint $table) {
            $table->id();
            $table->string('code', 20);
            $table->string('name', 255);
            $table->string('name_en', 255)->nullable();
            $table->string('full_name', 255)->nullable();
            $table->string('full_name_en', 255)->nullable();
            $table->string('code_name', 255)->nullable();
            $table->string('province_code', 20)->nullable();
            $table->string('area', 300)->nullable();
            $table->integer('administrative_unit_id')->nullable();
            $table->string('administrative_unit', 300)->nullable();
            $table->timestamps();
        });

        Schema::create('wards', function (Blueprint $table) {
            $table->id();
            $table->string('code', 20);
            $table->string('name', 255);
            $table->string('name_en', 255)->nullable();
            $table->string('full_name', 255)->nullable();
            $table->string('full_name_en', 255)->nullable();
            $table->string('code_name', 255)->nullable();
            $table->string('district_code', 20)->nullable();
            $table->integer('administrative_unit_id')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('provinces_districts_wards');
    }
};
