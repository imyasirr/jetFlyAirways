<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('careers', function (Blueprint $table) {
            $table->id();
            $table->string('job_title');
            $table->string('department')->nullable();
            $table->string('location')->nullable();
            $table->string('salary')->nullable();
            $table->unsignedInteger('openings')->default(1);
            $table->longText('job_description')->nullable();
            $table->longText('required_skills')->nullable();
            $table->date('apply_last_date')->nullable();
            $table->boolean('is_hiring')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('careers');
    }
};
