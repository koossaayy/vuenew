<?php

namespace Database\Seeders;

use App\Models\Article;
use App\Models\Category;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

        $categories = collect([
            'Technology', 'Science', 'Sports', 'Entertainment', 'Business',
        ])->map(fn ($name) => Category::create([
            'name' => $name,
            'slug' => Str::slug($name),
        ]));

        $tags = collect([
            'Laravel', 'Vue.js', 'PHP', 'JavaScript', 'Tutorial',
            'News', 'Opinion', 'Guide', 'Review', 'Tips',
        ])->map(fn ($name) => Tag::create([
            'name' => $name,
            'slug' => Str::slug($name),
        ]));

        $articles = [
            ['title' => 'Getting Started with Laravel', 'body' => 'Laravel is a web application framework with expressive, elegant syntax. We believe development must be an enjoyable and creative experience to be truly fulfilling.', 'is_published' => true],
            ['title' => 'Vue.js Composition API Deep Dive', 'body' => 'The Composition API is a set of APIs that allows us to author Vue components using imported functions instead of declaring options.', 'is_published' => true],
            ['title' => 'Building REST APIs with PHP', 'body' => 'REST APIs are the backbone of modern web applications. In this article, we explore best practices for building robust APIs.', 'is_published' => false],
            ['title' => 'JavaScript ES2024 Features', 'body' => 'JavaScript continues to evolve with new features every year. Let us explore what is new in ES2024 and how it affects your daily workflow.', 'is_published' => true],
            ['title' => 'Database Optimization Tips', 'body' => 'Database performance is critical for any application. Here are some tips to optimize your database queries and improve response times.', 'is_published' => false],
        ];

        foreach ($articles as $i => $data) {
            $article = Article::create([
                'title' => $data['title'],
                'slug' => Str::slug($data['title']),
                'body' => $data['body'],
                'category_id' => $categories[$i % $categories->count()]->id,
                'is_published' => $data['is_published'],
            ]);

            $article->tags()->attach(
                $tags->random(rand(1, 3))->pluck('id')
            );
        }
    }
}
