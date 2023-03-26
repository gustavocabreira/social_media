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
        Schema::create('profiles_social_medias', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('social_media_id');
            $table->unsignedBigInteger('profile_id');

            $table->foreign('social_media_id')->references('id')->on('social_medias')->onDelete('cascade');
            $table->foreign('profile_id')->references('id')->on('profiles')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('profiles_social_medias');
    }
};
