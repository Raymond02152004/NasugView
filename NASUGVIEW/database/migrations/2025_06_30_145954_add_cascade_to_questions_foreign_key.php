<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('questions', function (Blueprint $table) {
            $table->dropForeign(['form_id']); // drop old constraint
            $table->foreign('form_id')
                ->references('form_id')->on('forms')
                ->onDelete('cascade'); // add cascading delete
        });
    }

    public function down(): void
    {
        Schema::table('questions', function (Blueprint $table) {
            $table->dropForeign(['form_id']);
            $table->foreign('form_id')
                ->references('form_id')->on('forms'); // re-add without cascade
        });
    }
};
