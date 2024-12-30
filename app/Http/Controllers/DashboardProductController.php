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
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

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
        $categories = Category::all(['id', 'name']); 
        $sections = Section::all(['id', 'name']);

        return inertia('Dashboard/Product/create', [
            'categories' => $categories,
            'sections' => $sections,
        ]);
    }

    public function store(StoreProductRequest $request)
    {
        $product =  Product::create([
            'name' => $request->name, 
            'category_id' =>$request->category_id,
            'section_id' =>$request->section_id,
            'description' =>$request->description,
            'price' =>$request->price,

            'slug' => Str::slug($request->name),

        ]);
        if ($request->hasFile('image')) {
            $fileName = $request->file('image')->getClientOriginalName();
            
            $request->file('image')->storeAs('images', $fileName, 'public');
            $product->images()->create([
                'url' => $fileName,  
            ]);
        }
      
      
        return redirect()->route('products.index');
    }

    public function edit(Product $product)
    {
        $categories = Category::all();
        $sections = Section::all();
        return inertia('Dashboard/Product/edit', [ 'product' => $product,
        'categories' => $categories,
        'sections' => $sections]);
    }

    public function update(UpdateProductRequest $request, Product $product)
    {
        $product->update([
            'name' => $request->name,
            'category_id' => $request->category_id,
            'section_id' => $request->section_id,
            'description' => $request->description,
            'price' => $request->price,
            'slug' => Str::slug($request->name),
        ]);
        if ($request->hasFile('image')) {
            $fileName = $request->file('image')->getClientOriginalName();
    
            $request->file('image')->storeAs('images', $fileName, 'public');
            if ($product->images()->exists()) {
                $oldImage = $product->images->first();
                Storage::delete('public/' . $oldImage->url); 
                $oldImage->delete();
            }
            $product->images()->create([
                'url' => $fileName,
            ]);
        }
      return redirect()->route('products.index');
    }

    public function destroy(Product $product)
    {
        $product->delete();
        return redirect()->back();
    }
}
