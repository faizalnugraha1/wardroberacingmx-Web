<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Favorite extends Model
{
    use HasFactory;

    protected $table = 'user_favorite';
    protected $guarded = [];

    public function barang()
    {
        return $this->belongsTo(Barang::class);
    }
}