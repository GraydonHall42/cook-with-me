<?php

namespace App\Http\Requests\Posts;

use Illuminate\Foundation\Http\FormRequest;

class EditPostRequest extends FormRequest
{
    public function rules(): array
    {
        $optional = [ 'sometimes', 'nullable' ];

        return [
            'title' => [ ...$optional, 'string', 'max:255' ],
            'description' => [ ...$optional, 'string' ],
            'recipe' => [ ...$optional, 'string' ],
            'photos' => [ ...$optional, 'array' ],
            'photos.*' => [ ...$optional, 'string' ],
            'links' => [ ...$optional, 'array' ],
            'links.*' => [ ...$optional, 'url' ],
        ];
    }

    public function title(): ?string
    {
        return $this->input('title');
    }

    public function description(): ?string
    {
        return $this->input('description');
    }

    public function recipe(): ?string
    {
        return $this->input('recipe');
    }

    public function photos(): array
    {
        return $this->input('photos', []);
    }

    public function links(): array
    {
        return $this->input('links', []);
    }
}
