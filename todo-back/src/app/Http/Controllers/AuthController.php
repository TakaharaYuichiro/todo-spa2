<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Kreait\Firebase\Auth as FirebaseAuth;
use App\Models\User;

class AuthController extends Controller
{
  protected $firebaseAuth;

  public function __construct(FirebaseAuth $firebaseAuth)
  {
    $this->firebaseAuth = $firebaseAuth;
  }

  public function register(Request $request)
  {
    // バリデーション
    $validator = Validator::make($request->all(), [
      'name' => 'required|min:0|max:191',
      'email' => 'required|email|unique:users,email',
      'idToken' => 'required',
    ]);

    if ($validator->fails()) {
      return response()->json(['error' => $validator->errors()], 400);
    }

    try {
      // Firebaseのトークンを検証
      $verifiedIdToken = $this->firebaseAuth->verifyIdToken($request->idToken);
      $firebaseUserId = $verifiedIdToken->claims()->get('sub');

      // ユーザーをLaravelのDBに保存
      $user = User::create([
        'name' => $request->name,
        'email' => $request->email,
        'firebase_uid' => $firebaseUserId,
        'password' => bcrypt('random_password'), // DBには適当なパスワードで項目を埋めておく
      ]);

      return response()->json(['message' => 'ユーザー登録成功', 'user' => $user], 201);
    } catch (\Exception $e) {
      return response()->json(['error' => '認証エラー', 'message' => $e->getMessage()], 401);
    }
  }

  public function getUserName(Request $request)
  {
    try {
      // Firebaseのトークンを検証
      $verifiedIdToken = $this->firebaseAuth->verifyIdToken($request->idToken);
      $firebaseUserId = $verifiedIdToken->claims()->get('sub');

      $user = User::where('firebase_uid', $firebaseUserId)->first();
      return response()->json(['id' => $user->id, 'name' => $user->name], 201);
    } catch (\Exception $e) {
      return response()->json(['error' => 'エラー', 'message' => $e->getMessage()], 401);
    }
  }

  public function getUsers()
  {
    try {
      $users = User::select('id', 'name')->get();
      return response()->json($users, 201);
    } catch (\Exception $e) {
      return response()->json(['error' => 'エラー', 'message' => $e->getMessage()], 401);
    }
  }
}
