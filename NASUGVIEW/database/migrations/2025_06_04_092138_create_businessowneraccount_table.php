<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('businessowneraccount', function (Blueprint $table) {
            $table->bigIncrements('business_id');
            $table->string('business_name');
            $table->string('business_type');
            $table->string('business_address');
            $table->string('email')->unique();
            // No imported_id field, and no timestamps
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('businessowneraccount');
    }
};
