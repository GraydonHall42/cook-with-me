<?php

namespace App\Http\Controllers;

use App\Http\Requests\Posts\EditPostRequest;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class PostController extends Controller
{
    public function create(Request $request): Post
    {
        return Post::forUser($request);
    }

    public function patch(Post $post, EditPostRequest $request): Post
    {
        return $post->patchWithUserChanges($request->validated());
    }

    public function show(Post $post): Post
    {
        return $post;
    }

    public function delete(Post $post): Response
    {
        $post->delete();

        return response()->noContent();
    }

    public function list(Request $request) {
        return response()->json([
           'posts' => $request->user()->posts()->get()
        ]);
    }
}
