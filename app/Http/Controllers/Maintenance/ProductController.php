<?php

namespace App\Http\Controllers\Maintenance;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProductValidate;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\IncidenceCategory;

class ProductController extends Controller
{
    public function index(){
        return Product::query()
        ->when(request('query'), function($query, $searchQuery) {
            $query->where('products.description', 'like', "%{$searchQuery}%");
        })
        ->select('products.id','categories.description as cat','products.description')
        ->join('categories', 'products.category_id', '=', 'categories.id')
        ->orderBy('products.created_at', 'desc')
        ->paginate(setting('pagination_limit'));
    }

    public function getCategories(){
        return IncidenceCategory::latest()->get();
    }

    public function store(ProductValidate $request){
        $validated = $request->validated();
    
        Product::create([
            'category_id' => $validated['category_id'],
            'description' => $validated['description'],
            'detail' => $validated['detail'],
            'stock' => $validated['stock'],
        ]);

        return Product::select('products.id','categories.description as cat','products.description')->join('categories', 'products.category_id', '=', 'categories.id')->orderBy('id', 'desc')->first();
    }

    public function edit(Product $product){
        return $product;
    }
    
    public function update(ProductValidate $request, Product $product) {
        $validated = $request->validated();

        $product->update([
            'category_id'   => $validated['category_id'],
            'description'   => $validated['description'],
            'detail'        => $validated['detail'],
            'cantidad'      => $validated['stock']
        ]);

        return Product::select('products.id','categories.description as cat','products.description')->join('categories', 'products.category_id', '=', 'categories.id')->where('products.id', '=', $product->id)->first();

    }

    public function destroy(Product $product){
        $product->delete();
        return response()->json(['success' => true, 'message' => 'Eliminado exitosamente'], 200);
    }
}