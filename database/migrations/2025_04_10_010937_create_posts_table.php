<?php

use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('posts', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(User::class)->constrained();

            $table->string('title')->nullable();
            $table->text('description')->nullable();
            $table->text('recipe')->nullable();
            $table->jsonb('photos')->default('[]');
            $table->jsonb('links')->default('[]');

            $table->timestamps();
        });
    }
};
