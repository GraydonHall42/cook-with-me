<?php

namespace App\Models;

use App\Http\Requests\Posts\CreatePostRequest;
use Illuminate\Contracts\Support\Responsable;
use Illuminate\Database\Eloquent\Model;

class Post extends Model implements Responsable
{
    protected $guarded = [];

    protected function casts(): array
    {
        return [
            'photos' => 'array',
            'links' => 'array',
        ];
    }

    public function patchWithUserChanges(array $attributes): self
    {
        $this->update($attributes);

        return $this->refresh();
    }

    public static function fromRequest(CreatePostRequest $request): self
    {
        return Post::create([
            'user_id' => $request->user()->id,
            'title' => $request->title(),
            'description' => $request->description(),
            'recipe' => $request->recipe(),
            'photos' => $request->photos(),
            'links' => $request->links(),
        ]);
    }

    public function toResponse($request)
    {
        return response()->json([
            'post' => $this
        ]);
    }
}
