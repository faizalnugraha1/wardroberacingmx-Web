<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Mail\RegistrationMail;
use App\Models\Alamat;
use App\Providers\RouteServiceProvider;
use App\Models\User;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    // protected $redirectTo = RouteServiceProvider::HOME;
    protected function redirectTo()
    {
        // with('email_sent', 'Berhasil mendaftar.')
        Session::flash('email_sent', 'Berhasil mendaftar.');
        Session::flash('email', Auth::user()->email);
        Auth::logout();
        return ('/login');
    }
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'no_hp' => ['required', 'string'],
            'provinsi' => ['required'],
            'kota' => ['required'],
            'kecamatan' => ['required'],
            'kode_pos' => ['required'],
            'alamat' => ['required'],
            'agree' => ['required'],
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\Models\User
     */
    protected function create(array $data)
    {
        
        $token = substr(base_convert(sha1(uniqid(mt_rand())), 16, 36), 0, 50);
        // dd($data);
        
        $user = User::create([
            'name' => $data['name'],
            'no_hp' => str_replace(' ', '',$data['no_hp']),
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'verification_token' => $token,
            'is_active'=> 0,
        ])->assignRole('user');
            
        Alamat::create([
            'user_id' => $user->id,
            'provinsi_id' => $data['provinsi'],
            'kota_id' => $data['kota'],
            'kecamatan_id' => $data['kecamatan'],
            'kelurahan' => $data['kelurahan'],
            'kode_pos' => $data['kode_pos'],
            'detail' => $data['alamat'],
        ]);
        
        Mail::to($data['email'])->send(new RegistrationMail($user));
        return $user;
    }

    
}
