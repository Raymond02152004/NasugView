<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
public function up()
{
    Schema::create('posts', function (Blueprint $table) {
         $table->bigIncrements('posts_id');
        $table->unsignedBigInteger('signup_id');
        $table->text('content')->nullable();
        $table->longText('media_paths')->nullable(); // ✅ must be long enough for multiple files
        $table->timestamps();

        $table->foreign('signup_id')->references('signup_id')->on('signup')->onDelete('cascade');
    });
}

    public function down(): void
    {
        Schema::dropIfExists('posts');
    }
};
