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
        Schema::create('attendances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->date('date');
            $table->time('check_in')->nullable();
            $table->time('check_out')->nullable();
            $table->integer('work_duration')->nullable()->comment('Dakika cinsinden çalışma süresi');
            $table->enum('status', ['present', 'half_day', 'absent', 'leave', 'holiday'])->default('present');
            $table->text('notes')->nullable();
            $table->foreignId('card_read_log_id')->nullable()->constrained('card_read_logs')->onDelete('set null');
            $table->timestamps();

            // Bir kullanıcı bir günde sadece bir kayıt olabilir
            $table->unique(['user_id', 'date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attendances');
    }
};
