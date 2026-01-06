<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use App\Http\Requests\Auth\PasswordResetLinkRequest;
use App\Http\Requests\Auth\NewPasswordRequest;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Auth\Events\PasswordReset;


class PasswordResetController extends Controller
{
    public function sendResetLinkEmail(PasswordResetLinkRequest $request) {

        $status = Password::sendResetLink(
            $request->validated()
        );

        // Handle different password reset statuses
        switch ($status) {
            case Password::RESET_LINK_SENT:
                return response()->json([
                    'status' => true,
                    'message' => __($status)], 200);
            case Password::INVALID_USER:
                return response()->json([
                    'status' => false,
                    'message' => 'Alamat email tidak terdaftar'], 422);
            case Password::RESET_THROTTLED:
                return response()->json([
                    'status' => false,
                    'message' => 'Silakan tunggu sebelum meminta reset password lagi'], 429);
            default:
                return response()->json([
                    'status' => false,
                    'message' => 'Terjadi kesalahan, silakan coba lagi'], 500);
        }
    }

    public function resetPassword(NewPasswordRequest $request)
    {
        // Here we will attempt to reset the user's password. If it is successful we
        // will update the password on an actual user model and persist it to the
        // database. Otherwise we will parse the error and return the response.
        $status = Password::reset(
            $request->validated(),
            function (User $user, string $password) {
                $user->forceFill([
                    'password' => Hash::make($password)
                ])->setRememberToken(Str::random(60));

                $user->save();

                event(new PasswordReset($user));
            }
        );

        // If the password was successfully reset, we will redirect the user back to
        // the application's login screen. If there is an error we can redirect
        // them back to where they came from with their error message.
        switch ($status) {
            case Password::PASSWORD_RESET:
                return response()->json(['message' => __($status)], 200);

            case Password::INVALID_USER:
                return response()->json(['message' => 'Email tidak terdaftar'], 422);

            case Password::INVALID_TOKEN:
                return response()->json(['message' => 'Token reset password tidak valid atau sudah expired'], 422);

            default:
                return response()->json(['message' => 'Password tidak berhasil di reset'], 422);
        }
    }
}
