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
        Schema::create('ratings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('plan_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->unsignedTinyInteger('fun'); // 1-5
            $table->unsignedTinyInteger('emotional_connection'); // 1-5
            $table->unsignedTinyInteger('organization'); // 1-5
            $table->unsignedTinyInteger('value_for_money'); // 1-5
            $table->unsignedTinyInteger('overall'); // 1-5
            $table->text('comment')->nullable();
            $table->timestamps();

            // Unique constraint: one rating per user per plan
            $table->unique(['plan_id', 'user_id']);
            
            // Indexes for statistics
            $table->index(['user_id', 'created_at']);
            $table->index('plan_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ratings');
    }
};





