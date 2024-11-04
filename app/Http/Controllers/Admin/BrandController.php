<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\SharedFunction;
use App\Models\Brand;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class BrandController extends Controller
{
    
    use SharedFunction;
    public function index()
    {
        $data = Brand::all();
        return view('admin.brand', compact('data'));
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'nama' => 'required|unique:brand,nama',
            'logo' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        if ($request->file('logo')) {
            $file = $request->file('logo');
            $filename = 'brand_'.Str::slug($request->nama).'_'.$this->generate_uniq(5).'.'.$file->getClientOriginalExtension();
            $file->move(public_path("images/"), $filename);

        }

        Brand::create([
            'nama' => $request->nama,
            'slug' => Str::slug($request->nama),
            'logo' => $filename,
        ]);


        return redirect()->back()->with('toast_success', 'Data Brand/Merek Berhasil Ditambahkan');
    }

    public function edit(Request $request)
    {
        if ($request->ajax()) {
            $brand = Brand::where('slug', $request->slug)->first();
            
            $modal = view('admin.modal.editbrand', compact('brand'))->render();
            
            return response()->json([
                'modal' =>  $modal
            ]);           
        }
    }

    public function update(Request $request, $slug)
    {
        $brand = Brand::where('slug', $slug)->first();

        // dd($request->all(), $brand);
        $this->validate($request, [
            'nama' => 'required|unique:brand,nama,'.$brand->id,
            'logo' => 'image|mimes:jpeg,png,jpg|max:2048',
        ]);

        if ($request->file('logo')) {
            File::delete(public_path("images/".$brand->logo));
            $file = $request->file('logo');
            $filename = 'brand_'.Str::slug($request->nama).'_'.$this->generate_uniq(5).'.'.$file->getClientOriginalExtension();
            $file->move(public_path("images/"), $filename);
            $brand->update(['logo'=>$filename]);
        }

        $brand->update([
            'nama' => $request->nama,
            'slug' => Str::slug($request->nama),
        ]);


        return redirect()->back()->with('toast_success', 'Data Brand/Merek Berhasil Diubah');
    }

    public function delete($slug)
    {
        $kategori = Brand::where('slug', $slug)->first();
        $kategori->delete();
        return redirect()->back()->with('toast_success', 'Data Kategori Berhasil Dihapus');
    }
}
