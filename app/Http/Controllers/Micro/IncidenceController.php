<?php

namespace App\Http\Controllers\Micro;
use App\Http\Controllers\Controller;
use App\Http\Requests\IncidenceValidate;
use App\Http\Resources\IncidenceResource;
use App\Http\Resources\StaffResource;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\Incidence;
use App\Models\IncidenceCategory;
use App\Models\Staff;
use App\Models\DetailIncidenceApps;

class IncidenceController extends Controller {
    
    public function index(Request $request){
        $data = Incidence::where(function ($q) use ($request) {
            if($request->b_date) $q->where('created_at', '>=', $request->b_date);
            if($request->e_date) $q->where('created_at', '<=', $request->e_date);
        })
        ->select('incidence.id as id', DB::raw('DATE_FORMAT(incidence.created_at, "%d-%b-%Y") as created'), 's.names as names', 'ic.aka as category', 'it.description as type')
        ->join('staff as s', 's.id', '=', 'incidence.staffId')
        ->join('incidence_category as ic', 'ic.id', '=', 'incidence.incidenceCategoryId')
        ->join('incidence_type as it', 'it.id', '=', 'incidence.typeIncidenceId')
        ->orderBy('id', 'desc')
        ->get();
        
        return response()->json($data);
    }

    public function getStaff(){
        $data = StaffResource::collection(
            Staff::query()
            ->when(request('query'), function($query, $searchQuery) {
                $query->where('name', 'like', "%{$searchQuery}%");
            })
            ->latest()
            ->get()
        );

        return response()->json($data);
    }

    public function getIncidenceCategory(){
        $data = IncidenceCategory::get();
        return response()->json($data);
    }

    public function getIncidenceType(){
        return DB::table('incidence_type')->get();
    }

    public function store(IncidenceValidate $request){
        $validated = $request->validated();

        $incidence = new Incidence();
        $incidence->staffId             = $validated['staffId'];
        $incidence->incidenceCategoryId = $validated['incidenceCategoryId'];
        $incidence->typeIncidenceId     = $validated['typeIncidenceId'];
        $incidence->description         = $validated['description'];
        $incidence->solution            = $validated['solution'];
        if (!empty($validated['created_at'])) {
            $incidence->created_at = $validated['created_at'] . ' ' . now()->format('H:i:s');
        } else {
            $incidence->created_at = now();
        }
        $incidence->save();

        foreach($request->selectedItems as $detail){
            $detailApp = new DetailIncidenceApps();
            $detailApp->incidenceId     = $incidence->id;
            $detailApp->applicationId   = $detail['id'];
            $detailApp->save();
        }

        return response()->json(['success' => true, 'message' => 'Guardado exitosamente'], 200);
    }

    public function edit(Incidence $incidence){
        $rowIncidence       = IncidenceResource::make($incidence);
        $rowIncidenceUser   = Incidence::selectRaw('staff.names user')
            ->join('staff', 'incidence.staffId', '=', 'staff.id')
            ->where('incidence.id', $incidence->id)
            ->get();
        $appsIncidence      = DetailIncidenceApps::select('a.name', 'a.id')->join('applications as a', 'detail_incidence_apps.applicationId', '=', 'a.id')->where('incidenceId', $incidence->id)->get();
        return response()->json([
            'incidence' => $rowIncidence,
            'user'      => $rowIncidenceUser,
            'apps'      => $appsIncidence,
        ]);
    }

    public function getIncidenceByUser(Incidence $incidence){
        $data = Incidence::where('staffId', $incidence->staffId)->get();
        return response()->json($data);
    }

    public function update(Incidence $incidence, IncidenceValidate $request){
        $validated = $request->validated();
        $incidence->update($validated);
        return response()->json(['success' => true, 'message' => 'Actualizado exitosamente'], 200);
    }

    public function destroy($id){
        $incidence = Incidence::find($id);
        if ($incidence) {
            $incidence->delete();
            return response()->json(['success' => true, 'message' => 'Eliminado exitosamente'], 200);
        }else{
            return response()->json(['success' => true, 'message' => 'Algo salió mal']);
        }
    }
}