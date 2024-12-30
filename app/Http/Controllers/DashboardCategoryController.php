<?php

namespace App\Http\Controllers;

use App\Http\Requests\Category\StoreCategoryRequest;
use App\Http\Requests\Category\UpdateCategoryRequest;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Resources\CategoryResource;
use Illuminate\Support\Str;


class DashboardCategoryController extends Controller
{
    public function index(Request $request)
    {
        $categories = CategoryResource::collection(
            Category::query()
            ->with('products')
                ->get()
        );
        return inertia('Dashboard/Category/index', [
            'categories' => $categories
        ]);

    }

    public function create()
    {
        return inertia('Dashboard/Category/create');
    }

    public function store(StoreCategoryRequest $request)
    {
      Category::create([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
        ]);
        return redirect()->route('categories.index');
    }

    public function edit(Category $category)
    {
        return inertia('Dashboard/Category/edit', ['category' => $category]);
    }

    public function update(UpdateCategoryRequest $request, Category $category)
    {
      $category->update($request->all());
      return redirect()->route('categories.index');
    }

    public function destroy(Category $category)
    {
        $category->delete();
        return redirect()->back();
    }
}
