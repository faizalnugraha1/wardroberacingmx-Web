<?php

namespace App\Http\Controllers;

use App\Mail\RegistrationMail;
use App\Models\Barang;
use App\Models\Booking;
use App\Models\BookingLog;
use App\Models\Brand;
use App\Models\Cart;
use App\Models\Favorite;
use App\Models\Invoice;
use App\Models\InvoiceDetail;
use App\Models\Kategori;
use App\Models\Kecamatan;
use App\Models\Kota;
use App\Models\Provinsi;
use App\Models\User;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;
use Kavist\RajaOngkir\Facades\RajaOngkir;
use App\Http\Controllers\Trait\MetaTrait;

class MainController extends Controller
{
    use SharedFunction;
    public function index()
    {
        $barang = Barang::where('stok', '>', 0)->inRandomOrder($this->generate_random_order())->limit(4)->get();
        $kategori = Kategori::all();
        $brand = Brand::all();
        $terlaris = InvoiceDetail::where('barang_id', '!=', null)
                ->whereHas('barang', function($query) {
                    $query->where('deleted_at', null);
                })
                ->selectRaw("barang_id")
                ->selectRaw("SUM(qty) as total_terjual")
                ->selectRaw("SUM(subtotal) as total_pemasukan")
                ->groupBy('barang_id')
                ->orderBy('total_terjual', 'desc')
                ->with('barang')
                ->limit(4)
                ->get();

        MetaTrait::set([
            // 'title' => 'Tittle hahaha',
            'keywords' => 'bengkel,motor,trail,bandung,modif,trabas,sparepart,after market',
            'description' => 'Wardrobe Racing MX berdiri sejak tahun 2015. Sejak awal berdiri kami berfokus pada bidang automotive motor khususnya Motor Trail, mulah dari berbagai sparepart dan produk aftermarket motor trail dan juga berbagai macam apparel untuk kebutuhan Trail.',
            'image' => asset('logo_hd.png'),
            'url' => url('home'),
            'twitter_card' => 'summary',
        ]);

        return view('home', compact('barang', 'kategori', 'brand', 'terlaris'));
    }

    public function shop(Request $request)
    {
        $kategori = Kategori::all();
        $brand = Brand::all();
        $single_brand = null;

        if($request->has('show'))
        {
            $page = $request->show;
        } else {
            $page = 12;
        }
        $barang = Barang::inRandomOrder($this->generate_random_order())->paginate($page);

        MetaTrait::set([
            'title' => 'Shop',
            'image' => asset('logo_hd.png'),
            'url' => url('shop'),
            'twitter_card' => 'summary',
        ]);

        return view('shop', compact('barang', 'kategori', 'brand', 'single_brand'));
    }

    public function shop_query(Request $request)
    {
        if(count($request->all()) == 0)
        {
            return redirect()->route('shop');
        }
        
        $kategori = Kategori::all();
        $brand = Brand::all();    
        $single_brand = null; 
        
        $barang = new Barang;

        if($request->has('q'))
        {
            if($request->q == '')
            {
                return redirect()->route('shop');
            } else {
                $barang = $barang->where('nama', 'like', '%'.$request->q.'%')->orWhere('keywords', 'like', '%'.$request->q.'%');
            }
        }

        if($request->has('show'))
        {
            $page = $request->show;
        } else {
            $page = 12;
        }

        if($request->has('kategori'))
        {
            $barang = $barang->whereIn('kategori_id', $this->kategori_to_id($request->kategori));
        }
        
        if($request->has('brand'))
        {
            $barang = $barang->whereIn('brand_id', $this->brand_to_id($request->brand));  
            if(count($request->brand) == 1)
            {
                $single_brand = Brand::where('slug',$request->brand[0])->first();
            }         
        }

        if($request->has('sort'))
        {
            if($request->sort == 'terbaru')
            {
                $barang = $barang->orderBy('created_at', 'asc');
            } else if($request->sort == 'termurah') {
                $barang = $barang->orderBy('harga_jual', 'asc');
            } else if($request->sort == 'termahal') {
                $barang = $barang->orderBy('harga_jual', 'desc');
            }else if($request->sort == 'terbaik') {
                $barang = $barang->orderBy('rating', 'desc');
            }
        }        

        $barang = $barang->paginate($page)->appends([
            'q' => $request->q,
            'kategori' => $request->kategori,
            'brand' => $request->brand,
            'sort' => $request->sort,
            'show' => $request->show,
        ]);

        MetaTrait::set([
            'title' => 'Search - Shop',
            'image' => asset('logo_hd.png'),
            'url' => url('shop'),
            'twitter_card' => 'summary',
        ]);

        return view('shop', compact('barang', 'kategori', 'brand', 'single_brand'));
    }

