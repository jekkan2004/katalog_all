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
        Schema::create('fasilitas_produk', function (Blueprint $table) {
            //$table->id();
            $table->uuid('produk_id');
            $table->foreign('produk_id')->references('id')->on('produks')->onDelete('cascade');

            $table->foreignId('fasilitas_id')->constrained('fasilitas')->onDelete('cascade');

            $table->primary(['produk_id', 'fasilitas_id']);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('fasilitas_produk');
    }
};
