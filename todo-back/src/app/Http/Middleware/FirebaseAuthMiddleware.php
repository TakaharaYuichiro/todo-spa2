<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Kreait\Firebase\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Cache;

class FirebaseAuthMiddleware
{
  protected $auth;

  public function __construct(Auth $auth)
  {
    $this->auth = $auth; // サービスプロバイダーで作成したインスタンスを使用
  }

  public function handle(Request $request, Closure $next)
  {
    // Authorizationヘッダーを取得
    $header = $request->header('Authorization');

    if (!$header || !preg_match('/Bearer\s(\S+)/', $header, $matches)) {
      return response()->json(['error' => 'Unauthorized'], 401);
    }

    $token = $matches[1];
    $cacheKey = 'firebase_token_' . $token;

    // ユーザー情報をキャッシュから取得（なければFirebaseで検証）
    $user = Cache::remember($cacheKey, 300, function () use ($token) {
      try {
        // Firebaseの公開鍵をキャッシュ
        $publicKeys = Cache::remember('firebase_public_keys', 3600, function () {
          return file_get_contents('https://www.googleapis.com/robot/v1/metadata/x509/securetoken@system.gserviceaccount.com');
        });

        $verifiedIdToken = $this->auth->verifyIdToken($token, $publicKeys);
        return User::where('firebase_uid', $verifiedIdToken->claims()->get('sub'))->first();
      } catch (\Exception $e) {
        return null;
      }
    });

    if (!$user) {
      return response()->json(['error' => 'Unauthorized'], 401);
    }

    $request->attributes->set('authenticated_user', $user);

    return $next($request);
  }
}