    public function product_detail($slug)
    {
        $barang = Barang::where('slug', $slug)->firstOrFail();        
        
        $myreview = null;
        if(Auth::check())
        {
            $myreview = InvoiceDetail::where('barang_id', $barang->id)
                ->where('rating', '!=', null)
                ->whereHas('invoice', function($query) {
                    $query->where('user_id', Auth::user()->id);
                }) 
                ->latest()
                ->get();
        }
        // dd($myreview);
        MetaTrait::set([
            'title' => $barang->nama,
            'image' => asset('images/'.$barang->thumbnail),
            'url' => url('product', ['slug' => $barang->slug]),
            'description' => substr(strip_tags($barang->deskripsi),0,200) . " ...",
            'keywords' => $barang->keywords,
            'twitter_card' => 'summary',
        ]);

        return view('produk', compact('barang', 'myreview'));
    }

    public function booking()
    {
        MetaTrait::set([
            'title' => 'Booking',
            'image' => asset('logo_hd.png'),
            'url' => url('booking'),
            'twitter_card' => 'summary',
        ]);

        return view('booking');
    }

    public function booking_create(Request $request)
    {
        if(Auth::check())
        {
            $this->validate($request, [
                'tanggal' => 'required|date',
                'jam' => 'required|date_format:H:i',
                'keperluan' => 'required',
                'model_motor' => 'required',
            ]);
            $id = 'BK'. $this->generate_uniq(7);
            // dd($request->all(), implode(', ', $request->keperluan));
            
            $created = Booking::create([
                'booking_id' => strtoupper($id),
                'flag_id' => 1,
                'user_id' => Auth::user()->id,
                'tanggal_booking' => $request->tanggal,
                'jam_booking' => $request->jam,
                'model_motor' => $request->model_motor,
                'kebutuhan' => implode(', ', $request->keperluan),
                'keterangan' => $request->keterangan,
            ]);

            BookingLog::create([
                'booking_id' => $created->id,
                'flag_id' => 1,
            ]);

            $this->SwalNotif('success','Berhasil', 'Booking berhasil dibuat');
            return redirect()->route('user.booking.history')->with('success', 'Booking berhasil dibuat');
        } else {
            $this->SwalNotif('error', 'Gagal', 'Silahkan login terlebih dahulu');
            return back()->withErrors('error', 'Silahkan login terlebih dahulu');
        }
    }

    // ajax function
    public function fetch_comment($slug)
    {
        $barang = Barang::where('slug', $slug)->firstOrFail();   
        $review = InvoiceDetail::where('barang_id', $barang->id)
                ->where('rating', '!=', null)
                ->latest()
                ->paginate(5);
        $html =  view('components.product-comment', compact('review'))->render();

        return response()->json(['html' => $html]);
    }

    public function add_to_favorite(Request $request)
    {
        if($request->ajax()) {
            if (Auth::check()) {
                $barang = Barang::where('slug', $request->slug)->first();
                if ($barang) {
                    $favorite = Favorite::where('user_id', Auth::user()->id)->where('barang_id', $barang->id)->first();
                    if ($favorite) {
                        $favorite->delete();
                        $fav_count = count(Auth::user()->favorite);
                        return response()->json(['status' => 'success', 'title' => 'Berhasil', 'message' => 'Produk dihapus dari favorit', 'fav_count' => $fav_count]);
                    } else {
                        Favorite::create([
                            'user_id' => Auth::user()->id,
                            'barang_id' => $barang->id,
                        ]);
                        $fav_count = count(Auth::user()->favorite);
                        return response()->json(['status' => 'success', 'title' => 'Berhasil', 'message' => 'Produk ditambahkan ke favorit', 'fav_count' => $fav_count]);
                    }
                } else {
                    return response()->json(['status' => 'error', 'title' => 'Gagal', 'message' => 'Produk tidak ditemukan']);
                }
            } else {
                return response()->json(['status' => 'error', 'title' => 'Gagal', 'message' => 'Anda harus login terlebih dahulu']);
            }
        }
    }
    
