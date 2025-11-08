<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Fasilitas extends Model
{
    use HasFactory;

    protected $fillable = ['nama_fasilitas'];

    public function produks()
    {
        return $this->belongsToMany(Produk::class, 'fasilitas_produk', 'fasilitas_id', 'produk_id');
    }
}
