<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        $user = ($request->user() ?: auth()->user())->load('tenant');

        return view('profile.edit', [
            'user' => $user,
            'tenant' => $user->tenant,
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(Request $request): RedirectResponse
    {
        $user = $request->user() ?: auth()->user();

        if ($user && $user->role === 'tenant_staff' && $user->tenant) {
            $tenant = $user->tenant;

            $validated = $request->validate([
                'name' => 'required|string|max:100',
                'phone' => 'nullable|string|max:20',
                'pic_name' => 'nullable|string|max:100',
                'pic_phone' => 'nullable|string|max:20',
                'pic_email' => 'nullable|email|max:150',
                'opening_time' => 'nullable',
                'closing_time' => 'nullable',
                'logo' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:20480',
            ], [
                'logo.max' => 'Ukuran foto maksimal adalah 20MB. Silakan pilih foto dengan ukuran lebih kecil.',
                'logo.image' => 'File yang diunggah harus berupa gambar (JPG, JPEG, PNG, atau WebP).',
                'logo.mimes' => 'Format file yang didukung hanya JPG, JPEG, PNG, atau WebP.',
            ]);

            // Update user name
            $user->name = $validated['name'];
            $user->save();

            // Update tenant details
            $tenant->phone = $validated['phone'] ?? null;
            $tenant->pic_name = $validated['pic_name'] ?? null;
            $tenant->pic_phone = $validated['pic_phone'] ?? null;
            $tenant->pic_email = $validated['pic_email'] ?? null;

            if (!empty($request->opening_time)) {
                $tenant->opening_time = date('H:i:s', strtotime($request->opening_time));
            }
            if (!empty($request->closing_time)) {
                $tenant->closing_time = date('H:i:s', strtotime($request->closing_time));
            }

            if ($request->hasFile('logo')) {
                $file = $request->file('logo');
                $filename = time() . '_' . \Illuminate\Support\Str::slug($tenant->name) . '.' . $file->getClientOriginalExtension();
                $destinationPath = public_path('images/logos');
                if (!file_exists($destinationPath)) {
                    mkdir($destinationPath, 0777, true);
                }
                $file->move($destinationPath, $filename);

                // Hapus logo lama jika ada dan bukan default
                if ($tenant->logo && file_exists(public_path($tenant->logo))) {
                    @unlink(public_path($tenant->logo));
                }

                $tenant->logo = 'images/logos/' . $filename;
            }

            $tenant->save();

            return Redirect::route('profile.edit')->with('status', 'profile-updated')->with('success', 'Profil dan foto gerai berhasil diperbarui!');
        }

        // Default profile update for non-tenant users (admin ops)
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $user->id,
        ]);

        $user->name = $validated['name'];
        $user->email = $validated['email'];

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        $user->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated')->with('success', 'Profil berhasil diperbarui!');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        if ($request->user()->role === 'tenant_staff') {
            return Redirect::route('profile.edit')->with('error', 'Akun Mitra Tenant terikat dengan gerai resmi bandara dan tidak dapat dihapus secara mandiri.');
        }

        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
