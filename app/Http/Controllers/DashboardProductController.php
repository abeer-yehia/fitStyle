<?php

namespace App\Http\Controllers;

use App\Http\Requests\Product\StoreProductRequest;
use App\Http\Requests\Product\UpdateProductRequest;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use Illuminate\Http\Request;
use App\Http\Resources\CategoryResource;
use App\Http\Resources\SectionResource;
use App\Models\Category;
use App\Models\Section;

class DashboardProductController extends Controller
{
   
    public function index(Request $request)
    {
        $products = ProductResource::collection(
            Product::query()
            ->with('category')
            ->with('section')
                ->get()
        );
        return inertia('Dashboard/Product/index', [
            'products' => $products
        ]);
    }
    public function create()
    {
        return inertia('Dashboard/Product/create');
    }

    public function store(StoreProductRequest $request)
    {
      Product::create([
            'name' => $request->name,
        ]);
        return redirect()->route('products.index');
    }

    public function edit(Product $product)
    {
        return inertia('Dashboard/Product/edit', ['product' => $product]);
    }

    public function update(UpdateProductRequest $request, Product $product)
    {
      $product->update($request->all());
      return redirect()->route('products.index');
    }

    public function destroy(Product $product)
    {
        $product->delete();
        return redirect()->back();
    }
}
