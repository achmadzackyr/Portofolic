<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('portofolio_skills', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('portofolio_id');
            $table->unsignedBigInteger('skill_id');
            $table->timestamps();
            $table->foreign('portofolio_id')->references('id')->on('portofolios')->onDelete('cascade');
            $table->foreign('skill_id')->references('id')->on('skills')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('portofolio_skills');
    }
};
