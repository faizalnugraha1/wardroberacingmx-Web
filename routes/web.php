<?php

use App\Http\Controllers\Admin\BarangController;
use App\Http\Controllers\Admin\Booking;
use App\Http\Controllers\Admin\BookingController;
use App\Http\Controllers\Admin\BrandController;
use App\Http\Controllers\Admin\KategoriController;
use App\Http\Controllers\Admin\MainController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Auth\CustomController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\ShoppingController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Symfony\Component\HttpKernel\Profiler\Profile;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
Auth::routes();

Route::get('/home', function () {
    return redirect()->route('home');
});


Route::get('/', [App\Http\Controllers\MainController::class, 'index'])->name('home');
Route::get('/explore', [App\Http\Controllers\MainController::class, 'shop'])->name('shop');
Route::get('/search', [App\Http\Controllers\MainController::class, 'shop_query'])->name('shop_query');
Route::get('/product', [App\Http\Controllers\MainController::class, 'product'])->name('product');
Route::get('/product/{slug}', [App\Http\Controllers\MainController::class, 'product_detail'])->name('product_detail');
Route::get('/booking', [App\Http\Controllers\MainController::class, 'booking'])->name('booking');
Route::post('/booking/create', [App\Http\Controllers\MainController::class, 'booking_create'])->name('booking_create');
Route::get('/brand', [App\Http\Controllers\MainController::class, 'brand'])->name('brand');
Route::get('/brand/{slug}', [App\Http\Controllers\MainController::class, 'brand_detail'])->name('brand_detail');
Route::get('/kategori', [App\Http\Controllers\MainController::class, 'kategori'])->name('kategori');
Route::get('/kategori/{slug}', [App\Http\Controllers\MainController::class, 'kategori_detail'])->name('kategori_detail');

Route::post('/resend-verification', [CustomController::class, 'resend_verify'])->name('verification_resend');
Route::get('/verification/{verification_token}', [CustomController::class, 'verify'])->name('verification');
Route::post('/forget-password', [CustomController::class, 'request_reset'])->name('request_reset');
Route::get('/forget-password/{token}', [CustomController::class, 'reset_form'])->name('reset_form');
Route::post('/reset-password', [CustomController::class, 'reset_pass'])->name('reset_pass');

// ajax route
Route::get('/fetch-comment/{slug}', [App\Http\Controllers\MainController::class, 'fetch_comment'])->name('fetch_comment');
Route::get('/add-to-favorite', [App\Http\Controllers\MainController::class, 'add_to_favorite'])->name('add_favorite');
Route::get('/add-to-cart', [App\Http\Controllers\MainController::class, 'add_to_cart'])->name('add_cart');
Route::get('/add-to-cart2', [App\Http\Controllers\MainController::class, 'add_to_cart2'])->name('add_cart2');

Route::get('/provinsi-list', [App\Http\Controllers\MainController::class, 'prov_list'])->name('prov.list');
Route::get('/kota-list', [App\Http\Controllers\MainController::class, 'kota_list'])->name('kota.list');
Route::get('/kecamatan-list', [App\Http\Controllers\MainController::class, 'kecamatan_list'])->name('kecamatan.list');

Route::middleware('auth', 'role:user')->group(function () {    
    Route::get('/favourite', [ShoppingController::class, 'fav'])->name('fav');

    Route::get('/cart', [ShoppingController::class, 'cart'])->name('cart');
    Route::get('/cart/delete', [CartController::class, 'delete'])->name('cart.delete');
    Route::get('/cart/update', [CartController::class, 'update'])->name('cart.update');

    Route::get('/checkout', [ShoppingController::class, 'checkout'])->name('checkout');
    Route::post('/checkout/pay', [ShoppingController::class, 'pay'])->name('pay');
    Route::get('/checkout/pay/complete', [ShoppingController::class, 'pay_complete'])->name('pay.complete');
    Route::get('/checkout/pay/cancel/{kode_invoice}', [ShoppingController::class, 'pay_cancel'])->name('pay.cancel');
    
    Route::name('user.')->group(function() {
        Route::get('/profile', [UserController::class, 'profile'])->name('settings');
        Route::get('/history/order', [UserController::class, 'pembelian'])->name('order.history');
        Route::get('/detail/order/{id}', [UserController::class, 'pembelian_show'])->name('order.show');
        Route::put('/finish/order/{id}', [UserController::class, 'pembelian_finish'])->name('order.finish');
        Route::get('/history/booking', [UserController::class, 'booking'])->name('booking.history');
        Route::get('/detail/booking/{id}', [UserController::class, 'booking_show'])->name('booking.show');
        Route::get('/reviews', [UserController::class, 'review'])->name('review');
        Route::get('/reviews/create/{id}', [UserController::class, 'create_review'])->name('review.create');
        Route::get('/reviews/view/{id}', [UserController::class, 'view_review'])->name('review.view');
        Route::post('/reviews/store', [UserController::class, 'store_review'])->name('review.store');
        Route::get('/create-alamat', [UserController::class, 'create_alamat'])->name('create.alamat');
        Route::get('/edit-alamat', [UserController::class, 'edit_alamat'])->name('edit.alamat');
        Route::post('/store-alamat', [UserController::class, 'store_alamat'])->name('store.alamat');
        Route::delete('/delete-alamat', [UserController::class, 'delete_alamat'])->name('delete.alamat');
        
    });
    Route::prefix('settings')->name('setting.')->group(function() {
        Route::put('/general', [UserController::class, 'update_general'])->name('general');
        Route::put('/password', [UserController::class, 'update_password'])->name('password');
    });
    Route::get('/validatepass', [App\Http\Controllers\MainController::class, 'validatepass'])->name('validatepass');
    Route::get('/update-ongkir', [App\Http\Controllers\ShoppingController::class, 'update_ongkir'])->name('update_ongkir');
});

