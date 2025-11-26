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
        Schema::create('plans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('couple_id')->constrained()->cascadeOnDelete();
            $table->string('title');
            $table->date('date');
            $table->foreignId('category_id')->constrained()->restrictOnDelete();
            $table->string('location')->nullable();
            $table->decimal('cost', 10, 2)->nullable();
            $table->text('description')->nullable();
            $table->foreignId('created_by')->constrained('users')->restrictOnDelete();
            $table->string('status')->default('pending'); // pending, completed
            // Cached averages
            $table->decimal('overall_avg', 3, 2)->nullable();
            $table->decimal('fun_avg', 3, 2)->nullable();
            $table->decimal('emotional_connection_avg', 3, 2)->nullable();
            $table->decimal('organization_avg', 3, 2)->nullable();
            $table->decimal('value_for_money_avg', 3, 2)->nullable();
            $table->unsignedInteger('ratings_count')->default(0);
            $table->unsignedInteger('photos_count')->default(0);
            $table->timestamp('last_rated_at')->nullable();
            $table->timestamps();
            $table->softDeletes();

            // Indexes for performance
            $table->index(['couple_id', 'date', 'status']);
            $table->index(['couple_id', 'category_id']);
            $table->index(['created_by', 'date']);
            $table->index('date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('plans');
    }
};


