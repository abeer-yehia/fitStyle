<?php

namespace App\Http\Controllers;

use App\Http\Requests\FilterProductRequest;
use App\Http\Resources\CategoryResource;
use App\Http\Resources\ProductResource;
use App\Http\Resources\SectionResource;
use App\Models\Category;
use App\Models\Product;
use App\Models\Section;
use Illuminate\Http\Request;

class ShopController extends Controller
{
    public function __invoke(Request $request)
    {

        $products = ProductResource::collection(
            Product::query()
                ->withCategories($request->category ?? '')
                ->withSections($request->section ?? '')
                ->withMinPrice($request->min_price ?? '')
                ->withMaxPrice($request->max_price ?? '')
                ->withSearch($request->search ?? '')
                ->withSortBy($request->sortBy ?? '')
                ->with('category')
                ->with('section')
                ->where('available_quantity', '>', 0)
                ->paginate(8)
                ->withQueryString()
        );

        $categories = CategoryResource::collection(Category::withCount('products')->get());
        $sections = SectionResource::collection(Section::withCount('products')->get());

        return inertia('Shop', [
            'products' => $products,
            'sections' => $sections,
            'section' => $request->section ?? '',
            'categories' => $categories,
            'category' => $request->category ?? '',
            'min_price' => $request->min_price ?? '',
            'max_price' => $request->max_price ?? '',
            'sortBy' => $request->sortBy ?? '',
            'search' => $request->search
        ]);
    }
}
