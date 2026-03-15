<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Inertia\Inertia;
use Inertia\Response;

class CategoryController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('categories/Index', [
            'categories' => Category::withCount('articles')->latest()->get(),
        ]);
    }

    public function create(): Response
    {
        return Inertia::render('categories/Create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:categories,name',
        ]);

        Category::create([
            'name' => $validated['name'],
            'slug' => Str::slug($validated['name']),
        ]);

        return redirect()->route('categories.index')->with('success', __('Category created successfully.'));
    }

    public function edit(Category $category): Response
    {
        return Inertia::render('categories/Edit', [
            'category' => $category,
        ]);
    }

    public function update(Request $request, Category $category): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:categories,name,' . $category->id,
        ]);

        $category->update([
            'name' => $validated['name'],
            'slug' => Str::slug($validated['name']),
        ]);

        return redirect()->route('categories.index')->with('success', __('Category updated successfully.'));
    }

    public function destroy(Category $category): RedirectResponse
    {
        if ($category->articles()->exists()) {
            return back()->with('error', __('Cannot delete a category that has articles.'));
        }

        $category->delete();

        return redirect()->route('categories.index')->with('success', __('Category deleted successfully.'));
    }
}