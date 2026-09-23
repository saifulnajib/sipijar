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
        Schema::create('spreadsheet_pjus', function (Blueprint $table) {
            $table->id();
            $table->string('no_urut')->unique();
            $table->string('idpel')->nullable();
            $table->string('alamat')->nullable();
            $table->string('jenis_lampu')->nullable();
            $table->integer('besar_daya')->default(0);
            $table->string('status_meter')->nullable();
            $table->decimal('lat', 10, 8)->nullable();
            $table->decimal('lng', 11, 8)->nullable();
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
