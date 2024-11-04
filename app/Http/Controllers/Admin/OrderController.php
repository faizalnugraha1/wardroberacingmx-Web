<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\SharedFunction;
use App\Models\Invoice;
use App\Models\InvoiceDetail;
use App\Models\OrderLog;
use Carbon\Carbon;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    use SharedFunction;
    public function index(Request $request)
    {
        $title = 'Online Order';
        // dd(Carbon::today());
        $selesai = Invoice::Order()->whereIn('flag_id', [15,16])->count();
        $order_today = Invoice::Order()->whereDate('created_at', Carbon::today())->count();
        $transaksi = Invoice::Order()->where('midtrans_status', 'settlement')->count();
        $transaksi_today = Invoice::Order()->where('midtrans_status', 'settlement')->whereDate('created_at', Carbon::today())->count();

        // if($request->has('show')){
        //     $data = new Invoice();
        //     if($request->show == 'all')
        //     {
        //         $data = $data->all();
        //     } elseif ($request->show == 'selesai') {
        //         $data = $data->where('flag_id', 5)->get();
        //     } elseif ($request->show == 'menunggu') {
        //         $data = $data->where('flag_id', 1)->get();
        //     } elseif ($request->show == 'batal') {
        //         $data = $data->whereIn('flag_id', [2,6])->get();
        //     } elseif ($request->show == 'pengerjaan') {
        //         $data = $data->where('flag_id', 4)->get();
        //     } elseif ($request->show == 'diterima') {
        //         $data = $data->where('flag_id', 3)->get();
        //     }

        //     return view('admin.order', compact('title', 'data'));
        // }
        $data = Invoice::Order()->latest()->get();

        return view('admin.order', compact('title', 'data', 'selesai', 'transaksi', 'order_today', 'transaksi_today'));
    }

    public function refresh()
    {    
        $data = Invoice::Order()->where('midtrans_status', null)->orWhere('midtrans_status', 'pending')->get();        
        foreach ($data as $d) {
            $this->midtrans_check($d);
        }

        return redirect()->back()->with('toast_success', "Berhasil memeriksa ".count($data)." data");
    }

    public function detail($invoice_id, Request $request)
    {
        if($request->ajax()) {
        $data = Invoice::where('kode_invoice', $invoice_id)->with(['detail','order_log'])->first();
        $modal = view('components.modal-order', compact('data'))->render();

        return response()->json([
            'modal' => $modal
        ]);
        }
    }

    public function confirm($invoice_id)
    {
        $data = Invoice::where('kode_invoice', $invoice_id)->first();
        $data->update([
            'flag_id' => 12,
        ]);
        OrderLog::create([
            'invoice_id' => $data->id,
            'flag_id' => 12,
        ]);

        // mail to user

        return redirect()->back()->with('toast_success', 'Order telah dikonfirmasi');
    }

    public function cancel($invoice_id, Request $request)
    {
        $data = Invoice::where('kode_invoice', $invoice_id)->first();
        $this->midtrans_check($data);

        $text ='';
        if ($data->midtrans_status == 'settlement')
        {            
            $data->user->update([
                'deposit' => $data->user->deposit + $data->jumlah,        
            ]);

            $detail_inv = InvoiceDetail::where('invoice_id', $data->id)->where('barang_id', '!=', null)->with(['barang'])->get();  
            foreach ($detail_inv as $d) {
                $d->barang->update([
                    'stok' => $d->barang->stok + $d->qty,
                ]);
            }    
            
            $text = 'Deposit akun user berhasil ditambahkan';
        } else if ($data->midtrans_status == 'pending'){
            $this->init_midtrans();
            $cancel = \Midtrans\Transaction::cancel($data->kode_invoice);
            $data->update([
                'midtrans_status'=> 'cancel',
                'midtrans_status_code'=> $cancel,
            ]);
            $text = 'Pembayaran di Cancel';
        }

        $data->update([
            'flag_id' => 11,
        ]);
        OrderLog::create([
            'invoice_id' => $data->id,
            'flag_id' => 11,
            'keterangan'=> $request->keterangan,
        ]);
        
        // mail to user

        return redirect()->back()->with('toast_success', 'Pesanan berhasil ditolak/dibatalkan. '. $text);
    }

    public function lanjutkan($invoice_id, Request $request)
    {
        if($request->ajax()) {
        $invoice = Invoice::where('kode_invoice', $invoice_id)->first();

        if($invoice->flag_id == 12)
        {
            $data = InvoiceDetail::where('invoice_id', $invoice->id)->where('barang_id', '!=', null)->with(['barang'])->get();  
    
            $modal = view('admin.modal.recheckorder', compact('data'))->render();
    
            return response()->json([
                'modal' => $modal
            ]);
        } else if($invoice->flag_id == 13 || $invoice->flag_id == 14)
        {
            $data = $invoice;
    
            $modal = view('admin.modal.updateresi', compact('data'))->render();
    
            return response()->json([
                'modal' => $modal
            ]);
        }
    }
    }

    public function to_kurir($invoice_id, Request $request)
    {
        $data = Invoice::where('kode_invoice', $invoice_id)->first();
        $data->update([
            'flag_id' => 13,
        ]);
        OrderLog::create([
            'invoice_id' => $data->id,
            'flag_id' => 13,
            'keterangan'=> 'Barang selesai dikemas, siap untuk dikirim.',
        ]);

        return redirect()->back()->with('toast_success', 'Pesanan selesai disiapakan.');
    }
    

    public function update($invoice_id, Request $request)
    {
        $data = Invoice::where('kode_invoice', $invoice_id)->first();
        // dd($request->all());

        if($data->flag_id == 13)
        {
            $data->update([
                'flag_id' => 14,
                'kurir' => $request->kurir,
                'kurir_service' => $request->service,
                'resi' => $request->resi,
            ]);
            OrderLog::create([
                'invoice_id' => $data->id,
                'flag_id' => 14,
            ]);
            $detail_inv = InvoiceDetail::where('invoice_id', $data->id)->where('barang_id', '!=', null)->with(['barang'])->get();
            foreach ($detail_inv as $d) {
                $d->barang->update([
                    'terjual' => $d->barang->terjual + $d->qty,
                ]);
            }

        } else {
            $data->update([
                'kurir' => $request->kurir,
                'kurir_service' => $request->service,
                'resi' => $request->resi,
            ]);
        }

        return redirect()->back()->with('toast_success', 'Kurir/No Resi berhasil diupdate.');
    }
}
