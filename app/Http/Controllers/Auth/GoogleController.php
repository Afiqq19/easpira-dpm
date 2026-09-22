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
            ->stateless()
            ->redirectUrl($redirectUri)
            ->with(['prompt' => 'select_account'])
            ->redirect();
    }

    public function callback()
    {
        try {
            $redirectUri = url('/auth/google/callback');
            $googleUser = Socialite::driver('google')
                ->stateless()
                ->redirectUrl($redirectUri)
                ->user();

            $email = strtolower(trim($googleUser->getEmail() ?? ''));

            // Validasi Domain Kampus Polmed (Hanya @students.polmed.ac.id)
            if (!Str::endsWith($email, ['@students.polmed.ac.id'])) {
                return redirect()->route('login', [
                    'oauth_error'    => 'not_polmed',
                    'rejected_email' => $email
                ]);
            }

            // Pastikan role mahasiswa ada (auto-create jika belum di DB)
            Role::firstOrCreate(['name' => 'mahasiswa', 'guard_name' => 'web']);

            // Cari user by google_id dulu
            $user = User::where('google_id', $googleUser->getId())->first();

            if (!$user) {
                $user = User::where('email', $email)->first();
                if ($user) {
                    $user->update([
                        'google_id' => $googleUser->getId(),
                        'avatar'    => $googleUser->getAvatar(),
                    ]);
                } else {
                    $user = User::create([
                        'name'      => $googleUser->getName(),
                        'nama'      => $googleUser->getName(),
                        'email'     => $email,
                        'google_id' => $googleUser->getId(),
                        'avatar'    => $googleUser->getAvatar(),
                        'password'  => null,
                        'nim'       => null,
                        'prodi'     => null,
                        'is_active' => true,
                    ]);
                }
            }

            // Jika user belum punya role, beri role mahasiswa
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

        } catch (\Throwable $e) {
            return redirect()->route('login')->with('error', 'Gagal memproses login Google: ' . $e->getMessage());
        }
    }
}
