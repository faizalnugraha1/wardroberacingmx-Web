<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Flag extends Model
{
    use HasFactory;

    protected $table = 'flags';
    protected $guarded = [];

    public function badge()
    {
        if ($this->status == 'Menunggu')
        {
            return 'badge-warning';
        }                          
        else if ($this->status == 'Dibatalkan')
        {
            return 'badge-danger';
        }
        else if ($this->status == 'Diterima' || $this->status == 'Diproses' || $this->status == 'Dalam Perjalanan')
        {
            return 'badge-success';
        }
        else if ($this->status == 'Pengerjaan')
        {
            return 'badge-primary';
        }
        else if ($this->status == 'Selesai')
        {
            return 'badge-success';
        }
    }

    public function icon()
    {
        if ($this->status == 'Menunggu')
        {
            return '<i class="fas fa-clock"></i>';
        } else if ($this->status == 'Dibatalkan')
        {
            return '<i class="fas fa-xmark"></i>';
        } else if ($this->status == 'Diterima')
        {
            return '<i class="fas fa-check"></i>';
        } else if ($this->status == 'Pengerjaan' || $this->status == 'Diproses')
        {
            if ($this->id == 4)
            {
                return '<i class="fas fa-wrench"></i>';
            } else if ($this->id == 12)
            {
                return '<i class="fas fa-box-open"></i>';                
            } else if ($this->id == 13)
            {
                return '<i class="fas fa-box"></i>';
            }
        } else if ($this->status == 'Dalam Perjalanan')
        {
            return '<i class="fas fa-shipping-fast"></i>';
        } else if ($this->status == 'Selesai')
        {
            return '<i class="fas fa-flag"></i>';
        }
    }
}