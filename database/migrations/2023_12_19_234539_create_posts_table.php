<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('posts', function (Blueprint $table) {
            $table->id();
            $table->string("caption")->nullable();
            $table->timestamps();

            $table->unsignedBigInteger("user_id");
            $table->foreign("user_id")
                ->references("id")
                ->on("users")
                ->onDelete("cascade");

            $table->unsignedBigInteger("media_id");
            $table->foreign("media_id")
                ->references("id")
                ->on("media")
                ->onDelete("cascade");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('posts');
    }
};
