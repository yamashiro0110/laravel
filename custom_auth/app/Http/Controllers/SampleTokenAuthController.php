<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

use function PHPUnit\Framework\isNull;

class SampleTokenAuthController extends Controller
{

    public function auth(Request $request)
    {
        Log::debug("auth 開始", ['request', $request]);

        Auth::logout();
        Log::debug("ログアウト実行");

        $user = Auth::user();

        if ($user) {
            Log::debug("認証に成功", ['user', $user->getAuthIdentifier()]);
            return ['id', $user->getAuthIdentifier()];
        }

        throw new Exception('Error auth');
    }

    public function userInfo(Request $request)
    {
        Log::debug("userInfo 開始", ['request', $request]);
        // $user = Auth::guard('sample')->user();
        $user = Auth::user();

        if (is_null($user)) {
            throw new Exception('認証されていないユーザー');
        }

        return array(
            'id' => $user->getAuthIdentifier()
        );
    }

}
