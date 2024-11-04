<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    public function update(Request $request)
    {
        $id = decrypt($request->id);
        $cart = Cart::find($id);
        if ($cart) {
            if ($request->qty <= $cart->barang->stok) {
                $cart->update([
                    'qty' => $request->qty,
                ]);

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

                $view = view('components.cart-container', compact('data', 'kosong', 'subtotalbe'))->render();

                return response()->json(['status' => 'success', 'title' => 'Berhasil', 'message' => 'Barang pada Cart berhasil diupdate', 'view' => $view]);
            } else {
                $qty = $cart->qty;
                $max = $cart->barang->stok;
                return response()->json(['status' => 'error', 'title' => 'Gagal', 'message' => 'Stok barang tidak mencukupi', 'qty' => $qty, 'max' => $max]);
            }

        }
    }

    public function delete(Request $request)    
    {   
        $id = decrypt($request->id);
        $cart = Cart::find($id);
        if ($cart) {
            $cart->delete();

            $data = Cart::where('user_id', Auth::user()->id)->whereHas('barang', function($query) {
                $query->where('stok', '>', 0);
            })->with(['barang'])->get();
            
            $subtotalbe = 0;
            foreach ($data as $d) {
                $subtotalbe += $d->barang->harga_jual * $d->qty;
            }
            $subtotalbe = number_format($subtotalbe);

            $cart_count = count(Auth::user()->cart);

            $count_kosong = $cart_count - count($data);

            if ($count_kosong == 0){
                return response()->json(['status' => 'warning', 'title' => 'Berhasil', 'message' => 'Cart kosong' ]);
            } else {

                return response()->json(['status' => 'success', 'title' => 'Berhasil', 'message' => 'Barang telah dihapus dari Cart' ,'cart_count' => $cart_count, 'subtotal' => $subtotalbe, 'jumlah_kosong' => $count_kosong]);
            }

        } else {
            return response()->json(['status' => 'error', 'title' => 'Gagal', 'message' => 'Terjadi kesalahan']);
        }
    }
}
