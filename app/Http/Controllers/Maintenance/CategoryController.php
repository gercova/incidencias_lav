<?php

namespace App\Http\Controllers\Maintenance;
use App\Http\Controllers\Controller;
use App\Http\Requests\CategoryValidate;
use App\Models\IncidenceCategory;

class CategoryController extends Controller
{
    public function index(){
        return IncidenceCategory::query()->when(request('query'), function($q, $searchQuery){
            $q->where('description', 'like', "%{$searchQuery}%");
            $q->where('aka', 'like', "%{$searchQuery}%");
        })->paginate(setting('pagination_limit'));
    }

    public function store(CategoryValidate $request){
        $validated = $request->validated();
        
        $category = IncidenceCategory::create([
            'description'   => $validated['description'],
            'aka'           => $validated['aka'],
        ]);

        return response()->json([
            'category'  => $category,
            'success'   => true,
            'message'   => 'Añadido exitosamente',
        ], 200);
    }

    public function edit(IncidenceCategory $category){
        return $category;
    }

    public function update(IncidenceCategory $category, CategoryValidate $request){
        $validated = $request->validated();
        
        $category->update([
            'description'   => $validated['description'],
            'aka'           => $validated['aka'],
        ]);

        return response()->json([
            'category'  => $category,
            'success'   => true,
            'message'   => 'Actualizado exitosamente'
        ], 200);
    }

    public function destroy(IncidenceCategory $category){
        $category->delete();
        return response()->noContent();
    }
}
