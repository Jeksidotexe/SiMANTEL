<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Validation\Rules;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Validation\ValidationException;
use App\Mail\ResetPassword;
use Illuminate\Support\Facades\Mail;
use Illuminate\Auth\Passwords\PasswordBroker;

class PasswordResetController extends Controller
{
    /**
     * Menampilkan halaman form untuk meminta link reset password.
     */
    public function showLinkRequestForm()
    {
        return view('auth.forgot-password');
    }

    /**
     * Mengirim link reset password ke email pengguna.
     */
    public function sendResetLinkEmail(Request $request)
    {
        $request->validate([
            'email' => 'required|email|string|exists:users,email'
        ], [
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'email.exists' => 'Email tidak terdaftar di sistem kami.',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return back()->withErrors(['email' => 'Email tidak terdaftar di sistem kami.']);
        }

        $token = Password::createToken($user);

        try {
            Mail::to($user->email)->send(new ResetPassword($user, $token));

            return back()->with('status', 'Kami telah mengirimkan link reset password ke email Anda!');
        } catch (\Exception $e) {
            return back()->withErrors(['email' => 'Gagal mengirim link reset. Coba lagi nanti.']);
        }
    }

    /**
     * Menampilkan halaman form untuk reset password.
     */
    public function showResetForm(Request $request, $token = null)
    {
        return view('auth.reset-password')->with([
            'token' => $token,
            'email' => $request->email
        ]);
    }

    /**
     * Memproses reset password.
     */
    public function reset(Request $request)
    {
        $request->validate([
            'token' => 'required|string',
            'email' => 'required|email|string|exists:users,email',

            'password' => ['required', 'string', 'min:8', Rules\Password::defaults()],

            'password_confirmation' => 'required|string|same:password',

        ], [
            'password.required' => 'Password baru wajib diisi.',
            'password.min' => 'Password minimal 8 karakter.',

            'password_confirmation.required' => 'Konfirmasi password wajib diisi.',
            'password_confirmation.same' => 'Konfirmasi password tidak cocok dengan password baru.',
        ]);

        $user = User::where('email', $request->email)->first();

        if ($user && Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'password' => 'Password baru tidak boleh sama dengan password lama Anda.',
            ]);
        }

        $status = Password::broker()->reset(
            $request->only('email', 'password', 'token'),
            function ($user, $password) {
                $user->forceFill([
                    'password' => Hash::make($password)
                ])->setRememberToken(Str::random(60));

                $user->save();
            }
        );

        if ($status == Password::PASSWORD_RESET) {
            DB::table('password_reset_tokens')->where('email', $request->email)->delete();

            return redirect()->route('login')
                ->with('success', 'Password Anda berhasil direset! Silakan login dengan password baru.');
        }

        return back()->withErrors(['email' => 'Token reset ini tidak valid atau telah kedaluwarsa.']);
    }
}
