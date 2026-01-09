<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function index()
    {
        $users = User::all();
        confirmDelete('Hapus Data', 'Apakah anda yakin ingin menghapus data ini?');
        return view('users.index', compact('users'));
    }

    public function store(Request $request)
    {
        $id = $request->id;
        // dd($request->all());
        Request()->validate([
            'name' => 'required|string',
            'email' => [
                'required',
                'string',
                'email',
                Rule::unique('users', 'email')->ignore($id),
            ],
        ], [
            'name.required' => 'Nama user harus diisi',
            'email.required' => 'Email harus diisi',
            'email.email' => 'Email tidak valid',
            'email.unique' => 'Email ini sudah digunakan',
        ]);

        $newRequest = $request->all();

        if (!$id) {
            $newRequest['password'] = Hash::make('12345678');
        }

        User::updateOrCreate(['id' => $id], $newRequest);

        toast()->success('User berhasil disimpan');
        return redirect()->route('users.index');
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();
        toast()->success('User berhasil dihapus.');
        return redirect()->route('users.index');
    }

    public function gantiPassword(Request $request)
    {
        $request->validate([
            'old_password' => 'required',
            'new_password' => 'required|min:8|confirmed',
            // 'password' => [Password::min(8)->mixedCase()->letters()->numbers()->symbols(), 'confirmed']
        ], [
            'old_password.required' => 'Password lama harus diisi.',
            'new_password.required' => 'Password baru harus diisi.',
            'new_password.min' => 'Password baru minimal 8 karakter.',
            'new_password.confirmed' => 'Konfirmasi password tidak sesuai.'
        ]);

        $user = User::find(Auth::id());

        if (!Hash::check($request->old_password, $user->password)) {
            return back()
            -> withErrors(['old_password' => 'Password lama tidak sesuai.'])
            -> with('show_ganti_password', true);
        }

        $user->update([
            'password' => Hash::make($request->new_password)
        ]);

        toast()->success('Password berhasil diubah.');
        return redirect()->route('dashboard');

    }
}
