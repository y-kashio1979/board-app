<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use App\Models\Thread;
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

        $comment = Comment::create([
            'user_id' => Auth::user()->id,
            'thread_id' => $threadId,
            'body' => $validated['body'],
        ]);

        $comment->load('user');

        return response()->json($comment);
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

    public function showComments(Request $request,String $id)
    {
        $thread = Thread::with('user')->findOrFail($id);

        $comments = Comment::with('user')
            ->where('thread_id', $id)
                ->orderBy('created_at', 'desc')
                    ->paginate(perPage: 20, page: $request->page ?? 1);

        return response()->json($comments);
    }
}
