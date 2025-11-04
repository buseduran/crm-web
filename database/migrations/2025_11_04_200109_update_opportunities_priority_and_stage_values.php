<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Önce mevcut constraint'i kaldır
        DB::statement("ALTER TABLE opportunities DROP CONSTRAINT IF EXISTS opportunities_priority_check");
        DB::statement("ALTER TABLE opportunities DROP CONSTRAINT IF EXISTS opportunities_stage_check");
        
        // Yeni constraint'leri ekle (urgent dahil)
        DB::statement("ALTER TABLE opportunities ADD CONSTRAINT opportunities_priority_check CHECK (priority IN ('low', 'medium', 'high', 'urgent'))");
        DB::statement("ALTER TABLE opportunities ADD CONSTRAINT opportunities_stage_check CHECK (stage IN ('prospecting', 'qualification', 'proposal', 'negotiation', 'closed-won', 'closed-lost', 'closed_won', 'closed_lost'))");
        
        // Mevcut verileri güncelle (closed_won -> closed-won, closed_lost -> closed-lost)
        DB::statement("UPDATE opportunities SET stage = 'closed-won' WHERE stage = 'closed_won'");
        DB::statement("UPDATE opportunities SET stage = 'closed-lost' WHERE stage = 'closed_lost'");
        
        // Eski değerleri kaldırmak için constraint'i tekrar güncelle
        DB::statement("ALTER TABLE opportunities DROP CONSTRAINT IF EXISTS opportunities_stage_check");
        DB::statement("ALTER TABLE opportunities ADD CONSTRAINT opportunities_stage_check CHECK (stage IN ('prospecting', 'qualification', 'proposal', 'negotiation', 'closed-won', 'closed-lost'))");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Eski constraint'leri geri yükle
        DB::statement("ALTER TABLE opportunities DROP CONSTRAINT IF EXISTS opportunities_priority_check");
        DB::statement("ALTER TABLE opportunities DROP CONSTRAINT IF EXISTS opportunities_stage_check");
        
        DB::statement("ALTER TABLE opportunities ADD CONSTRAINT opportunities_priority_check CHECK (priority IN ('low', 'medium', 'high'))");
        DB::statement("ALTER TABLE opportunities ADD CONSTRAINT opportunities_stage_check CHECK (stage IN ('prospecting', 'qualification', 'proposal', 'negotiation', 'closed_won', 'closed_lost'))");
        
        // Verileri geri dönüştür
        DB::statement("UPDATE opportunities SET stage = 'closed_won' WHERE stage = 'closed-won'");
        DB::statement("UPDATE opportunities SET stage = 'closed_lost' WHERE stage = 'closed-lost'");
    }
};
