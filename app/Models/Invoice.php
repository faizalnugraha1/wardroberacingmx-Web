<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Invoice extends Model
{
    use HasFactory;

    protected $table = 'invoice';
    protected $guarded = [];
    
    public function detail()
    {
        return $this->hasMany(InvoiceDetail::class)->orderBy('barang_id', 'desc');
    }

    public function order_log()
    {
        return $this->hasMany(OrderLog::class)->latest();
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }

    public function flag()
    {
        return $this->belongsTo(Flag::class);
    }

    public function scopeOnUser($query)
    {
        return $query->where('user_id', Auth::user()->id);
    }

    public function scopeOrder($query)
    {
        return $query->where('booking_id', null);
    }

    public function scopeBooking($query)
    {
        return $query->where('booking_id', '!=', null);
    }

    public function midtrans_badge()
    {
        if ($this->midtrans_status == 'pending')
        {
            return 'badge-warning';
        }                          
        else if ($this->midtrans_status == 'failure' || $this->midtrans_status == 'expire' || $this->midtrans_status == 'cancel')
        {
            return 'badge-danger';
        }
        else if ($this->midtrans_status == 'settlement')
        {
            return 'badge-success';
        }
    }
}
