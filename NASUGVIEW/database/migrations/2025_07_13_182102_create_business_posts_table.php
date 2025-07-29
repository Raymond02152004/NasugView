<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBusinessPostsTable extends Migration
{
    public function up()
    {
        Schema::create('business_posts', function (Blueprint $table) {
            $table->id('business_id'); // Primary Key
            $table->unsignedBigInteger('signup_id'); // Foreign Key
            $table->string('business_name');
            $table->text('description');
            $table->string('contact_info');
            $table->string('address');
            $table->string('latitude', 50);
            $table->string('longitude', 50);
            $table->string('image_path')->nullable();
            $table->timestamps();

            // Foreign key constraint
            $table->foreign('signup_id')->references('signup_id')->on('signup')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('business_posts');
    }
}
