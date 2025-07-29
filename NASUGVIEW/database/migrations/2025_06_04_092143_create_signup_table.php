<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('signup', function (Blueprint $table) {
            $table->bigIncrements('signup_id');
            $table->string('email')->unique();
            $table->string('username');
            $table->string('password');
            $table->string('role');
            $table->string('profile_pic')->nullable(); // 👈 NEW: allows empty value
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('signup');
    }
};
