<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Category;
use App\Models\Tag;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Inertia\Inertia;
use Inertia\Response;

class ArticleController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('articles/Index', [
            'articles' => Article::with(['category', 'tags'])->latest()->get(),
        ]);
    }

    public function create(): Response
    {
        return Inertia::render('articles/Create', [
            'categories' => Category::all(),
            'tags' => Tag::all(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'body' => 'required|string',
            'category_id' => 'required|exists:categories,id',
            'tags' => 'array',
            'tags.*' => 'exists:tags,id',
            'is_published' => 'boolean',
        ]);

        $article = Article::create([
            'title' => $validated['title'],
            'slug' => Str::slug($validated['title']),
            'body' => $validated['body'],
            'category_id' => $validated['category_id'],
            'is_published' => $validated['is_published'] ?? false,
        ]);

        if (! empty($validated['tags'])) {
            $article->tags()->sync($validated['tags']);
        }

        return redirect()->route('articles.index')->with(__('success'), __('Article created successfully.'));
    }

    public function edit(Article $article): Response
    {
        return Inertia::render('articles/Edit', [
            'article' => $article->load('tags'),
            'categories' => Category::all(),
            'tags' => Tag::all(),
        ]);
    }

    public function update(Request $request, Article $article): RedirectResponse
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'body' => 'required|string',
            'category_id' => 'required|exists:categories,id',
            'tags' => 'array',
            'tags.*' => 'exists:tags,id',
            'is_published' => 'boolean',
        ]);

        $article->update([
            'title' => $validated['title'],
            'slug' => Str::slug($validated['title']),
            'body' => $validated['body'],
            'category_id' => $validated['category_id'],
            'is_published' => $validated['is_published'] ?? false,
        ]);

        $article->tags()->sync($validated['tags'] ?? []);

        return redirect()->route('articles.index')->with(__('success'), __('Article updated successfully.'));
    }

    public function destroy(Article $article): RedirectResponse
    {
        $article->delete();

        return redirect()->route('articles.index')->with(__('success'), __('Article deleted successfully.'));
    }
}