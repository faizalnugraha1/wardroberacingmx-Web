<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderLog extends Model
{
    use HasFactory;

    protected $table = 'order_log';
    protected $guarded = [];

    public function invoice()
    {
        return $this->belongsTo(Invoice::class);
    }

    public function flag()
    {
        return $this->belongsTo(Flag::class);
    }
    
}
