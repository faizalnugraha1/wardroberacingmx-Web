<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\BarangStoreRequest;
use App\Http\Controllers\Controller;
use App\Http\Controllers\SharedFunction;
use App\Models\Barang;
use App\Models\Brand;
use App\Models\FotoBarang;
use App\Models\Invoice;
use App\Models\InvoiceDetail;
use App\Models\Kategori;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use PDF;

class BarangController extends Controller
{
    use SharedFunction;
    public function index()
    {
        $tanggal = InvoiceDetail::where('barang_id', '!=', null)
                    ->whereHas('invoice', function($query) {
                        $query->whereIn('flag_id', [5,14,15]);
                    })    
                    ->selectRaw('year(created_at) year, monthname(created_at) month')
                    ->groupBy('year', 'month')
                    ->orderBy('year', 'desc')
                    ->get();
        
        // dd(Carbon::parse('2022-July')->year);

        $data = Barang::inRandomOrder()->get();
        $kategori = Kategori::all()->sortBy('nama');
        $brand = Brand::all()->sortBy('nama');
        $habis = Barang::where('stok', '<=', '3')->get();
        $sipaling = InvoiceDetail::where('barang_id', '!=', null)
        ->whereHas('invoice', function($query) {
            $query->whereIn('flag_id', [5,14,15]);
        })  
        ->selectRaw("barang_id")
        ->selectRaw("SUM(qty) as total_terjual")
        ->groupBy('barang_id')
        ->orderBy('total_terjual', 'desc')->first();

        return view('admin.stokbarang', compact('data', 'kategori', 'brand', 'habis', 'sipaling', 'tanggal'));
    }

    public function store(BarangStoreRequest $request)
    {
        $this->validate($request, [
            'nama' => 'required|unique:kategori,nama',
            'kategori_id' => 'required',
            'thumbnail' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            'harga_jual' => 'required',
            'stok' => 'required|numeric',
            'berat' => 'required|numeric',
            'deskripsi' => 'required',
        ]);

        // dd($request->all());

        
        $stored = Barang::create([
            'nama' => $request->nama,
            'slug' => Str::slug($request->nama),
            'kategori_id' => $request->kategori_id,
            'brand_id' => $request->brand_id,
            'harga_jual' => $request->harga_jual,
            'harga_asal' => $request->harga_asal,
            'stok' => $request->stok,
            'berat' => $request->berat,
            'deskripsi' => $request->deskripsi,
            'keywords' => $request->keywords,
        ]);
        
        if ($request->file('thumbnail')) {
            $file = $request->file('thumbnail');
            $filename = 'thumb_'.$stored->id.'_'.$this->generate_uniq(5).'.'.$file->getClientOriginalExtension();
            $file->move(public_path("images/"), $filename);
            $stored->update(['thumbnail' => $filename]);
        }

        if ($request->file('images')) {
            for ($i=0; $i < count($request->images); $i++) { 
                $file = $request->file('images')[$i];
                $filename = 'images_'.$stored->id.'_'.$this->generate_uniq(5).'.'.$file->getClientOriginalExtension();
                $file->move(public_path("images/"), $filename);
                FotoBarang::create([
                    'barang_id' => $stored->id,
                    'file' => $filename,
                ]);                
            }         
        }

        return redirect()->back()->with('toast_success', 'Data Barang Berhasil Ditambahkan');
    }

    public function edit(Request $request)
    {
        // dd($request->all());
        if ($request->ajax()) {
            $barang = Barang::where('slug', $request->slug)->first();
            // dd($barang);
            $kategori = Kategori::all();
            $brand = Brand::all();
            
            $modal = view('admin.modal.editbarang', compact('barang', 'kategori', 'brand'))->render();
            
            return response()->json([
                'modal' =>  $modal
            ]);           
        }
    }

    public function update(BarangStoreRequest $request, $slug)
    {
        $barang = Barang::where('slug', $slug)->first();

        $this->validate($request, [
            'nama' => 'required|unique:brand,nama,'.$barang->id,
            'kategori_id' => 'required',
            'thumbnail' => 'image|mimes:jpeg,png,jpg|max:2048',
            'harga_jual' => 'required',
            'stok' => 'required|numeric',
            'berat' => 'required|numeric',
            'deskripsi' => 'required',
        ]);

        // dd($request->all(), $barang->images);
        
        $barang->update([
            'nama' => $request->nama,
            'slug' => Str::slug($request->nama),
            'kategori_id' => $request->kategori_id,
            'brand_id' => $request->brand_id,
            'harga_jual' => $request->harga_jual,
            'harga_asal' => $request->harga_asal,
            'stok' => $request->stok,
            'berat' => $request->berat,
            'deskripsi' => $request->deskripsi,
            'keywords' => $request->keywords,
        ]);
        
        if ($request->file('thumbnail')) {
            File::delete(public_path("images/".$barang->thumbnail));
            $file = $request->file('thumbnail');
            $filename = 'thumb_'.$barang->id.'_'.$this->generate_uniq(5).'.'.$file->getClientOriginalExtension();
            $file->move(public_path("images/"), $filename);
            $barang->update(['thumbnail' => $filename]);
        }

        foreach($barang->images as $image) {
            if (!in_array($image->id, $request->images_old)) {
                File::delete(public_path("images/".$image->file));
                $image->delete();
            }
        }

        if ($request->file('images')) {
            for ($i=0; $i < count($request->images); $i++) { 
                $file = $request->file('images')[$i];
                $filename = 'images_'.$barang->id.'_'.$this->generate_uniq(5).'.'.$file->getClientOriginalExtension();
                $file->move(public_path("images/"), $filename);
                FotoBarang::create([
                    'barang_id' => $barang->id,
                    'file' => $filename,
                ]);                
            }         
        }

        return redirect()->back()->with('toast_success', 'Data Barang Berhasil Diubah');
    }

    public function delete($slug)
    {
        $kategori = Barang::where('slug', $slug)->first();
        $kategori->delete();
        return redirect()->back()->with('toast_success', 'Data Barang Berhasil Dihapus');
    }

    public function print(Request $request)
    {
        $this->validate($request, [
            'tanggal' => 'required',
        ]);

        $tanggal = Carbon::parse($request->tanggal);
        $data = InvoiceDetail::where('barang_id', '!=', null)
                    ->whereYear('created_at', $tanggal->year)
                    ->whereMonth('created_at', $tanggal->month)
                    ->whereHas('invoice', function($query) {
                        $query->whereIn('flag_id', [5,14,15,16]);
                    })    
                    ->orderBy('barang_id')
                    ->with('invoice')
                    ->get()
                    ->groupBy('barang_id');

        $pdf = PDF::loadview('admin.print.laporan-penjualan', compact('data', 'tanggal'))->setPaper('A4', 'potrait');
        return $pdf->stream();

    }

    public function databarang($id)
    {
        $barang = Barang::findOrFail($id);
        
        return response()->json([
            'barang' => $barang
        ]);
    }
}
