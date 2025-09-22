<?php

namespace App\Http\Controllers\Micro;
use App\Http\Controllers\Controller;
use App\Http\Requests\ApplicationValidate;
use App\Models\Application;
use App\Http\Resources\ApplicationsResource;

class ApplicationController extends Controller
{
    public function index(){
        return Application::query()
            ->when(request('query'), function ($q, $searchQuery) {
                $q->where('name', 'like', "%{$searchQuery}%");
                $q->orWhere('description', 'like', "%{$searchQuery}%");
            })
            ->latest()
            ->paginate(setting('pagination_limit'));
    }

    public function store(ApplicationValidate $request){
        $validated = $request->validated();
        $item = Application::create([
            'name'          => $validated['name'],
            'description'   => $validated['description'],
        ]);

        return response()->json(['success' => true, 'item' => $item, 'message' => 'Guardado exitosamente'], 200);
    }

    public function edit(Application $application){
        return $application;
    }

    public function update(ApplicationValidate $request, Application $application){
        $validated = $request->validated();
        $application->update($validated);
        $item = Application::find($application->id);
        
        return response()->json(['item' => $item, 'success' => true, 'message' => 'Actualizado exitosamente'], 200);
    }

    public function getApps(){
        $data = ApplicationsResource::collection(Application::get());
        return response()->json($data);
    }

    public function destroy(Application $application){
        $application->delete();
        return response()->json(['status' => true, 'message' => 'Eliminado exitosamente'], 200);
    }
}
