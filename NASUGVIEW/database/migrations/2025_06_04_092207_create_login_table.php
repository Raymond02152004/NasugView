<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('login', function (Blueprint $table) {
            $table->bigIncrements('login_id');
            $table->unsignedBigInteger('signup_id')->unique();

            $table->foreign('signup_id')->references('signup_id')->on('signup')->onDelete('cascade');
            // No timestamps
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('login');
    }
};