    public function add_to_cart(Request $request)
    {
        if($request->ajax()) {
            if (Auth::check()) {
                $barang = Barang::where('slug', $request->slug)->first();
                if ($barang) {
                    if($barang->stok > 0) {
                        $cart = Cart::where('user_id', Auth::user()->id)->where('barang_id', $barang->id)->first();
                        if ($cart) {
                            return response()->json(['status' => 'info', 'title' => 'Perhatian', 'message' => 'Produk sudah ada di dalam keranjang']);
                        } else {
                            Cart::create([
                                'user_id' => Auth::user()->id,
                                'barang_id' => $barang->id,
                            ]);
                            $cart_count = count(Auth::user()->cart);
                            return response()->json(['status' => 'success', 'title' => 'Berhasil', 'message' => 'Produk ditambahkan ke keranjang', 'cart_count' => $cart_count]);
                        }
                    } else {                        
                        return response()->json(['status' => 'info', 'title' => 'Gagal', 'message' => 'Stok produk tidak tersedia']);
                    }
                } else{
                    return response()->json(['status' => 'error', 'title' => 'Gagal', 'message' => 'Produk tidak ditemukan']);
                }
            } else {
                return response()->json(['status' => 'error', 'title' => 'Gagal', 'message' => 'Anda harus login terlebih dahulu']);
            }
        }
    }

    public function add_to_cart2(Request $request)
    {
        if($request->ajax()) {
            if (Auth::check()) {
                $barang = Barang::where('slug', $request->slug)->first();
                if ($barang) {
                    if($barang->stok > 0) {
                        $cart = Cart::where('user_id', Auth::user()->id)->where('barang_id', $barang->id)->first();
                        if ($cart) {
                            $cart->update([
                                'qty' => $request->qty,
                            ]);
                            return response()->json(['status' => 'success', 'title' => 'Berhasil', 'message' => 'Produk sudah dalam keranjang, berhasil mengupdate jumlah']);
                        } else {
                            Cart::create([
                                'user_id' => Auth::user()->id,
                                'barang_id' => $barang->id,
                                'qty' => $request->qty,
                            ]);
                            $cart_count = count(Auth::user()->cart);
                            return response()->json(['status' => 'success', 'title' => 'Berhasil', 'message' => 'Produk ditambahkan ke keranjang', 'cart_count' => $cart_count]);
                        }
                    } else {                        
                        return response()->json(['status' => 'info', 'title' => 'Gagal', 'message' => 'Stok produk tidak tersedia']);
                    }
                } else{
                    return response()->json(['status' => 'error', 'title' => 'Gagal', 'message' => 'Produk tidak ditemukan']);
                }
            } else {
                return response()->json(['status' => 'error', 'title' => 'Gagal', 'message' => 'Anda harus login terlebih dahulu']);
            }
        }
    }

    public function validatepass(Request $request)
    {
        if($request->ajax()) {
            if(Hash::check( $request->password, Auth::user()->password)){
                $status = 'Accepted';
            } else {
                $status = 'Refused';
            }
    
            return response()->json([
                'result' => $status,
            ]);            
        }
    }

    public function prov_list()
    {
        $data = Provinsi::all()->sortBy('nama');

        $response = array();
        foreach ($data as $d) {
            $response[] = array(
                "id" => $d->id,
                "text" => $d->nama,
            );
        }
        return response()->json($response);
    }

    public function kota_list(Request $request)
    {
        $data = Kota::where('provinsi_id', $request->provinsi_id)->orderBy('nama')->get();

        $response = array();
        foreach ($data as $d) {
            $response[] = array(
                "id" => $d->id,
                "text" => $d->nama,
            );
        }
        return response()->json($response);
    }

    public function kecamatan_list(Request $request)
    {
        $data = Kecamatan::where('kota_id', $request->kota_id)->orderBy('nama')->get();

        $response = array();
        foreach ($data as $d) {
            $response[] = array(
                "id" => $d->id,
                "text" => $d->nama,
            );
        }
        return response()->json($response);
    }    

    public function test()
    {     

        // $data = Invoice::Order()->where('midtrans_status', null)->orWhere('midtrans_status', 'pending')->get();        
        // foreach ($data as $d) {
        //     $this->midtrans_check($d);
        // }
        // dd($data);
        // try {
        //     $this->init_midtrans();
        //     $result = \Midtrans\Transaction::status('INV233ICJONZG');
        //     $check = true;
        // } catch (Exception $e) {           
        //     $check = false;
        // }
        
        // dd(substr(preg_replace('/\D/', '', '1-4 Hari'), -1));
        // dd(abs(1000000 - 1500000));
        $data = Booking::where('booking_id', 'BKOV5A45E')->first();
        $invoice = Invoice::where('booking_id', $data->id)->with(['detail'])->first();

        // $modal = view('admin.modal.booking-detail', compact('data', 'invoice'))->render();

        return view('mail.booking_invoice', compact('data', 'invoice'));
    }
}
