<?php

namespace App\Http\Controllers;

use App\Models\Tag;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Inertia\Inertia;
use Inertia\Response;

class TagController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('tags/Index', [
            'tags' => Tag::withCount('articles')->latest()->get(),
        ]);
    }

    public function create(): Response
    {
        return Inertia::render('tags/Create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:tags,name',
        ]);

        Tag::create([
            'name' => $validated['name'],
            'slug' => Str::slug($validated['name']),
        ]);

        return redirect()->route('tags.index')->with('success', __('Tag created successfully.'));
    }

    public function edit(Tag $tag): Response
    {
        return Inertia::render('tags/Edit', [
            'tag' => $tag,
        ]);
    }

    public function update(Request $request, Tag $tag): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:tags,name,' . $tag->id,
        ]);

        $tag->update([
            'name' => $validated['name'],
            'slug' => Str::slug($validated['name']),
        ]);

        return redirect()->route('tags.index')->with('success', __('Tag updated successfully.'));
    }

    public function destroy(Tag $tag): RedirectResponse
    {
        if ($tag->articles()->exists()) {
            return back()->with('error', __('Cannot delete a tag that is attached to articles.'));
        }

        $tag->delete();

        return redirect()->route('tags.index')->with('success', __('Tag deleted successfully.'));
    }
}