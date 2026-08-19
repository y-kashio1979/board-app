<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
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
    public function store(Request $request, int $threadId)
    {
        $validated = $request->validate(
            [
                'body' => 'required|max:200'
            ],
            [
                'required' => ':attributeは必須です',
                'max' => ':attributeは:max文字以下にしてください'
            ],
            [
                'body' => 'コメント本文'
            ]
        );

        Comment::create([
            'user_id' => Auth::user()->id,
            'thread_id' => $threadId,
            'body' => $validated['body'],
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
