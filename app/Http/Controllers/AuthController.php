<?php

namespace App\Http\Controllers;

use App\Models\Pengguna;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    public function showLogin()
    {
        // Jika pengguna sudah terautentikasi, arahkan berdasarkan peran
        if (Session::has('pengguna_id')) {
            $user = Pengguna::find(Session::get('pengguna_id'));

            if ($user) {
                return $this->redirectBasedOnRole($user->role);
            }
        }

        return view('auth.login');
    }

    public function login(Request $request)
    {
        $customMessages = [
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'password.required' => 'Password wajib diisi.',
        ];

        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ], $customMessages);

        $user = Pengguna::where('email', $request->email)->first();

        if (!$user) {
            return redirect()->back()
                ->withInput($request->only('email', 'password'))
                ->with('error', 'Akun anda tidak ditemukan');
        }

        $checkPassword = Hash::check($request->password, $user->password);

        if ($checkPassword) {
        } else {
            return redirect()->back()
                ->with('error', 'Password anda salah');
        }

        // dd('HAHAHAHA');

        // Set session untuk pengguna dan role
        Session::put('pengguna_id', $user->pengguna_id);
        Session::put('role', $user->role);

        return $this->redirectBasedOnRole($user->role);
    }

    public function logout()
    {
        // Hapus sesi yang dimasukkan saat login saja
        Session::forget('pengguna_id');
        Session::forget('role');
        return redirect()->route('login')->with('success', 'Anda berhasil logout');
    }

    public function showForgotPassword()
    {
        return view('auth.forgot-password');
    }

    public function sendResetLink(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        $user = Pengguna::where('email', $request->email)->first();

        if (!$user) {
            return back()->with('error', 'Email tidak ditemukan');
        }

        $token = Str::random(60);
        $user->reset_token = $token;
        $user->save();

        $resetLink = route('password.reset', $token);

        // Kirim email dengan tautan reset password
        Mail::send('emails.reset-password', ['resetLink' => $resetLink], function ($message) use ($request) {
            $message->to($request->email);
            $message->subject('Reset Password');
        });

        return back()->with('success', 'Tautan reset password telah dikirim ke email Anda');
    }

    public function showResetForm($token)
    {
        return view('auth.reset-password', ['token' => $token]);
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|confirmed',
            'token' => 'required'
        ]);

        $user = Pengguna::where('email', $request->email)
            ->where('reset_token', $request->token)
            ->first();

        if (!$user) {
            return back()->with('error', 'Token reset password tidak valid');
        }

        $user->password = Hash::make($request->password);
        $user->reset_token = null;
        $user->save();

        return redirect()->route('login')->with('success', 'Password berhasil diubah');
    }

    // Arahkan berdasarkan role pengguna
    protected function redirectBasedOnRole($role)
    {
        if ($role == 'manajer_produksi') {
            return redirect()->route('manajer_produksi.bahan_baku.index')->with('success', 'Selamat datang di menu Manajer Produksi');
        } elseif ($role == 'supervisor') {
            return redirect()->route('supervisor.transaksi.index')->with('success', 'Selamat datang di menu Supervisor');
        } elseif ($role == 'admin') {
            return redirect()->route('admin.dashboard')->with('success', 'Selamat datang di menu Admin');
        } else {
            return redirect()->back()->withErrors(['error' => 'Role tidak dikenali']);
        }
    }
}
