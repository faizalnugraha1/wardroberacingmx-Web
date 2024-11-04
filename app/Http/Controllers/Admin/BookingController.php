<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\SharedFunction;
use App\Mail\BookingInvoiceMail;
use App\Models\Barang;
use App\Models\Booking;
use App\Models\BookingLog;
use App\Models\Invoice;
use App\Models\InvoiceDetail;
use PDF;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\ViewErrorBag;

class BookingController extends Controller
{
    use SharedFunction;
    public function index(Request $request)
    {
        $title = 'Booking Bengkel';
        $selesai = Booking::where('flag_id', 5)->count();
        $menunggu = Booking::where('flag_id', 1)->count();
        $batal = Booking::whereIn('flag_id', [2,6])->count();
        $today = Booking::where('tanggal_booking', date("Y-m-d"))->whereIn('flag_id', [3,4])->count();
        $tanggal = InvoiceDetail::where('barang_id', '!=', null)
                ->whereNotIn('nama', ["Ongkos Kirim","Biaya Admin", "Dana Simpan"])
                ->selectRaw('year(created_at) year, monthname(created_at) month')
                ->groupBy('year', 'month')
                ->orderBy('year', 'desc')
                ->get();

        if($request->has('show')){
            $data = new Booking;
            if($request->show == 'all')
            {
                $data = $data->all();
            } elseif ($request->show == 'selesai') {
                $data = $data->where('flag_id', 5)->get();
            } elseif ($request->show == 'menunggu') {
                $data = $data->where('flag_id', 1)->get();
            } elseif ($request->show == 'batal') {
                $data = $data->whereIn('flag_id', [2,6])->get();
            } elseif ($request->show == 'pengerjaan') {
                $data = $data->where('flag_id', 4)->get();
            } elseif ($request->show == 'diterima') {
                $data = $data->where('flag_id', 3)->get();
            }

            return view('admin.booking', compact('title', 'data', 'selesai', 'menunggu', 'batal', 'today', 'tanggal'));
        }
        $data = Booking::all();

        return view('admin.booking', compact('title', 'data', 'selesai', 'menunggu', 'batal', 'today', 'tanggal'));
    }

    public function list(Request $request)
    {
        if($request->ajax()) {
        $data = Booking::all();

        $response = array();
        foreach($data as $d){
            $response[] = array(
                "title"=> $d->user->name . '-' . $d->kebutuhan .'-'. $d->model_motor,
                "start"=> $d->tanggal_booking.'T'.$d->jam_booking,
                // "backgroundColor"=> '#ffd333',
                "nama"=> $d->user->name,
                "tanggal_booking"=> $d->tanggal_booking,
                "jam_booking"=> $d->jam_booking,
                "kebutuhan"=> $d->kebutuhan,
                "model_motor"=> $d->model_motor,
                "keterangan"=> ($d->keterangan)? $d->keterangan : '-',
                "className" => ($d->flag_id== 1 ? 'bg-info' : ($d->flag_id== 3 || $d->flag_id== 5 ? 'bg-success': ($d->flag_id== 4 ? 'bg-warning' : ($d->flag_id== 2 || $d->flag_id== 6 ? 'bg-danger': ''))))
            );
        }

        return response()->json($response);
        }
    }

    public function detail($booking_id, Request $request)    
    {
        if($request->ajax()) {
            $data = Booking::where('booking_id', $booking_id)->first();
            $invoice = Invoice::where('booking_id', $data->id)->with(['detail'])->first();

            $modal = view('admin.modal.booking-detail', compact('data', 'invoice'))->render();

            return response()->json([
                'modal' =>  $modal
            ]); 
        }
    }

    public function approve($booking_id, Request $request)
    {
        $data = Booking::where('booking_id', $booking_id)->first();

        $data->update([
            'flag_id' => 3,
        ]);

        BookingLog::create([
            'booking_id' => $data->id,
            'flag_id' => 3,
        ]);

        return redirect()->route('admin.booking.index')->with('toast_success', 'Jadwal Booking berhasil di approve');

    }

