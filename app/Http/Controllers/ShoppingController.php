<?php

namespace App\Http\Controllers;

use App\Models\Alamat;
use App\Models\Barang;
use App\Models\Cart;
use App\Models\Favorite;
use App\Models\Invoice;
use App\Models\InvoiceDetail;
use App\Models\OrderLog;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Kavist\RajaOngkir\Facades\RajaOngkir;
use App\Http\Controllers\Trait\MetaTrait;
class ShoppingController extends Controller
{
    use SharedFunction;
    public function fav(Request $request)
    {
        $data = new Favorite();

        if($request->has('show'))
        {
            $page = $request->show;
        } else {
            $page = 12;
        }
        
        
        $data = $data->latest()->paginate($page)->appends([
            'show' => $request->show,
        ]);

        MetaTrait::set([
            'title' => 'Favourite',
        ]);

        return view('user.fav', compact('data'));        
    }

    public function cart()
    {
        $data = Cart::where('user_id', Auth::user()->id)->whereHas('barang', function($query) {
            $query->where('stok', '>', 0);
        })->with(['barang'])->get();

        $kosong = Cart::where('user_id', Auth::user()->id)->whereHas('barang', function($query) {
            $query->where('stok', '<=', 0);
        })->with(['barang'])->get();

        $subtotalbe = 0;
        foreach ($data as $d) {
            $subtotalbe += $d->barang->harga_jual * $d->qty;
        }        

        MetaTrait::set([
            'title' => 'Cart',
        ]);

        return view('cart', compact('data', 'kosong', 'subtotalbe'));
    }

    public function checkout(Request $request)
    {   
        $cart_count = count(Auth::user()->cart);

        if (url()->previous() == route('cart') || $cart_count > 0)    
        {            
            $data = Cart::where('user_id', Auth::user()->id)->whereHas('barang', function($query) {
                $query->where('stok', '>', 0);
            })->with(['barang'])->get();

            $berat = 0;
            $subtotalbe = 0;
            foreach ($data as $d) {
                $berat += $d->barang->berat * $d->qty;
                $subtotalbe += $d->barang->harga_jual * $d->qty;
                if ($d->qty > $d->barang->stok) {
                    $d->update([
                        'qty' => $d->barang->stok
                    ]);
                    $this->SwalNotif('info', 'Perhatian', 'Terdapat barang yang tidak tersedia');
                }
            }
            
            $alamat = Auth::user()->alamat;

            $ongkir = $this->get_ongkir($alamat[0]->kota_id, $berat);

            $invoice = strtoupper('INV'.$this->generate_uniq(10));

            MetaTrait::set([
                'title' => 'Checkout',
            ]);

            return view('checkout', compact('data', 'subtotalbe', 'berat', 'alamat', 'ongkir', 'invoice'));
        } else {
            return redirect()->route('cart');
        }

    }

    public function update_ongkir(Request $request)
    {
        if($request->ajax()) {
            if (Auth::check()) {
                $id = decrypt($request->id);
                $alamat = Alamat::find($id);
                $ongkir = $this->get_ongkir($alamat->kota_id, $request->berat);
                
                $view = view('components.kurir', compact('ongkir'))->render();

                $alamat = $alamat->detail ." <br> Kec. ". $alamat->kecamatan->nama ."<br> ". $alamat->kota->nama ."<br> ". $alamat->provinsi->nama ."<br>". $alamat->kode_pos ."<br>";

                return response()->json([
                    'view' => $view,
                    'alamat' => $alamat
                ]);
            } else {
                return response()->json(['status' => 'error', 'title' => 'Gagal', 'message' => 'Anda harus login terlebih dahulu']);
            }
        }
    }

