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
        Schema::create('actions', function (Blueprint $table) {
            $table->id();
            $table->string('action_code')->unique(); // ID
            $table->string('reference'); // Rujukan
            $table->string('theme'); // Tema
            $table->text('required_action'); // Tindakan Diperlukan
            $table->string('owner'); // Pemilik Tindakan
            $table->string('target')->nullable(); // Sasaran / Tempoh
            $table->string('status'); // status
            $table->string('priority'); // Keutamaan
            $table->string('basis'); // Asas Tindakan
            $table->text('progress')->nullable(); // Kemajuan Terkini
            $table->text('evidence')->nullable(); // Bukti / Keputusan
            $table->date('updated_on')->nullable(); // Tarikh Kemas Kini
            $table->text('source_note')->nullable(); // Catatan Sumber
            $table->date('meeting_date')->nullable(); // Tarikh Mesyuarat
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('actions');
    }
};
