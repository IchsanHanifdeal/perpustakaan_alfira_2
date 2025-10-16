<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Devrabiul\ToastMagic\Facades\ToastMagic;

class AuthController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function auth(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|string',
            'password' => 'required|string',
        ], [
            'email.required' => 'Email harus diisi.',
            'email.email' => 'Format email salah.',
            'password.required' => 'Password harus diisi.',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            $user = Auth::user();
            $userRole = $user->role;

            $loginTime = Carbon::now();
            $request->session()->put([
                'login_time' => $loginTime->toDateTimeString(),
                'nama' => $user->nama,
                'id_user' => $user->id,
                'email' => $user->email,
                'role' => $user->role,
                'created_at' => $user->created_at,
            ]);

            if ($userRole === 'admin' || $userRole === 'user') {
                ToastMagic::success('Login berhasil!');
                return redirect()->intended('dashboard')->with('toast', [
                    'message' => 'Login berhasil!',
                    'type' => 'success'
                ]);
            }

            ToastMagic::error('Login gagal, role pengguna tidak dikenali.');
            return back()->with('toast', [
                'message' => 'Login gagal, role pengguna tidak dikenali.',
                'type' => 'error'
            ])->withInput();
        }

        ToastMagic::error('Login gagal, Email atau Password Salah.');
        return back()->withErrors([
            'loginError' => 'Email atau password salah.',
        ])->with('toast', [
            'message' => 'Email atau password salah.',
            'type' => 'error'
        ])->withInput();
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        ToastMagic::success('Logout berhasil!');

        return redirect()->route('login')->with('toast', [
            'message' => 'Logout berhasil!',
            'type' => 'success'
        ]);;
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
