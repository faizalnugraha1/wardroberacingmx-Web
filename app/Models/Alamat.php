<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Alamat extends Model
{
    use HasFactory;

    protected $table = 'user_alamat';
    protected $guarded = [];

    public function enc_id()
    {
        return encrypt($this->id);
    }
    
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function provinsi()
    {
        return $this->belongsTo(Provinsi::class, 'provinsi_id');
    }

    public function kota()
    {
        return $this->belongsTo(Kota::class, 'kota_id');
    }

    public function kecamatan()
    {
        return $this->belongsTo(Kecamatan::class, 'kecamatan_id');
    }

    public function full()
    {
        $full = '';
        $full .= $this->detail;
        if ($this->kelurahan) {
            $full .= ', ' . $this->kelurahan;
        }

        $full .= ', Kec.' . $this->kecamatan->nama . ', ' . $this->kota->nama . ', ' . $this->provinsi->nama;

        if ($this->kode_pos) {
            $full .= ', ' . $this->kode_pos;
        }

        return $full;       
    }
}
