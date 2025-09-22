<?php

namespace App\Http\Controllers;
use App\Http\Requests\StaffValidate;
use Illuminate\Http\Request;
use App\Models\Staff;
use App\Http\Resources\SearchStaffResource;

class StaffController extends Controller
{
    public function index(){
        $data = Staff::query()
            ->when(request('query'), function($query, $searchQuery) {
                $query->where('name', 'like', "%{$searchQuery}%");
            })
            ->latest()
            ->get();
        return response()->json($data);
    }

    public function search(Request $request){
        $query = $request->input('query');
        $items = SearchStaffResource::collection(Staff::where('names', 'like', "%$query%")->get());
        return response()->json($items);
    }

    public function store(StaffValidate $request){
        $validated = $request->validated();
        Staff::create([
            'dni'                   => $validated['dni'],
            'names'                 => $validated['names'],
            'society'               => $validated['society'],
            'area'                  => $validated['area'],
            'position_description'  => $validated['position_description'],
            'direction'             => $validated['direction'],
            'organizational_unit'   => $validated['organizational_unit'],
            'start_date'            => $validated['start_date'],
        ]);

        return response()->json(['success' => true, 'message' => 'Añadido exitosamente'], 200);
    }

    public function edit(Staff $staff){
        return $staff;
    }

    public function update(Staff $staff, StaffValidate $request){
        $validated = $request->validated();
        $staff->update($validated);
        return response()->json(['success' => true, 'message' => 'Actualizado exitosamente'], 200);
    }

    public function destroy(Staff $staff){
        $staff->delete();
        return response()->json([
            'success' => true, 
            'message' => 'Eliminado exitosamente'
        ], 200);
    }
}
