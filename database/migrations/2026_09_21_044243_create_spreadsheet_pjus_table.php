<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('spreadsheet_pjus', function (Blueprint $table) {
            $table->id();
            $table->string('no_urut')->unique();
            $table->string('alamat')->nullable();
            $table->string('status_meter')->nullable(); // METER/TERSEBAR
            $table->string('bentuk')->nullable();
            $table->string('idpel')->nullable();
            $table->string('tiang')->nullable();
            $table->string('jenis_lampu')->nullable();
            $table->integer('besar_daya')->nullable()->default(0);
            $table->decimal('lat', 10, 8)->nullable();
            $table->decimal('lng', 11, 8)->nullable();
            $table->enum('survei', ['', 'SUDAH', 'BELUM'])->nullable()->default('');
            $table->enum('meterisasi', ['', 'SUDAH', 'BELUM'])->nullable()->default('');
            $table->string('tindak_lanjut')->nullable();
            $table->string('kebutuhan_material')->nullable();
            $table->string('status_lampu')->nullable();
            $table->string('unit_pln')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('spreadsheet_pjus');
    }
};
