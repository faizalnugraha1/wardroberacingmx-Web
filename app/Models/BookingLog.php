<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BookingLog extends Model
{
    use HasFactory;

    protected $table = 'booking_log';
    protected $guarded = [];

    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }

    public function flag()
    {
        return $this->belongsTo(Flag::class);
    }

    public function scopePengerjaan($query)
    {
        return $query->where('flag_id', 4);
    }

    public function scopeSelesai($query)
    {
        return $query->where('flag_id', 5);
    }

}