Route::middleware('auth','role:admin')->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [MainController::class, 'index'])->name('dashboard');

    Route::prefix('barang')->name('barang.')->group(function () {
        
        Route::prefix('kategori')->name('kategori.')->group(function () {
            Route::get('/', [KategoriController::class, 'index'])->name('index');
            Route::post('/store', [KategoriController::class, 'store'])->name('store');
            Route::get('/edit', [KategoriController::class, 'edit'])->name('edit');
            Route::put('/{slug}/edit', [KategoriController::class, 'update'])->name('update');
            Route::delete('/{slug}/delete', [KategoriController::class, 'delete'])->name('delete');
        });
        Route::prefix('brand')->name('brand.')->group(function () {
            Route::get('/', [BrandController::class, 'index'])->name('index');
            Route::post('/store', [BrandController::class, 'store'])->name('store');
            Route::get('/edit', [BrandController::class, 'edit'])->name('edit');
            Route::put('/{slug}/edit', [BrandController::class, 'update'])->name('update');
            Route::delete('/{slug}/delete', [BrandController::class, 'delete'])->name('delete');
        });

        Route::get('/', [BarangController::class, 'index'])->name('index');
        Route::post('/store', [BarangController::class, 'store'])->name('store');
        Route::get('/edit', [BarangController::class, 'edit'])->name('edit');
        Route::put('/{slug}/edit', [BarangController::class, 'update'])->name('update');
        Route::delete('/{slug}/delete', [BarangController::class, 'delete'])->name('delete');
        Route::get('/print', [BarangController::class, 'print'])->name('print');
        
        Route::get('/{id}/databarang', [BarangController::class, 'databarang'])->name('databarang');
    });
    
    Route::prefix('order')->name('order.')->group(function () {
        Route::get('/', [OrderController::class, 'index'])->name('index');
        Route::get('/refresh', [OrderController::class, 'refresh'])->name('refresh');
        Route::get('/{invoice_id}/detail', [OrderController::class, 'detail'])->name('detail');
        Route::put('/{invoice_id}/cancel', [OrderController::class, 'cancel'])->name('cancel');
        Route::put('/{invoice_id}/confirm', [OrderController::class, 'confirm'])->name('confirm');
        Route::get('/{invoice_id}/lanjutkan', [OrderController::class, 'lanjutkan'])->name('lanjutkan');
        Route::put('/{invoice_id}/to_kurir', [OrderController::class, 'to_kurir'])->name('to_kurir');
        Route::put('/{invoice_id}/update', [OrderController::class, 'update'])->name('update');
        // Route::get('/{invoice_id}/complete', [OrderController::class, 'complete'])->name('complete');
    });
    
    Route::prefix('booking')->name('booking.')->group(function () {
        Route::get('/', [BookingController::class, 'index'])->name('index');
        Route::get('/calendar', [BookingController::class, 'list'])->name('list');
        Route::get('/additionbarang', [BookingController::class, 'addbarang'])->name('addbarang');
        Route::get('/{booking_id}/detail', [BookingController::class, 'detail'])->name('detail');
        Route::put('/{booking_id}/approve', [BookingController::class, 'approve'])->name('approve');
        Route::put('/{booking_id}/kerjakan', [BookingController::class, 'kerjakan'])->name('kerjakan');
        Route::get('/{booking_id}/checkout', [BookingController::class, 'checkout'])->name('checkout');
        Route::post('/{booking_id}/checkout/store', [BookingController::class, 'store'])->name('store');
        Route::put('/{booking_id}/batal', [BookingController::class, 'batal'])->name('batal');
        Route::get('/print', [BookingController::class, 'print'])->name('print');
        // Route::get('/{id}/delete', [BookingController::class, 'delete'])->name('delete');
    });
});

Route::get('/test', [App\Http\Controllers\MainController::class, 'test'])->name('test');