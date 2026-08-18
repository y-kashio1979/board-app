<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Thread;
use Illuminate\Support\Facades\Auth;

class ThreadController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $keyword = $request->keyword;
        $query = Thread::with('user')
            ->withCount('comments');

        if (!empty($keyword)) {
            $query->where(function ($q) use ($keyword) {
                $q->where('title', 'like', '%' . $keyword . '%')
                    ->orWhereHas('user', function ($userQuery) use ($keyword) {
                        $userQuery->where('name', 'like', '%' . $keyword . '%');
                    });
            });
        }

        $threads = $query
            ->orderBy('created_at', 'desc')
            ->paginate(5);

        return response()->json($threads);
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate(
            [
                'title' => 'required|max:200',
                'body' => 'required|max:200'
            ],
            [
                'require' => ':attributeは必須です',
                'max' => ':attributeは:max文字以下にしてください'
            ],
            [
                'title' => 'スレッドタイトル',
                'body' => 'スレッド本文'
            ]
        );

        $thread = Thread::create([
            'user_id' => Auth::user()->id,
            'title' => $validated['title'],
            'body' => $validated['body'],
            'view_count' => 0,
        ]);

        return response()->json([
            'threadId' => $thread->id,
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Thread $thread)
    {
        return response()->json($thread->load([
            'user',
            'comments' => function ($query) {
                $query->orderby('created_at', 'asc')->with('user');
            },
        ]));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Thread $thread)
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

    public function countUpView(Request $request, Thread $thread)
    {
        $thread->increment('view_count');
    }
}
