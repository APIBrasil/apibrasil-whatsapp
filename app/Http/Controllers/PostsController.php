<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
class PostsController extends Controller
{

    public function index()
    {
        $posts = Post::orderBy('created_at', 'desc')
        ->limit(8)
        ->get();

        return view('posts.index', compact('posts'));
    }

    public function create()
    {
        return view('posts.create');
    }

    public function store(Request $request)
    {
        try {

            Post::create(array_merge($request->only('title', 'description', 'body'),[
                'user_id' => auth()->id()
            ]));

            return redirect()->route('posts.index')
                ->withSuccess(__('Post created successfully.'));

        } catch (\Throwable $th) {
            \Log::critical($th->getMessage());
        }

    }

    public function show(Post $post)
    {
        return view('posts.show', [
            'post' => $post
        ]);
    }

    public function edit(Post $post)
    {
        return view('posts.edit', [
            'post' => $post
        ]);
    }

    public function update(Request $request, Post $post)
    {
        $post->update($request->only('title', 'description', 'body'));

        return redirect()->route('posts.index')
            ->withSuccess(__('Post updated successfully.'));
    }

    public function destroy($id)
    {
        $post->delete();

        return redirect()->route('posts.index')
            ->withSuccess(__('Post deleted successfully.'));
    }
}
