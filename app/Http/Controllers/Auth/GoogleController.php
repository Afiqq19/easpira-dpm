<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Role;

class GoogleController extends Controller
{
    public function redirect()
    {
        $redirectUri = url('/auth/google/callback');
        return Socialite::driver('google')
            ->redirectUrl($redirectUri)
            ->with(['prompt' => 'select_account'])
            ->redirect();
    }

    public function callback()
    {
        try {
            $redirectUri = url('/auth/google/callback');
            $googleUser = Socialite::driver('google')
                ->redirectUrl($redirectUri)
                ->user();

            // Wajib email kampus
            if (!Str::endsWith($googleUser->getEmail(), '@students.polmed.ac.id')) {
                return redirect()->route('login', ['oauth_error' => 'not_polmed']);
            }

            // Pastikan role mahasiswa ada (auto-create jika belum di DB)
            Role::firstOrCreate(['name' => 'mahasiswa', 'guard_name' => 'web']);

            // Cari user by google_id dulu
            $user = User::where('google_id', $googleUser->getId())->first();

            if (!$user) {
                $user = User::where('email', $googleUser->getEmail())->first();
                if ($user) {
                    $user->update([
                        'google_id' => $googleUser->getId(),
                        'avatar'    => $googleUser->getAvatar(),
                    ]);
                } else {
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
                }
            }

            // FIX: Jika user tidak punya role sama sekali, beri role mahasiswa
            if ($user->roles->isEmpty()) {
                $user->assignRole('mahasiswa');
            }

            Auth::login($user, true);

            if ($user->hasRole('admin')) {
                return redirect()->route('admin.dashboard');
            } elseif ($user->hasRole('staff_dewan')) {
                return redirect()->route('dewan.dashboard');
            } elseif ($user->hasRole('hmps') || $user->hasRole('ukm')) {
                return redirect()->route('organisasi.dashboard');
            }

            return redirect()->route('mahasiswa.dashboard');

        } catch (\Exception $e) {
            return redirect()->route('login')->with('error', 'Gagal login Google: ' . $e->getMessage());
        }
    }
}
