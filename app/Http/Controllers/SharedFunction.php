<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\Cart;
use App\Models\Invoice;
use App\Models\InvoiceDetail;
use App\Models\Kategori;
use App\Models\OrderLog;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Kavist\RajaOngkir\Facades\RajaOngkir;

trait SharedFunction
{

    function init_midtrans()
    {
        \Midtrans\Config::$serverKey = env('MIDTRANS_SERVER_KEY');
        \Midtrans\Config::$isProduction = false;
        \Midtrans\Config::$isSanitized = true;
        \Midtrans\Config::$is3ds = true;
    }

    function midtrans_check(Invoice $invoice)
    {
        try {
            $this->init_midtrans();
            $check = \Midtrans\Transaction::status($invoice->kode_invoice);            
            $result = true;
        } catch (Exception $e) {           
            $result = false;
        }

        if($result == true) {
        
            if($check->transaction_status == 'settlement' && $invoice->midtrans_status != $check->transaction_status) {
                $invoice->update([
                    'midtrans_transaksi_id' => $check -> transaction_id,
                    'midtrans_status'=> $check->transaction_status,
                    'midtrans_status_code'=> $check->status_code,
                    'midtrans_date'=> $check->settlement_time,
                    'flag_id' => 10,
                ]);
                OrderLog::create([
                    'invoice_id' => $invoice->id,
                    'flag_id' => 10,
                    'keterangan' => 'Pembayaran berhasil',
                ]);
                $detail_inv = InvoiceDetail::where('invoice_id', $invoice->id)->where('barang_id', '!=', null)->with(['barang'])->get();  
                foreach ($detail_inv as $d) {
                    $d->barang->update([
                        'stok' => $d->barang->stok - $d->qty,
                    ]);
                }                  
            } else if($check->transaction_status == 'expire' || $check->transaction_status == 'cancel' || $check->transaction_status == 'failure' && $invoice->midtrans_status != $check->transaction_status)
            {
                $invoice->update([
                    'midtrans_transaksi_id' => $check -> transaction_id,
                    'midtrans_status'=> $check->transaction_status,
                    'midtrans_status_code'=> $check->status_code,
                    'flag_id' => 8,
                ]);
                OrderLog::create([
                    'invoice_id' => $invoice->id,
                    'flag_id' => 8,
                ]);
            } else if($check->transaction_status == 'pending' && $invoice->midtrans_status != $check->transaction_status)
            {
                $invoice->update([
                    'midtrans_transaksi_id' => $check -> transaction_id,
                    'midtrans_status'=> $check->transaction_status,
                    'midtrans_status_code'=> $check->status_code,
                ]);
            }

            if($check->payment_type == 'bank_transfer')
            {
                return [
                    'status' => $check->transaction_status,
                    'va' => $check->va_numbers[0]->va_number,
                    'bank' => $check->va_numbers[0]->bank,
                ];
            }
        } else {
            if(now() > $invoice->expired_date && $invoice->flag_id == 7)
            {
                $invoice->update([
                    'flag_id' => 8,
                ]);
                OrderLog::create([
                    'invoice_id' => $invoice->id,
                    'flag_id' => 8,
                ]);
            }
        }
    }

    function invoice_check()
    {

    }

    function generate_uniq($limit)
    {
        $uniq = substr(base_convert(sha1(uniqid(mt_rand())), 16, 36), 0, $limit);

        return $uniq;
    }

    function generate_random_order()
    {
        if(Auth::check()) {
            return date('mdY').Auth::user()->id;
        } else {
            return date('mdY').rand(0,9);
        }
    }

    function kategori_to_id($kategori)
    {
        $array_kategori = [];
        foreach ($kategori as $k) {
            $kt = Kategori::where('slug', $k)->first();
            array_push($array_kategori, $kt->id);
        }
        return $array_kategori;
    }

    function brand_to_id($brand)
    {
        $array_brand = [];
        foreach ($brand as $b) {
            $br = Brand::where('slug', $b)->first();
            array_push($array_brand, $br->id);
        }
        return $array_brand;
    }

    function get_ongkir($destination, $berat)
    {
        $origin = 23;
        $kurir = ['jne','tiki','pos'];
        $result = [];
        foreach ($kurir as $k) {
            $daftarOngkir = RajaOngkir::ongkir([
                'origin'        => $origin,     // ID kota/kabupaten asal
                'destination'   => $destination,      // ID kota/kabupaten tujuan
                'weight'        => $berat,    // berat barang dalam gram
                'courier'       => $k    // kode kurir pengiriman: ['jne', 'tiki', 'pos'] untuk starter
            ])->get();
            array_push($result, $daftarOngkir[0]);
            // echo '<pre>';
            // print_r($daftarProvinsi);
            // echo '</pre>';
        }
        // dd($result);
        return $result;
    }

    function clear_cart($id)
    {
        Cart::where('user_id', $id)->delete();
    }

    function SwalNotif($icon, $title, $text)
    {
        Session::flash('swal', [
            'title' => $title,
            'text' => $text,
            'icon' => $icon,
        ]);
    }
}
