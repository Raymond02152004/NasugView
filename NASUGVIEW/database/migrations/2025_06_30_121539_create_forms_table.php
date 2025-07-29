<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('questions', function (Blueprint $table) {
            $table->id('question_id');
            $table->unsignedBigInteger('form_id');
            $table->text('question_text');
            $table->enum('question_type', ['text', 'multiple_choice']);
            $table->integer('position')->default(0);

            $table->foreign('form_id')->references('form_id')->on('forms')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('questions');
    }
};
