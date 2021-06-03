<?php

namespace App\Http\Controllers;

use App\Auth\TokenUser;
use App\Http\Middleware\Authenticate;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

use function PHPUnit\Framework\isNull;

class SampleTokenAuthController extends Controller
{
    private function toResponse(TokenUser $user) {
        return array(
            'id' => $user->getAuthIdentifierName(),
            'token' => $user->getAuthIdentifier(),
        );
    }

    public function auth(Request $request)
    {
        Log::debug("auth 開始", ['request', $request]);

        Auth::logout();
        Log::debug("ログアウト実行");

        $user = Auth::user();

        if ($user) {
            Log::debug("認証に成功", ['user', $user->getAuthIdentifier()]);
            return $this->toResponse($user);
        }

        throw new Exception('Error auth');
    }

    public function userInfo(Request $request)
    {
        Log::debug("userInfo 開始", ['request', $request]);
        $user = Auth::user();

        if (is_null($user)) {
            throw new Exception('認証されていないユーザー');
        }

        return $this->toResponse($user);
    }

}
