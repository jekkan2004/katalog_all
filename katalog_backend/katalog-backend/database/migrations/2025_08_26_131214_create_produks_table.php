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
        Schema::create('produks', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('nama_produk');
            $table->string('slug')->unique();
            $table->decimal('harga_produk', 20, 2)->nullable();
            $table->text('deskripsi_produk')->nullable();
            $table->string('gambar')->nullable();
            $table->timestamps();                  
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('produks');
    }
};
