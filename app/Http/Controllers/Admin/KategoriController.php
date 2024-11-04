<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\SharedFunction;
use App\Models\Kategori;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;

class KategoriController extends Controller
{
    use SharedFunction;
    public function index()
    {
        $data = Kategori::all();
        // return view('admin.kategori', compact('data'))->with('success', 'Data Kategori Berhasil Ditambahkan');
        return view('admin.kategori', compact('data'));
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'nama' => 'required|unique:kategori,nama',
            'thumbnail' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $kategori = Kategori::create([
            'nama' => $request->nama,
            'slug' => Str::slug($request->nama),
        ]);

        if ($request->file('thumbnail')) {
            $file = $request->file('thumbnail');
            $filename = 'kategori_'.$kategori->id.'_'.$this->generate_uniq(5).'.'.$file->getClientOriginalExtension();
            $file->move(public_path("images/"), $filename);
            $kategori->update([
                'thumbnail' => $filename,
            ]);
        }

        return redirect()->back()->with('toast_success', 'Data Kategori Berhasil Ditambahkan');
    }

    public function edit(Request $request)
    {
        if ($request->ajax()) {
            $kategori = Kategori::where('slug', $request->slug)->first();
            
            $modal = view('admin.modal.editkategori', compact('kategori'))->render();
            
            return response()->json([
                'modal' =>  $modal
            ]);           
        }
    }

    public function update(Request $request, $slug)
    {
        $kategori = Kategori::where('slug', $slug)->first();

        // dd($request->all(), $brand);
        $this->validate($request, [
            'nama' => 'required|unique:kategori,nama,'.$kategori->id,
            'thumbnail' => 'image|mimes:jpeg,png,jpg|max:2048',
        ]);

        if ($request->file('thumbnail')) {
            File::delete(public_path("images/".$kategori->thumbnail));
            $file = $request->file('thumbnail');
            $filename = 'kategori_'.$kategori->id.'_'.$this->generate_uniq(5).'.'.$file->getClientOriginalExtension();
            $file->move(public_path("images/"), $filename);
            $kategori->update(['thumbnail'=>$filename]);
        }

        $kategori->update([
            'nama' => $request->nama,
            'slug' => Str::slug($request->nama),
        ]);


        return redirect()->back()->with('toast_success', 'Data Kategori Berhasil Diubah');
    }

    public function delete($slug)
    {
        $kategori = Kategori::where('slug', $slug)->first();
        $kategori->delete();
        return redirect()->back()->with('toast_success', 'Data Kategori Berhasil Dihapus');
    }
}
