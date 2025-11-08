<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Str;


class Produk extends Model
{
    use HasFactory;

    protected $table = 'produks';

    protected $fillable = ['nama_produk', 'slug', 'deskripsi_produk', 'harga_produk'];

    // Untuk Konsep UUID
    protected $keyType = 'string';
    public $incrementing = false;

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            // hanya buat ID baru kalau belum ada
            if (empty($model->{$model->getKeyName()})) {
                $model->{$model->getKeyName()} = (string) Str::uuid();
            }
        });
    }

    public function fasilitas()
    {
        return $this->belongsToMany(Fasilitas::class, 'fasilitas_produk', 'produk_id', 'fasilitas_id');
    }

     // otomatis tambahkan gambar_url di JSON
    //  protected $appends = ['gambar_url'];

    //  public function getGambarUrlAttribute()
    //  {
    //     if (!$this->gambar) return null;
    //     return asset('storage/' . $this->gambar);
    //  }

     public function gambars()
        {
            return $this->hasMany(ProdukGambar::class);
        }

}
