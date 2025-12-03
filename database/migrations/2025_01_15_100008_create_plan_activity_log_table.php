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
    Schema::create('plan_activity_logs', function (Blueprint $table) {
        $table->id();
        $table->foreignId('plan_id')->constrained()->cascadeOnDelete();
        $table->foreignId('user_id')->constrained()->cascadeOnDelete();
        $table->string('action'); // created, updated, status_changed, rated, photo_added
        $table->json('old_values')->nullable();
        $table->json('new_values')->nullable();
        $table->timestamps();

        // Indexes for querying activity
        $table->index(['plan_id', 'created_at']);
        $table->index('user_id');
    });
}

public function down(): void
{
    Schema::dropIfExists('plan_activity_logs');
}
};




