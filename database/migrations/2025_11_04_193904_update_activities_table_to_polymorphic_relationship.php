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
        Schema::table('activities', function (Blueprint $table) {
            // Remove old foreign key columns
            $table->dropForeign(['customer_id']);
            $table->dropForeign(['opportunity_id']);
            $table->dropColumn(['customer_id', 'opportunity_id']);
            
            // Add polymorphic relationship columns
            $table->string('activityable_type')->after('id');
            $table->unsignedBigInteger('activityable_id')->after('activityable_type');
            
            // Add index for polymorphic relationship
            $table->index(['activityable_type', 'activityable_id'], 'activities_activityable_index');
            
            // Add start_date and end_date columns if they don't exist
            if (!Schema::hasColumn('activities', 'start_date')) {
                $table->datetime('start_date')->nullable()->after('scheduled_at');
            }
            if (!Schema::hasColumn('activities', 'end_date')) {
                $table->datetime('end_date')->nullable()->after('start_date');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('activities', function (Blueprint $table) {
            // Remove polymorphic relationship columns
            $table->dropIndex('activities_activityable_index');
            $table->dropColumn(['activityable_type', 'activityable_id']);
            
            // Restore old foreign key columns
            $table->foreignId('customer_id')->constrained()->onDelete('cascade');
            $table->foreignId('opportunity_id')->nullable()->constrained()->onDelete('cascade');
        });
    }
};
