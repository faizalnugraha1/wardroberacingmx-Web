<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Booking extends Model
{
    use HasFactory;

    protected $table = 'booking';
    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function flag()
    {
        return $this->belongsTo(Flag::class);
    }

    public function booking_log()
    {
        return $this->hasMany(BookingLog::class)->latest();
    }

    public function pengerjaan()
    {
        return $this->hasMany(BookingLog::class)->pengerjaan();
    }
    public function selesai()
    {
        return $this->hasMany(BookingLog::class)->selesai();
    }

    public function scopeOnUser($query)
    {
        return $query->where('user_id', Auth::user()->id);
    }

        
}