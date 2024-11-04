<?php

namespace App\Http\Controllers;

use App\Models\Alamat;
use App\Models\Barang;
use App\Models\Booking;
use App\Models\Invoice;
use App\Models\InvoiceDetail;
use App\Models\OrderLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Trait\MetaTrait;

class UserController extends Controller
{
    use SharedFunction;

    public function profile()
    {
        return view('user.profile');
    }

    public function pembelian()
    {
        $data = Invoice::OnUser()->Order()->latest()->paginate(10);

        MetaTrait::set([
            'title' => 'Order History',
        ]);
        return view('user.history-order', compact('data'));
    }

    public function pembelian_show($id)
    {
        $data = Invoice::where('kode_invoice', $id)->with(['detail','order_log'])->first();
        $result =[];

        if($data->midtrans_status == 'pending' || $data->midtrans_status == null)
        {
            $result = $this->midtrans_check($data);
        }

        $modal = view('components.modal-order', compact('data', 'result'))->render();

        return response()->json([
            'modal' => $modal
        ]);
    }

    public function pembelian_finish($id)
    {
        $data = Invoice::where('kode_invoice', $id)->with(['detail','order_log'])->first();

        $data->update([
            'flag_id' => 15,
        ]);
        OrderLog::create([
            'invoice_id' => $data->id,
            'flag_id' => 15,
        ]);

        // mail to user

        $this->SwalNotif('success', 'Berhasil', 'Pesanan telah diterima.');
        return redirect()->back()->with('success', 'Pesanan telah diterima.');
    }

    public function booking()
    {
        $data = Booking::OnUser()->latest()->paginate(10);
        MetaTrait::set([
            'title' => 'History Booking',
        ]);
        return view('user.history-booking', compact('data'));
    }

    public function booking_show($id)
    {
        $data = Booking::where('booking_id', $id)->with(['booking_log'])->first();

        $modal = view('components.modal-booking', compact('data'))->render();

        return response()->json([
            'modal' => $modal
        ]);
    }

    public function review()
    {
        $data = InvoiceDetail::OnUser()
                ->Barang()
                ->Complete()
                ->latest()
                ->with('invoice')
                ->paginate(5)
                ->groupBy('invoice_id');

        MetaTrait::set([
            'title' => 'Review',
        ]);

        return view('user.reviews', compact('data'));
    }

    public function create_review($id)
    {
        if(request()->ajax())
        {
            $id = decrypt($id);
            // dd($id);
            $data = InvoiceDetail::find($id);
            // dd($data->barang);

            $modal = view('components.modal-review', compact('data'))->render();

            // dd($modal);

            return response()->json([
                'status' => 'success',
                'modal' => $modal
            ]);
        }
    }

    public function view_review($id)
    {
        if(request()->ajax())
        {
            $id = decrypt($id);

            $data = InvoiceDetail::find($id);

            $modal = view('components.modal-review-view', compact('data'))->render();

            return response()->json([
                'status' => 'success',
                'modal' => $modal
            ]);
        }
    }

    public function store_review(Request $request)
    {
        $id = decrypt($request->id);
        $data = InvoiceDetail::find($id);
        if($data->is_updated == 1)
        {
            $this->SwalNotif('error', 'Gagal', 'Anda sudah melakukakn update review untuk barang ini.');
            return redirect()->back()->with('error', 'Anda sudah melakukakn update review untuk barang ini.');
        }        

        $validator = Validator::make($request->all(), [
            'rating' => 'required',
        ]);

        if ($validator->fails()) {
            $this->SwalNotif('error', 'Gagal', $validator->errors()->first());
            return redirect()->back()->withErrors($validator);
        } else {            
            if($request->rating != null)
            {
                $data->update([
                    'is_updated' => 1,
                ]);
            }

            $data->update([
                'rating' => $request->rating,
                'review' => $request->review,
            ]);

            $all = InvoiceDetail::where('barang_id', $data->barang_id)
                    ->where('rating', '!=', null)
                    ->selectRaw("SUM(rating) as total_rating, COUNT(rating) as total_review")
                    ->first();

            // dd($all, $all->total_rating / $all->total_review);

            $barang = Barang::find($data->barang_id);
            $barang->update([
                'rating' => $all->total_rating / $all->total_review,
                'total_review' => $all->total_review,
            ]);

            $this->SwalNotif('success', 'Berhasil', 'Review berhasil disimpan');
            return redirect()->back()->with('success', 'Review berhasil disimpan');
        }
    }

