<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\URL;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Str;

class GoogleController extends Controller
{
    /**
     * Redirect the user to the Google authentication page.
     * Menggunakan URL dinamis agar selalu cocok dengan domain saat ini.
     */
    public function redirect()
    {
        // Paksa redirect URI menggunakan APP_URL yang aktif saat ini
        $redirectUri = url('/auth/google/callback');

        return Socialite::driver('google')
            ->redirectUrl($redirectUri)
            ->with(['prompt' => 'select_account'])
            ->redirect();
    }

    /**
     * Obtain the user information from Google.
     */
    public function callback()
    {
        try {
            // Paksa redirect URI sama dengan saat redirect agar tidak mismatch
            $redirectUri = url('/auth/google/callback');

            $googleUser = Socialite::driver('google')
                ->redirectUrl($redirectUri)
                ->user();

            // KUNCI: Wajib email kampus @students.polmed.ac.id
            if (!Str::endsWith($googleUser->getEmail(), '@students.polmed.ac.id')) {
                return redirect()->route('login', ['oauth_error' => 'not_polmed']);
            }

            // Cek apakah user dengan google_id ini sudah ada
            $user = User::where('google_id', $googleUser->getId())->first();

            if (!$user) {
                // Jika belum, cek apakah emailnya sudah terdaftar
                $user = User::where('email', $googleUser->getEmail())->first();

                if ($user) {
                    // Update user yang sudah ada dengan google_id
                    $user->update([
                        'google_id' => $googleUser->getId(),
                        'avatar'    => $googleUser->getAvatar(),
                    ]);
                } else {
                    // Buat user baru secara otomatis
                    $user = User::create([
                        'name'      => $googleUser->getName(),
                        'nama'      => $googleUser->getName(),
                        'email'     => $googleUser->getEmail(),
                        'google_id' => $googleUser->getId(),
                        'avatar'    => $googleUser->getAvatar(),
                        'password'  => null,
                        'nim'       => null,
                        'prodi'     => null,
                        'is_active' => true,
                    ]);

                    // Assign role mahasiswa secara default
                    $user->assignRole('mahasiswa');
                }
            }

            // Login user
            Auth::login($user, true);

            // Redirect ke dashboard sesuai role
            if ($user->isAdmin()) {
                return redirect()->route('admin.dashboard');
            } elseif ($user->isStaffDewan()) {
                return redirect()->route('dewan.dashboard');
            } elseif ($user->isHMPS() || $user->isUKM()) {
                return redirect()->route('organisasi.dashboard');
            }

            return redirect()->route('mahasiswa.dashboard');

        } catch (\Exception $e) {
            return redirect()->route('login')->with('error', 'Gagal login dengan Google: ' . $e->getMessage());
        }
    }
}
