<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     * BUG-06: Make class_id nullable so students can be unassigned from a class.
     */
    public function up(): void
    {
        Schema::table('students', function (Blueprint $table) {
            // Drop the existing FK constraint first to avoid duplicate key error
            $table->dropForeign(['class_id']);

            // Alter column to be nullable
            $table->foreignId('class_id')->nullable()->change();

            // Re-add the FK constraint with set null on delete
            $table->foreign('class_id')
                ->references('id')
                ->on('classes')
                ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('students', function (Blueprint $table) {
            $table->dropForeign(['class_id']);
            $table->foreignId('class_id')->nullable(false)->change();
            $table->foreign('class_id')
                ->references('id')
                ->on('classes')
                ->onDelete('cascade');
        });
    }
};
