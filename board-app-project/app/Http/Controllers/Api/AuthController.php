<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use GuzzleHttp\Promise\Create;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $credentials = $request->validate(
            [
                'email' => ['required', 'email'],
                'password' => ['required'],
            ],
            [
                'required' => ':attributeは必須です',
                'email' => ':attributeの形式が正しくありません',
            ],
            [
                'email' => 'メールアドレス',
                'password' => 'パスワード',
            ]
        );

        if (!Auth::attempt($credentials)) {
            return response()->json(['message' => 'メールアドレスまたはパスワードが正しくありません'], 401);
        }

        $request->session()->regenerate();

        return response()->json(['message' => 'ログインに成功しました', 'user' => Auth::user()]);
    }

    public function me(Request $request)
    {
        return response()->json($request->user());
    }

    public function register(Request $request)
    {
        $user = $request->validate(
            [
                'name' => 'required|max:20',
                'email' => 'required|email|unique:users,email',
                'password' => 'required|min:8|confirmed',
            ],
            [
                'required' => ':attributeは必須入力です',
                'max' => ':attributeは:max文字以内で入力してください',
                'email' => ':attributeの形式が正しくありません',
                'unique' => '不正な:attributeです',
                'min' => ':attributeは:min文字以上で入力してください',
                'confirmed' => '確認用パスワードが:attributeと一致しません',
            ],
            [
                'name' => 'ユーザー名',
                'email' => 'メールアドレス',
                'password' => 'パスワード',
            ]
        );
        User::create([
            'name' => $user['name'],
            'email' => $user['email'],
            'password' => $user['password']
        ]);

        return response()->json([
            'message' => "登録が完了しました"
        ]);
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
