<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;
use App\Models\Category;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class CategoryController extends Controller
{
    public function index(): View
    {
        $this->authorize('viewAny', Category::class);
        $categories = Category::query()->with('parent')->latest()->paginate(15);

        return view('categories.index', compact('categories'));
    }

    public function create(): View
    {
        $this->authorize('create', Category::class);
        $categories = Category::query()
            ->whereNull('parent_id')
            ->where('status', 'Active')
            ->orderBy('name')
            ->get();

        return view('categories.create', compact('categories'));
    }

    public function store(StoreCategoryRequest $request): RedirectResponse
    {
        $this->authorize('create', Category::class);
        Category::create($request->validated());

        return redirect()->route('categories.index')->with('success', 'Category created successfully.');
    }

    public function show(Category $category): View
    {
        $this->authorize('view', $category);
        $category->load(['parent', 'children']);

        return view('categories.show', compact('category'));
    }

    public function edit(Category $category): View
    {
        $this->authorize('update', $category);
        $categories = Category::query()
            ->whereNull('parent_id')
            ->where('status', 'Active')
            ->where('id', '!=', $category->id)
            ->orderBy('name')
            ->get();

        return view('categories.edit', compact('category', 'categories'));
    }

    public function update(UpdateCategoryRequest $request, Category $category): RedirectResponse
    {
        $this->authorize('update', $category);
        $category->update($request->validated());

        return redirect()->route('categories.index')->with('success', 'Category updated successfully.');
    }

    public function destroy(Category $category): RedirectResponse
    {
        $this->authorize('delete', $category);
        $category->delete();

        return redirect()->route('categories.index')->with('success', 'Category moved to trash successfully.');
    }

    public function trash(): View
    {
        $this->authorize('viewTrash', Category::class);
        $categories = Category::onlyTrashed()->with('parent')->paginate(15);

        return view('categories.trash', compact('categories'));
    }

    public function restore(int $category): RedirectResponse
    {
        $category = Category::onlyTrashed()->findOrFail($category);
        $this->authorize('restore', $category);
        $category->restore();

        return redirect()->route('categories.trash')->with('success', 'Category restored successfully.');
    }

    public function forceDelete(int $category): RedirectResponse
    {
        $category = Category::onlyTrashed()->findOrFail($category);
        $this->authorize('forceDelete', $category);
        $category->forceDelete();

        return redirect()->route('categories.trash')->with('success', 'Category permanently deleted.');
    }
}
