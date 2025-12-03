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
        Schema::table('couples', function (Blueprint $table) {
            $table->date('relationship_start_date')->nullable()->after('photo_path');
            $table->date('anniversary_date')->nullable()->after('relationship_start_date');
            $table->string('emoji', 10)->nullable()->after('anniversary_date');
            $table->string('color', 20)->nullable()->after('emoji');
            $table->json('plan_styles')->nullable()->after('color');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('couples', function (Blueprint $table) {
            $table->dropColumn(['relationship_start_date', 'anniversary_date', 'emoji', 'color', 'plan_styles']);
        });
    }
};