    public function batal($booking_id, Request $request)
    {
        $data = Booking::where('booking_id', $booking_id)->first();

        $data->update([
            'flag_id' => 2,
        ]);

        BookingLog::create([
            'booking_id' => $data->id,
            'flag_id' => 2,
            'keterangan' => $request->keterangan,
        ]);

        return redirect()->route('admin.booking.index')->with('toast_success', 'Jadwal Booking berhasil ditolak/dibatalkan');

    }

    public function kerjakan($booking_id)
    {
        $data = Booking::where('booking_id', $booking_id)->first();

        $data->update([
            'flag_id' => 4,
        ]);

        BookingLog::create([
            'booking_id' => $data->id,
            'flag_id' => 4,
        ]);

        return redirect()->route('admin.booking.index')->with('toast_success', 'Jadwal Booking sedang dalam proses pengerjaan');

    }

    public function checkout($booking_id)
    {
        $title = 'Checkout';
        $data = Booking::where('booking_id', $booking_id)->first();
        $invoice = strtoupper('INV'.$this->generate_uniq(10));
        
        return view('admin.booking-chekout', compact('title','data', 'invoice'));
    }

    public function store($booking_id, Request $request)
    {
        
        $this->validate($request, [
            'kode_invoice' => 'required',
            'nama_jasa' => 'required',
            'harga_jasa' => 'required',
            'qty_jasa' => 'required',
            'total_jasa' => 'required',
        ]);

        $bk= Booking::where('booking_id', $booking_id)->first();

        $bk->update([
            'flag_id' => 5,
        ]);

        BookingLog::create([
            'booking_id' => $bk->id,
            'flag_id' => 5,
        ]);

        $invoice = Invoice::create([
            'kode_invoice' => $request->kode_invoice,
            'booking_id' => $bk->id,
            'flag_id' => 5,
            'user_id'=> $bk->user->id,
        ]);

        $total = 0;

        foreach($request->nama_jasa as $i => $value){
            InvoiceDetail::create([
                'invoice_id' => $invoice->id,
                'nama' => $request->nama_jasa[$i],
                'harga' => $request->harga_jasa[$i],
                'qty' => $request->qty_jasa[$i],
                'subtotal' => $request->total_jasa[$i],            
            ]);

            $total += $request->total_jasa[$i];
        }

        if($request->has('id_barang'))
        {
            foreach($request->id_barang as $i => $value){
                InvoiceDetail::create([
                    'invoice_id' => $invoice->id,
                    'barang_id' => $request->id_barang[$i],
                    'nama' => $request->nama_barang[$i],
                    'harga' => $request->harga_barang[$i],
                    'qty' => $request->qty_barang[$i],
                    'subtotal' => $request->total_barang[$i],
                ]);

                $barang = Barang::find($request->id_barang[$i]);
                $barang->update([
                    'stok' => $barang->stok - $request->qty_barang[$i],
                    'terjual' => $barang->terjual + $request->qty_barang[$i],
                ]);

                $total += $request->total_barang[$i];
            }
        }

        $invoice->update([
            'jumlah' => $total,
        ]);

        //kirim email
        Mail::to($bk->user->email)->send(new BookingInvoiceMail($bk));
        
        return redirect()->route('admin.booking.index')->with('toast_success', 'Jadwal Booking berhasil dicheckout');
    }

    public function addbarang(Request $request)
    {
        if($request->ajax()) {
            $barang = Barang::all();

            $modal = view('admin.modal.booking-addbarang', compact('barang'))->render();

            return response()->json([
                'modal' =>  $modal
            ]);
        }
    }

    public function print(Request $request)
    {
        $this->validate($request, [
            'tanggal' => 'required',
        ]);
        
        $tanggal = Carbon::parse($request->tanggal);
        $data = InvoiceDetail::where('barang_id', null)
                    ->whereYear('created_at', $tanggal->year)
                    ->whereMonth('created_at', $tanggal->month)
                    ->whereNotIn('nama', ["Ongkos Kirim","Biaya Admin", "Dana Tersimpan"])
                    ->orderBy('created_at')
                    ->with('invoice.booking')                    
                    ->get();
        // dd($data);

        $pdf = PDF::loadview('admin.print.laporan-booking', compact('data', 'tanggal'))->setPaper('A4', 'potrait');
        return $pdf->stream();
    }
}
