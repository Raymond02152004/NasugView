<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('responses', function (Blueprint $table) {
            $table->id('response_id');
            $table->unsignedBigInteger('form_id');
            $table->unsignedBigInteger('signup_id')->nullable(); // optional
            $table->timestamp('submitted_at')->useCurrent();

            $table->foreign('form_id')->references('form_id')->on('forms')->onDelete('cascade');
            $table->foreign('signup_id')->references('signup_id')->on('signup')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('responses');
    }
};
