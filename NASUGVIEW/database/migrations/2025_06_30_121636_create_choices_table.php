<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('choices', function (Blueprint $table) {
            $table->id('choice_id');
            $table->unsignedBigInteger('question_id');
            $table->string('choice_text');
            $table->integer('position')->default(0);

            $table->foreign('question_id')->references('question_id')->on('questions')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('choices');
    }
};
