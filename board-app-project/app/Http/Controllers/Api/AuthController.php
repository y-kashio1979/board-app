<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $credentials = $request->validate([
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
            ]);

        if (!Auth::attempt($credentials)) {
            return response()->json(['message' => 'メールアドレスまたはパスワードが正しくありません'], 401);
        }

        $request->session()->regenerate();

        return response()->json(['message' => 'ログインに成功しました', 'user' => Auth::user()]);
    }

    public function me(Request $request){
        return response()->json($request->user());
    }

    public function logout(Request $request) {
        Auth::guard('web')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return response()->json([
            'message' => 'ログアウトしました',
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