    public function update_general(Request $request)
    {

        $user = Auth::user();
        $user->update([
            'name' => $request->name,
            'no_hp'=> $request->no_hp,
        ]);

        $this->SwalNotif('success', 'Berhasil', 'Data berhasil diperbarui');
        return redirect()->route('user.settings')->with('success', 'Data berhasil diperbarui');

    }

    public function update_password(Request $request)
    {        
        $rules = array(
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        );    

        $request->all();
        $validator = Validator::make($request->all(), $rules); 

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()]);
        } else {

            $user = Auth::user();
            $user->update([
                'password' => Hash::make($request->password),
            ]);
    
            $this->SwalNotif('success', 'Berhasil', 'Password berhasil diperbarui');
            return redirect()->route('user.settings')->with('success', 'Data berhasil diperbarui');
        }

    }

    public function create_alamat(Request $request)
    {
        if($request->ajax())
        {
            $jum_alamat = count(Auth::user()->alamat);
            if ($jum_alamat >= 3)
            {
                return response()->json(['status' => 'info', 'title' => 'Perhatian!', 'message' => 'Jumlah alamat yang tersimpan sudah memenuhi batas']);
            } else {
                $modal = view('components.create-alamat')->render();
                return response()->json(['status' => 'allowed', 'modal' => $modal]);
            }     
            // return response()->json(['status' => 'success', 'title' => 'Berhasil', 'message' => 'Berhasil menambahkan alamat']);
        }
    }

    public function edit_alamat(Request $request)
    {
        if($request->ajax())
        {
            $id = decrypt($request->id);
            $alamat = Alamat::find($id);
            if ($alamat)
            {
                $modal = view('components.edit-alamat', compact('alamat'))->render();

                return response()->json([
                    'status' =>'success',
                    'modal' => $modal
                ]);
            } else {
                return response()->json(['status' => 'error', 'title' => 'Gagal', 'message' => 'Gagal meenemukan data alamat']);
            }
        }
    }

    public function store_alamat(Request $request)
    {
        // dd($request->all());
        $this->validate($request,[
            'provinsi' => 'required',
            'kota' => 'required',
            'kecamatan' => 'required',
            'kode_pos' => 'required',
            'alamat' => 'required',
        ]);

        if ($request->has('id'))
        {
            $id = decrypt($request->id);
            $alamat = Alamat::find($id);
        }
        
        Alamat::updateOrCreate([
            'id' => ($request->has('id')) ? $alamat->id : null,
        ],
        [
            'user_id' => Auth::user()->id,
            'provinsi_id' => $request->provinsi,
            'kota_id' => $request->kota,
            'kecamatan_id' => $request->kecamatan,
            'kelurahan' => $request->kelurahan,
            'kode_pos' => $request->kode_pos,
            'detail' => $request->alamat,
        ]);

        $this->SwalNotif('success', 'Berhasil', 'Data alamat berhasil diperbarui');
        return redirect()->route('user.settings')->with('success', 'Alamat telah diperbarui');
    }

    public function delete_alamat(Request $request)
    {
        if ($request->ajax())
        {
            $id = decrypt($request->id);
            $alamat = Alamat::find($id);
            $alamat->delete();
            $this->SwalNotif('success', 'Berhasil', 'Data alamat berhasil dihapus');
            return response()->json(['status' => 'success']);
        }
    }
}
