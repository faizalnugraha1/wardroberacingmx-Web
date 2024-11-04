<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Controllers\SharedFunction;
use App\Mail\RegistrationMail;
use App\Mail\ResetPasswordMail;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class CustomController extends Controller
{
    use SharedFunction;
    public function verify($verification_token)
    {
        $user = User::where('verification_token', $verification_token)->firstOrFail();

        if ($user) {
            if ($user->is_active) {
                abort(404);
            } 
            $user->update([
                'is_active' => true,
                'verification_token' => null
            ]);
            Auth::login($user);

            $this->SwalNotif('success', 'Verifikasi Berhasil', 'Selamat, akun anda telah diverifikasi.');
            return redirect()->route('home');
        }
    }

    public function resend_verify(Request $request)
    {
        $user = User::where('email', $request->email)->firstOrFail();

        $token = $this->generate_uniq(50);
        $user->update([
            'verification_token' => $token
        ]);
        Mail::to($user->email)->send(new RegistrationMail($user));

        return redirect()->route('login')->with('email',$user->email)->with('email_sent', 'Email verifikasi telah dikirim.');
        
    }

    public function request_reset(Request $request)
    {
        $user = User::where('email', $request->email)->first();
        if ($user) {
            $token = $this->generate_uniq(50);
            $user->update([
                'remember_token' => $token,
            ]);

            Mail::to($user->email)->send(new ResetPasswordMail($token));

            return redirect()->back()->with('email',$user->email)->with('email_sent', 'Permintaan reset Password telah dikirim.');
        } else {
            return redirect()->back()->withErrors(['msg' => 'Email tidak ditemukan.']);
        }
        
    }

    public function reset_form($token)
    {
        $user = User::where('remember_token', $token)->firstOrFail();
        return view('auth.passwords.reset', compact('user'))->with('token', $token);
    }

    public function reset_pass(Request $request)
    {
        // dd($request->all());
        $user = User::where('email', $request->email)->firstOrFail();

        $this->validate($request, [
            'password' => 'required|min:8|confirmed',
            'reset_token' => 'required'
        ]);

        if($user->remember_token)
        {
            if($user->remember_token == $request->reset_token) {
                $user->update([
                    'password' => bcrypt($request->password),
                    'remember_token' => null,
                ]);
                return redirect()->route('login')->with('success', 'Password berhasil diubah.');
            } else {
                abort(404);
            }
        } else
        {
            abort(404);
        }
    }

    public function test()
    {
        return view('auth.test');
    }
}
