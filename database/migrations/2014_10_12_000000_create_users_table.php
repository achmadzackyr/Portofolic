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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('first_name');
            $table->string('last_name')->nullable();
            $table->string('username')->unique();
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->date('date_of_birth')->nullable();
            $table->char('gender', 2)->nullable();
            $table->string('phone')->nullable();
            $table->boolean('is_active');
            $table->boolean('is_public');
            $table->boolean('is_hire_ready');
            $table->string('profile_picture_url')->nullable();
            $table->string('cover_picture_url')->nullable();
            $table->longText('about_me')->nullable();
            $table->string('headline')->nullable();
            $table->string('navbar_bg_color')->nullable();
            $table->string('navbar_text_color')->nullable();
            $table->string('footer_bg_color')->nullable();
            $table->string('footer_text_color')->nullable();
            $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
};
