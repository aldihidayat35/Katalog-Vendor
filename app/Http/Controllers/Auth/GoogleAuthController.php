<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Laravel\Socialite\Facades\Socialite;

class GoogleAuthController extends Controller
{
    /**
     * Redirect the user to the Google authentication page.
     */
    public function redirect(): RedirectResponse
    {
        if (! config('services.google.enabled', false)) {
            return redirect()->route('login')->with(
                'error',
                'Fitur login dengan akun Google saat ini sedang dinonaktifkan sementara.'
            );
        }

        $clientId = config('services.google.client_id');
        $clientSecret = config('services.google.client_secret');

        if (empty($clientId) || empty($clientSecret)) {
            return redirect()->route('login')->with(
                'error',
                'Kredensial Google OAuth belum dikonfigurasi di file .env (GOOGLE_CLIENT_ID & GOOGLE_CLIENT_SECRET).'
            );
        }

        try {
            return Socialite::driver('google')->redirect();
        } catch (Exception $e) {
            Log::error('Gagal melakukan redirect ke Google OAuth: '.$e->getMessage());

            return redirect()->route('login')->with(
                'error',
                'Terjadi kendala saat menghubungkan ke Google: '.$e->getMessage()
            );
        }
    }

    /**
     * Obtain the user information from Google and authenticate.
     */
    public function callback(): RedirectResponse
    {
        if (! config('services.google.enabled', false)) {
            return redirect()->route('login')->with(
                'error',
                'Fitur login dengan akun Google saat ini sedang dinonaktifkan sementara.'
            );
        }

        try {
            $googleUser = Socialite::driver('google')->user();
        } catch (Exception $e) {
            Log::warning('Google OAuth callback dibatalkan atau gagal: '.$e->getMessage());

            return redirect()->route('login')->with(
                'error',
                'Proses login dengan Google dibatalkan atau terjadi kesalahan. Silakan coba kembali.'
            );
        }

        $googleId = $googleUser->getId();
        $email = $googleUser->getEmail();
        $name = $googleUser->getName() ?: 'Pengguna Google';
        $avatarUrl = $googleUser->getAvatar();

        if (empty($email)) {
            return redirect()->route('login')->with(
                'error',
                'Akun Google Anda tidak menyediakan alamat email yang valid.'
            );
        }

        // 1. Cari user yang sudah terhubung dengan google_id ini
        $user = User::where('google_id', $googleId)->first();

        if (! $user) {
            // 2. Jika belum ada google_id, cari user berdasarkan email (auto-link akun yang sudah ada)
            $user = User::where('email', $email)->first();

            if ($user) {
                $user->google_id = $googleId;
                if (empty($user->avatar_url) && ! empty($avatarUrl)) {
                    $user->avatar_url = $avatarUrl;
                }
                if (empty($user->username)) {
                    $user->username = User::generateUniqueUsername($email, $name);
                }
                if (is_null($user->email_verified_at)) {
                    $user->email_verified_at = now();
                }
                $user->save();
            } else {
                // 3. Buat user baru dengan data dari Google
                $username = User::generateUniqueUsername($email, $name);

                $user = User::create([
                    'name' => $name,
                    'username' => $username,
                    'email' => $email,
                    'avatar_url' => $avatarUrl,
                    'google_id' => $googleId,
                    'email_verified_at' => now(),
                    'role' => 'member',
                    'password' => null,
                ]);
            }
        } else {
            // Perbarui avatar / nama jika berubah di Google
            if (! empty($avatarUrl) && $user->avatar_url !== $avatarUrl) {
                $user->avatar_url = $avatarUrl;
            }
            if (empty($user->username)) {
                $user->username = User::generateUniqueUsername($email, $name);
            }
            $user->save();
        }

        Auth::login($user, remember: true);
        request()->session()->regenerate();

        $redirectUrl = $user->isAdmin() ? route('admin.dashboard') : route('catalog.index');

        return redirect()->intended($redirectUrl)->with(
            'success',
            "Selamat datang kembali, {$user->name}! Berhasil masuk dengan akun Google."
        );
    }
}
