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
        Schema::create('courses', function (Blueprint $table) {
            $table->id();
            $table->string('thumbnail_image');
            $table->string('video_link');
            $table->string('cover_image');
            $table->string('pricing_type');
            $table->decimal('previous_price', 8, 2)->nullable();
            $table->decimal('current_price', 8, 2)->nullable();
            $table->string('status');
            $table->string('is_featured');
            $table->decimal('average_rating', 2, 1)->nullable();
            $table->time('duration');
            $table->tinyInteger('certificate_status')->default(1);
            $table->tinyInteger('video_watching')->default(1);
            $table->tinyInteger('quiz_completion')->default(0);
            $table->decimal('min_quiz_score' , 8 , 2)->default(0.0);
            $table->string('certificate_title')->nullable();
            $table->text('certificate_text')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('courses');
    }
};
