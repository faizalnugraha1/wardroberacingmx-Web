<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class InvoiceDetail extends Model
{
    use HasFactory;

    protected $table = 'invoice_detail';
    protected $guarded = [];

    public function enc_id()
    {
        return encrypt($this->id);
    }

    public function invoice()
    {
        return $this->belongsTo(Invoice::class);
    }

    public function barang()
    {
        return $this->belongsTo(Barang::class)->withTrashed();
    }

    public function scopeBarang($query)
    {
        return $query->where('barang_id', '!=', null);
    }

    public function scopeOnUser($query)
    {
        return $query->whereHas('invoice', function($query) {
            $query->where('user_id', Auth::user()->id);
        });
    }

    public function scopeComplete($query)
    {
        return $query->whereHas('invoice', function($query) {
                $query->whereIn('flag_id', [5,15,16]);
            });
    }
}
