<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('answers', function (Blueprint $table) {
            $table->increments('answer_id');
            $table->unsignedInteger('response_id');
            $table->unsignedInteger('question_id');
            $table->unsignedInteger('choice_id')->nullable();
            $table->text('answer_text')->nullable();

            // Foreign keys
            $table->foreign('response_id')->references('response_id')->on('responses')->onDelete('cascade');
            $table->foreign('question_id')->references('question_id')->on('questions')->onDelete('cascade');
            $table->foreign('choice_id')->references('choice_id')->on('choices')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('answers');
    }
};

