<?php

namespace App\Http\Controllers\Organization;
use App\Models\Organization;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\Controller;



class AuthController extends Controller
{
    public function showRegister()
    {
        return view('organization.auth.register');
    }

    public function showLogin()
    {
        return view('organization.auth.login');
    }
    public function register(Request $request)
{
    $request->validate([
        'name' => 'required',
        'pic_name' => 'required',
        'email' => 'required|email|unique:organizations,email',
        'password' => 'required|min:6',
        'phone' => 'required',
        'description' => 'nullable',
        'logo' => 'nullable|image|max:2048'
    ]);

    $logoPath = null;
    if ($request->hasFile('logo')) {
        $logoPath = $request->file('logo')->store('organizations', 'public');
    }

    Organization::create([
        'name' => $request->name,
        'pic_name' => $request->pic_name,
        'email' => $request->email,
        'password' => Hash::make($request->password),
        'phone' => $request->phone,
        'logo' => $logoPath,
        'description' => $request->description,
        'status' => 'active',
    ]);

    return redirect()->route('organization.login')
        ->with('success', 'Registrasi berhasil, silakan login.');
}
public function login(Request $request)
{
    $request->validate([
        'email' => 'required|email',
        'password' => 'required',
    ]);

    // 1. Coba cari di tabel organizations
    $organization = Organization::where('email', $request->email)->first();

    if ($organization && Hash::check($request->password, $organization->password)) {
        session([
            'organization_id' => $organization->id,
            'organization_name' => $organization->name,
        ]);

        return redirect()->route('organization.dashboard');
    }

    // 2. Jika tidak ada di tabel organizations, coba cari di tabel users (untuk Admin)
    $credentials = $request->only('email', 'password');
    if (\Illuminate\Support\Facades\Auth::attempt($credentials)) {
        $user = \Illuminate\Support\Facades\Auth::user();
        if ($user->role === 'admin') {
            $request->session()->regenerate();
            return redirect()->route('admin.dashboard');
        }
        // Jika bukan admin, logout-kan kembali
        \Illuminate\Support\Facades\Auth::logout();
    }

    return back()->with('error', 'Email atau Password salah.');
}

public function logout(Request $request)
{
    $request->session()->forget(['organization_id', 'organization_name']);
    return redirect()->route('login')->with('success', 'Anda telah berhasil logout.');
}
}