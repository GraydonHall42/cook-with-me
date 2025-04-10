<?php

namespace App\Domain;

use App\Models\Post;
use App\Models\User;
use Carbon\Carbon;
use Ramsey\Uuid\Uuid;

class PostBuilder
{
    public array $attributes = [];
    private function __construct()
    {
    }

    public static function start(): self
    {
        return new self;
    }

    public function forUser(User $user): self
    {
        $this->attributes['user_id'] = $user->id;

        return $this;
    }

    public function title(?string $value): self
    {
        $this->attributes['title'] = $value;

        return $this;
    }

    public function description(?string $value): self
    {
        $this->attributes['description'] = $value;

        return $this;
    }

    public function recipe(?string $value): self
    {
        $this->attributes['recipe'] = $value;

        return $this;
    }

    public function photos(array $photoUrls): self
    {
        $this->attributes['photos'] = $photoUrls;

        return $this;
    }

    public function links(array $urls): self
    {
        $this->attributes['links'] = $urls;

        return $this;
    }

    public function build(): Post
    {
        $required = ['user_id'];

        foreach ($required as $field) {
            if (!isset($this->attributes[$field])) {
                throw new \InvalidArgumentException("Attempting to build without required field: $field");
            }
        }

        // Create the Post instance
        return Post::create([
            ...$this->attributes
        ]);
    }
}
