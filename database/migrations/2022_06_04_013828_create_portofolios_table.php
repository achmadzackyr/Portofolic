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
        Schema::create('portofolios', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('portofolio_type_id');
            $table->unsignedBigInteger('user_id');
            $table->string('portofolio_name');
            $table->longText('portofolio_description')->nullable();
            $table->string('portofolio_url')->nullable();
            $table->date('portofolio_date')->nullable();
            $table->timestamps();
            $table->foreign('portofolio_type_id')->references('id')->on('portofolio_types')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('portofolios');
    }
};
