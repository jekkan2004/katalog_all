<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('produks', function (Blueprint $table) {
            // ubah id ke id_produk
            // $table->renameColumn('id', 'id_produk');

            // hapus kolom gambar karena akan dipisah ke tabel lain
            $table->dropColumn('gambar');
        });
    }

    public function down(): void
    {
        Schema::table('produks', function (Blueprint $table) {
            // rollback
            $table->string('gambar')->nullable();
        });
    }
};
