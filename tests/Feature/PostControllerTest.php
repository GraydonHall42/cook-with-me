<?php

use App\Domain\PostBuilder;
use App\Models\User;
use Illuminate\Testing\Fluent\AssertableJson;

it('allows a user to create a post', function () {
    $user = $this->unitTestUser();
    $this->actingAs($user);

    $this->postJson(route('posts.create'))->assertOk()
        ->assertJson(fn (AssertableJson $json) => $json->where('post.user_id', $user->id));

    expect($user->posts()->count())->toBe(1);
});

it('allows a user to patch a post with updated fields', function () {
    $user = $this->unitTestUser();
    $this->actingAs($user);

    $post = PostBuilder::start()
        ->forUser($user)
        ->title('Original Title')
        ->description('Original description.')
        ->recipe('Original recipe.')
        ->photos([ 'original-photo1.jpg' ])
        ->links([ 'https://original.com' ])
        ->build();

    $updatedPostData = [
        'title' => 'Updated Title',
        'recipe' => 'Updated recipe instructions.',
        'photos' => [ 'updated-photo1.jpg', 'updated-photo2.jpg' ],
    ];

    $this->patchJson(route('posts.update', $post), $updatedPostData)
        ->assertOk()
        ->assertJson(fn (AssertableJson $json) => $json->where('post.title', 'Updated Title')
            ->where('post.description', 'Original description.')
            ->where('post.recipe', 'Updated recipe instructions.')
            ->where('post.photos', [ 'updated-photo1.jpg', 'updated-photo2.jpg' ])
            ->where('post.links', [ 'https://original.com' ])
        );

    // Assert that the post has been updated correctly
    // Original description and links are not touched
    expect($post->refresh()->title)->toBe('Updated Title')
        ->and($post->description)->toBe('Original description.')
        ->and($post->recipe)->toBe('Updated recipe instructions.')
        ->and($post->photos)->toEqual([ 'updated-photo1.jpg', 'updated-photo2.jpg' ])
        ->and($post->links)->toEqual([ 'https://original.com' ]);
});

it('returns all posts for a user', function () {
    $user = $this->unitTestUser();
    $this->actingAs($user);

    PostBuilder::start()
        ->forUser($user)
        ->title('First Post')
        ->build();

    PostBuilder::start()
        ->forUser($user)
        ->title('Second Post')
        ->build();

    $response = $this->getJson(route('posts.list'));

    $response->assertOk()
        ->assertJson(fn (AssertableJson $json) => $json
            ->has('posts', 2)
            ->where('posts.0.title', 'First Post')
            ->where('posts.1.title', 'Second Post')
            ->etc()
        );
});

it('ensures a user cannot access or patch a post that does not belong to them', function () {
    $user_1 = User::factory()->create();
    $post = PostBuilder::start()
        ->forUser($user_1)
        ->title('Title')
        ->build();

    $user_2 = User::factory()->create();
    $this->actingAs($user_2);

    $this->getJson(route('posts.show', $post))
        ->assertForbidden();

    $this->patchJson(route('posts.update', $post), [
        'title' => 'Updated Title'
    ])->assertForbidden();
});

