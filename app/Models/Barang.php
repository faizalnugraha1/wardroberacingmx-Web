<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Barang extends Model
{
    use SoftDeletes;

    protected $table = 'barang';
    protected $guarded = [];

    public function images()
    {
        return $this->hasMany(FotoBarang::class);
    }

    public function kategori()
    {
        return $this->belongsTo(Kategori::class);
    }

    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }

    public function scopeFilter($q)
    {
        $q->whereIn('kategori_id', [1,2]);

        return $q;
    }

    public function scopeAvailable($query)
    {
        return $query->where('stok', '>', 0);
    }

}
