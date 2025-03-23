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
        Schema::create('task_revisions', function (Blueprint $table) {
            $table->uuid('id')->primary();
            // Przechowujemy zmiany w formacie JSON, np. { "name": {"old": "xxx", "new": "yyy"} }
            $table->json('changes');
            $table->timestamp('changed_at')->useCurrent();
            
            $table->foreignUuid('task_id')
                ->references('id')
                ->on('tasks')->onDelete('cascade');
            $table->foreignUuid('user_id')->references('id')
                ->on('users');
        
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('task_revisions');
    }
};