<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
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
                'unique' => 'この:attributeは既に登録されています',
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
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
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
