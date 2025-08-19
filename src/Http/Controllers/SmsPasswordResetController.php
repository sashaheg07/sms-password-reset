<?php

namespace sashaheg07\SmsPasswordReset\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use ItTop\SmsPasswordReset\Services\SmscService;
use App\Http\Controllers\Controller;

class SmsPasswordResetController extends Controller
{
    public function forgot(Request $request, SmscService $smsc)
    {
        $request->validate(['phone' => 'required|string']);

        $phone = $request->phone;
        $code = rand(1000, 9999);

        DB::table('password_resets')->updateOrInsert(
            ['phone' => $phone],
            ['token' => $code, 'created_at' => now()]
        );

        $smsc->send($phone, "Ваш код для восстановления: $code");

        return response()->json(['message' => 'Код отправлен']);
    }

    public function verify(Request $request)
    {
        $request->validate([
            'phone' => 'required|string',
            'code'  => 'required|string'
        ]);

        $record = DB::table('password_resets')
            ->where('phone', $request->phone)
            ->where('token', $request->code)
            ->where('created_at', '>', now()->subMinutes(10))
            ->first();

        if (!$record) {
            return response()->json(['error' => 'Код неверный или истек'], 400);
        }

        return response()->json(['message' => 'Код подтвержден']);
    }

    public function reset(Request $request)
    {
        $request->validate([
            'phone' => 'required|string',
            'code'  => 'required|string',
            'password' => 'required|min:6|confirmed',
        ]);

        $record = DB::table('password_resets')
            ->where('phone', $request->phone)
            ->where('token', $request->code)
            ->first();

        if (!$record) {
            return response()->json(['error' => 'Код неверный'], 400);
        }

        $user = User::where('phone', $request->phone)->first();
        if (!$user) {
            return response()->json(['error' => 'Пользователь не найден'], 404);
        }

        $user->password = Hash::make($request->password);
        $user->save();

        DB::table('password_resets')->where('phone', $request->phone)->delete();

        return response()->json(['message' => 'Пароль успешно обновлен']);
    }
}
