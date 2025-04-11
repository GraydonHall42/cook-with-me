<?php

namespace App\Models;

use App\Http\Requests\Posts\EditPostRequest;
use Illuminate\Contracts\Support\Responsable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class Post extends Model implements Responsable
{
    use HasFactory;
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

    public static function forUser(Request $request): self
    {
        return Post::create([
            'user_id' => $request->user()->id,
        ]);
    }

    public function toResponse($request)
    {
        return response()->json([
            'post' => $this
        ]);
    }
}