    public function pay(Request $request)
    {
        if(request()->ajax()){
            $this->init_midtrans();
            
            $alamat = Alamat::find(decrypt($request->alamat_id));
                    
            $shipping_address = array(
                'first_name'   => Auth::user()->name,
                'address'      => $alamat->detail,
                'city'         => $alamat->kota->nama,
                'postal_code'  => $alamat->kode_pos,
                'phone'        => Auth::user()->no_hp,
                // 'country_code' => 'IDN'
            );
            
            $customer_details =[
                'first_name'    => Auth::user()->name,
                'email'         => Auth::user()->email,
                'phone'         => Auth::user()->no_hp,
                'shipping_address' => $shipping_address
            ];
            
            
            $items = [];
            $inv_detail = [];
            $total = 0;
            $data = Cart::where('user_id', Auth::user()->id)->whereHas('barang', function($query) {
                $query->where('stok', '>', 0);
            })->with(['barang'])->get();
            foreach ($data as $d) {
                $items[] = [
                    'id' => $d->barang->id,
                    'price' => $d->barang->harga_jual,
                    'quantity' => $d->qty,
                    'name' => $d->barang->nama
                ];
                $total += $d->barang->harga_jual * $d->qty;
                $inv_detail[] = [
                    'nama' => $d->barang->nama,
                    'barang_id' => $d->barang->id,
                    'harga' => $d->barang->harga_jual,
                    'qty' => $d->qty,
                    'subtotal' => $d->barang->harga_jual * $d->qty,
                ];
            }
            
            array_push($items, [
                'id' => 'admin',
                'price' => env('MIDTRANS_ADMIN'),
                'quantity' => 1,
                'name' => 'Biaya Admin'
            ]);
            $total += env('MIDTRANS_ADMIN');
            array_push($inv_detail, [
                'nama' => 'Biaya Admin',
                'harga' => env('MIDTRANS_ADMIN'),
                'qty' => 1,
                'subtotal' => env('MIDTRANS_ADMIN')
            ]);
            
            array_push($items, [
                'id' => 'ongkir',
                'price' => $request->ongkir,
                'quantity' => 1,
                'name' => 'Ongkos Kirim'
            ]);
            $total += $request->ongkir;
            array_push($inv_detail, [
                'nama' => 'Ongkos Kirim',
                'harga' => $request->ongkir,
                'qty' => 1,
                'subtotal' => $request->ongkir,
            ]);

            if(request()->has('dana_tersimpan'))
            {
                array_push($items, [
                    'id' => 'deposit',
                    'price' => -Auth::user()->deposit,
                    'quantity' => 1,
                    'name' => 'Dana Tersimpan'
                ]);
                $sisa_bayar = abs($request->dana_tersimpan - $total);
                // $total -= Auth::user()->deposit;
                array_push($inv_detail, [
                    'nama' => 'Dana Tersimpan',
                    'harga' => -Auth::user()->deposit,
                    'qty' => 1,
                    'subtotal' => -Auth::user()->deposit,
                ]);                
                $transaction_details = array(
                    'order_id'    => $request->invoice,
                    'gross_amount'  =>  $sisa_bayar
                );
                Auth::user()->update([
                    'deposit' => 0
                ]);
            } else {

                $transaction_details = array(
                    'order_id'    => $request->invoice,
                    'gross_amount'  =>  $total
                );
            }
            
            
            
            $transaction_data = array(
                'transaction_details' => $transaction_details,
                'item_details'        => $items,
                'customer_details'    => $customer_details
            );
            
            $snapToken = \Midtrans\Snap::getSnapToken($transaction_data);
    
            // dd($request->all(), $alamat, $customer_details, $items, $total, $transaction_data, $snapToken);
    
            $invoice = Invoice::create([
                'kode_invoice' => $request->invoice,
                'flag_id' => 7,
                'user_id' => Auth::user()->id,
                'jumlah' => $total,
                'kurir' => $request->kurir_code,
                'kurir_service' => $request->kurir_service,
                'alamat' => $alamat->full(),
                'expired_date' => Carbon::now()->addDays(1),
                'finish_date' => Carbon::now()->addDays((int)$request->kurir_service + 7),
            ]);
            OrderLog::create([
                'invoice_id' => $invoice->id,
                'flag_id' => 7,
            ]);
            $invoice->detail()->createMany($inv_detail);
    
            return response()->json([
                'token' => $snapToken,
                // 'invoice' => $request->invoice,
                'cancel' => route('pay.cancel',['kode_invoice' => $request->invoice]),
            ]);
        } else {
            // dd($request->all());

            $alamat = Alamat::find(decrypt($request->alamat_id));

            $items = [];
            $inv_detail = [];
            $total = 0;
            $data = Cart::where('user_id', Auth::user()->id)->whereHas('barang', function($query) {
                $query->where('stok', '>', 0);
            })->with(['barang'])->get();
            foreach ($data as $d) {
                $items[] = [
                    'id' => $d->barang->id,
                    'price' => $d->barang->harga_jual,
                    'quantity' => $d->qty,
                    'name' => $d->barang->nama
                ];
                $total += $d->barang->harga_jual * $d->qty;
                $inv_detail[] = [
                    'nama' => $d->barang->nama,
                    'barang_id' => $d->barang->id,
                    'harga' => $d->barang->harga_jual,
                    'qty' => $d->qty,
                    'subtotal' => $d->barang->harga_jual * $d->qty,
                ];
            }

            $total += env('MIDTRANS_ADMIN');
            array_push($inv_detail, [
                'nama' => 'Biaya Admin',
                'harga' => env('MIDTRANS_ADMIN'),
                'qty' => 1,
                'subtotal' => env('MIDTRANS_ADMIN')
            ]);

            $total += $request->ongkir;
            array_push($inv_detail, [
                'nama' => 'Ongkos Kirim',
                'harga' => $request->ongkir,
                'qty' => 1,
                'subtotal' => $request->ongkir,
            ]);

            if(request()->has('dana_tersimpan'))
            {
                $sisa = $request->dana_tersimpan - $total;
                // $total -= $request->dana_tersimpan;
                array_push($inv_detail, [
                    'nama' => 'Dana Tersimpan',
                    'harga' => -Auth::user()->deposit,
                    'qty' => 1,
                    'subtotal' => -Auth::user()->deposit,
                ]);                
            }

            // dd($request->all(), $alamat, $items, $total, $inv_detail, $sisa);

            $invoice = Invoice::create([
                'kode_invoice' => $request->invoice,
                'flag_id' => 10,
                'user_id' => Auth::user()->id,
                'midtrans_status'=> 'settlement',
                'jumlah' => $total,
                'kurir' => $request->kurir_code,
                'kurir_service' => $request->kurir_service,
                'alamat' => $alamat->full(),
                'expired_date' => Carbon::now()->addDays(1),
                'finish_date' => Carbon::now()->addDays((int)$request->kurir_service + 7),
            ]);
            OrderLog::create([
                'invoice_id' => $invoice->id,
                'flag_id' => 10,
                'keterangan' => 'Pembayaran menggunakan Dana Tersimpan',
            ]);
            $invoice->detail()->createMany($inv_detail);

            $detail_inv = InvoiceDetail::where('invoice_id', $invoice->id)->where('barang_id', '!=', null)->with(['barang'])->get();
            foreach ($detail_inv as $d) {
                $d->barang->update([
                    'stok' => $d->barang->stok - $d->qty,
                ]);
            }

            $invoice->user->update([
                'deposit' => $sisa,
            ]);

            $this->SwalNotif('success', 'Berhasil', 'Pesanan berhasil dibuat');
            return redirect()->route('user.order.history')->with('success', 'Pembayaran anda berhasil dilakukan');
        }
    }

    public function pay_complete(Request $request)
    {
        $this->init_midtrans();

        $detail = \Midtrans\Transaction::status($request->order_id);

        $invoice = Invoice::where('kode_invoice', $request->order_id)->first();
        // status_code=200&transaction_status=settlement
        $invoice->update([
            'midtrans_transaksi_id' => $detail -> transaction_id,
            'midtrans_status'=> $request->transaction_status,
            'midtrans_status_code'=> $request->status_code,
            'midtrans_date'=> $detail->settlement_time,
        ]);        

        $this->clear_cart($invoice->user_id);

        if($request->transaction_status == 'settlement') {
            $invoice->update([
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

            $this->SwalNotif('success', 'Pembayaran Selesai', 'Pembayaran anda berhasil dilakukan');
        }
        
        return redirect()->route('user.order.history')->with('success', 'Pembayaran anda berhasil dilakukan');
    }

    public function pay_cancel($kode_invoice)
    {
        Invoice::where('kode_invoice', $kode_invoice)->delete();

        return response()->json([
            'status' => 'error',
            'title' => 'Dibatalkan',
            'message' => 'Pembayaran anda telah dibatalkan'
        ]);
    }
}
