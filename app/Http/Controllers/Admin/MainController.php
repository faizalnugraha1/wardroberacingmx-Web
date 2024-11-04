<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use App\Models\InvoiceDetail;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MainController extends Controller
{
    public function index()
    {
        $diproses = Invoice::whereIn('flag_id', [12,13])->whereMonth('created_at', Carbon::now()->month)->count();
        $kurir = Invoice::where('flag_id', 14)->whereMonth('created_at', Carbon::now()->month)->count();
        $selesai = Invoice::whereIn('flag_id', [15,16])->whereMonth('created_at', Carbon::now()->month)->count();
        $total = Invoice::where('booking_id', null)->whereMonth('created_at', Carbon::now()->month)->count();
        $barang = InvoiceDetail::where('barang_id', '!=', null)
                    ->whereMonth('created_at', Carbon::now()->month) 
                    ->whereHas('invoice', function($query) {
                        $query->whereIn('flag_id', [5,14,15,16]);
                    })            
                    ->selectRaw("SUM(subtotal) as pemasukan")                   
                    ->first();
        $jasa = InvoiceDetail::where('barang_id', null)
                    ->whereNotIn('nama', ["Ongkos Kirim","Biaya Admin", "Dana Tersimpan"])
                    ->whereMonth('created_at', Carbon::now()->month)           
                    ->selectRaw("SUM(subtotal) as pemasukan")                   
                    ->first();
        $perbulan = InvoiceDetail::where('barang_id', '!=', null)
                    ->whereMonth('created_at', Carbon::now()->month)
                    ->whereHas('invoice', function($query) {
                        $query->whereIn('flag_id', [5,14,15,16]);
                    })    
                    ->selectRaw("SUM(subtotal) as pemasukan")
                    ->selectRaw("SUM(qty) as total_barang")
                    ->selectRaw('day(created_at) Day')
                    ->groupBy('Day')
                    ->get();
        $perbulan2 = InvoiceDetail::where('barang_id', null)
                    ->whereMonth('created_at', Carbon::now()->month)
                    ->whereNotIn('nama', ["Ongkos Kirim","Biaya Admin", "Dana Simpan"])
                    ->selectRaw("SUM(subtotal) as pemasukan")
                    ->selectRaw('day(created_at) Day')
                    ->groupBy('Day')
                    ->get();

        $data =[];
        $data2 =[];
        
        $tanggal = array_map(function($value){
            return Carbon::now()->format('Y-m-').$value;
        }, $perbulan->pluck('Day')->toArray());
        
        array_push($data, $tanggal);
        array_push($data, $perbulan->pluck('pemasukan')->toArray());
        array_push($data, $perbulan->pluck('total_barang')->toArray());
        
        $tanggal2 = array_map(function($value){
            return Carbon::now()->format('Y-m-').$value;
        }, $perbulan2->pluck('Day')->toArray());
        // dd($tanggal2);
        
        array_push($data2, $tanggal2);
        array_push($data2, $perbulan2->pluck('pemasukan')->toArray());

        $terlaris = InvoiceDetail::where('barang_id', '!=', null)
                ->whereHas('invoice', function($query) {
                    $query->whereIn('flag_id', [5,14,15,16]);
                }) 
                ->selectRaw("barang_id")
                ->selectRaw("SUM(qty) as total_terjual")
                ->selectRaw("SUM(subtotal) as total_pemasukan")
                ->groupBy('barang_id')
                ->orderBy('total_terjual', 'desc')
                ->with('barang')
                ->limit(5)
                ->get();
        // dd($terlaris);
        // echo '<pre>';
        // print_r($terlaris);
        // echo '</pre>';

        $transaksi = Invoice::where('booking_id', null)->where('midtrans_status', 'settlement')->limit(5)->orderBy('midtrans_date', 'desc')->get();

        return view('admin.dashboard', compact('diproses', 'kurir', 'selesai', 'total', 'barang', 'jasa', 'data', 'data2', 'transaksi','terlaris'));
    }
}
