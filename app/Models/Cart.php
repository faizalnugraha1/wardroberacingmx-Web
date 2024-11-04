<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    use HasFactory;

    protected $table = 'user_cart';
    protected $guarded = [];

    public function enc_id()
    {
        return encrypt($this->id);
    }

    public function barang()
    {
        return $this->belongsTo(Barang::class);
    }

    public function readystok()
    {
        return $this->belongsTo(Barang::class, 'barang_id')->available();
    }
}