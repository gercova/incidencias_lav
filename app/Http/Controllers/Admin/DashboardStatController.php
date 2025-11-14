<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Incidence;
use Illuminate\Http\Request;

class DashboardStatController extends Controller {

    public function incidences(){
        $totalIncidencesCount = Incidence::query()
            ->when(request('date_range') === 'today', function ($query) {
                $query->whereBetween('created_at', [now()->today(), now()]);
            })
            ->when(request('date_range') === '30_days', function ($query) {
                $query->whereBetween('created_at', [now()->subDays(30), now()]);
            })
            ->when(request('date_range') === '60_days', function ($query) {
                $query->whereBetween('created_at', [now()->subDays(60), now()]);
            })
            ->when(request('date_range') === '360_days', function ($query) {
                $query->whereBetween('created_at', [now()->subDays(360), now()]);
            })
            ->when(request('date_range') === 'month_to_date', function ($query) {
                $query->whereBetween('created_at', [now()->firstOfMonth(), now()]);
            })
            ->when(request('date_range') === 'year_to_date', function ($query) {
                $query->whereBetween('created_at', [now()->firstOfYear(), now()]);
            })
            ->count();

        return response()->json([
            'totalIncidencesCount' => (int) $totalIncidencesCount,
        ]);
    }

    public function countTypes(Request $request){
        if($request->type !== 'todos'){
            $totalTypesCount = Incidence::query()
                ->when(request('type'), function ($q) {
                    $q->where('typeIncidenceId', '=', request('type'));
                })
                ->count();
        }else{
            $totalTypesCount = Incidence::all()->count();
        }

        return response()->json([
            'totalTypesCount' => (int) $totalTypesCount,
        ]);
    }

    public function countCategories(Request $request){
        if($request->category !== 'todos'){
            $totalCategoriesCount = Incidence::query()
                ->when(request('category'), function ($q) {
                    $q->where('incidenceCategoryId', '=', request('category'));
                })
                ->count();
        }else{
            $totalCategoriesCount = Incidence::all()->count();
        }

        return response()->json([
            'totalCategoriesCount' => $totalCategoriesCount,
        ]);
    }

    // Chart bar
    /*public function incidencesByMonth(Request $request){
        $incidences = Incidence::where(function ($q) use ($request) {
            if($request->filled('start_date')) $q->where('created_at', '>=', request('start_date'));
            if($request->filled('end_date')) $q->where('created_at', '<=', request('end_date'));
            if($request->selectedCategory !== 'TODO') $q->where('incidenceCategoryId', request('selectedCategory'));
            if($request->selectedType !== 'TODO') $q->where('typeIncidenceId', request('selectedType'));
        })
        ->selectRaw('EXTRACT(MONTH FROM created_at) as month, COUNT(*) as count')
        ->groupBy('month')
        ->orderBy('month')
        ->get();

        $data = [[
            'name' => 'Incidencias',
            'data' => array_fill(0, 12, 0) // Inicializa un array con 12 ceros (uno por cada mes)
        ]];

        foreach ($incidences as $incidence) {
            $data[0]['data'][$incidence->month - 1] = $incidence->count;
        }

        return $data;
    }*/
    
    public function incidencesByMonth(Request $request){
        $incidences = Incidence::where(function ($q) use ($request) {
            if($request->filled('start_date')) $q->where('created_at', '>=', request('start_date'));
            if($request->filled('end_date')) $q->where('created_at', '<=', request('end_date'));
            if($request->selectedCategory !== 'TODO') $q->where('incidenceCategoryId', request('selectedCategory'));
            if($request->selectedType !== 'TODO') $q->where('typeIncidenceId', request('selectedType'));
        })
        ->selectRaw('EXTRACT(MONTH FROM created_at) as month, COUNT(*) as count')
        ->groupBy('month')
        ->orderBy('month')
        ->get();

        $labels = ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'];
        $data = array_fill(0, 12, 0);

        foreach ($incidences as $incidence) {
            $data[$incidence->month - 1] = (int) $incidence->count;
        }

        return response()->json([
            [
                'name' => 'Incidencias',
                'data' => $data
            ]
        ]);
    }

    // Pie Charts
    public function incidencesByLevels(Request $request){
        $data = Incidence::where(function ($q) use ($request) {
            if($request->filled('start_date')) $q->where('created_at', '>=', request('start_date'));
            if($request->filled('end_date')) $q->where('created_at', '<=', request('end_date'));
            if($request->selectedCategory !== 'TODO') $q->where('incidenceCategoryId', request('selectedCategory'));
            if($request->selectedType !== 'TODO') $q->where('typeIncidenceId', request('selectedType'));
        })
        ->selectRaw('it.description as category, COUNT(incidence.id) as count')
        ->join('incidence_type as it', 'incidence.typeIncidenceId', '=', 'it.id')
        ->groupBy('category')
        ->having('count', '>', 0)
        ->orderBy('count', 'desc')
        ->get();
        
        $formattedData = $data->map(function ($item) {
            return [
                'name'  => $item->category,
                'y'     => (int) $item->count
            ];
        });

        return response()->json($formattedData);
    }

    // Pie Chart
    public function incidenceByCategories(Request $request){
        $data = Incidence::where(function ($q) use ($request) {
            if($request->filled('start_date')) $q->where('created_at', '>=', request('start_date'));
            if($request->filled('end_date')) $q->where('created_at', '<=', request('end_date'));
            if($request->selectedType !== 'TODO') $q->where('typeIncidenceId', request('selectedType'));
            if($request->selectedCategory !== 'TODO') $q->where('incidenceCategoryId', request('selectedCategory'));
        })
        ->selectRaw('ic.description as category, COUNT(incidence.id) as count')
        ->join('incidence_category as ic', 'ic.id', '=', 'incidenceCategoryId')
        ->groupBy('category')
        ->having('count', '>', 0)
        ->orderBy('count', 'desc')
        ->get();

        $formattedData = $data->map( function ($item) {
            return [
                'name'  => $item->category,
                'y'     => (int) $item->count
            ];
        });

        return response()->json($formattedData);
    }

    // Bar Chart
    public function incidencesbyUsers(Request $request){
        $data = Incidence::where(function ($q) use ($request) {
            if($request->filled('start_date')) $q->where('incidence.created_at', '>=', request('start_date'));
            if($request->filled('end_date')) $q->where('incidence.created_at', '<=', request('end_date'));
            if($request->selectedCategory !== 'TODO') $q->where('incidenceCategoryId', request('selectedCategory'));
            if($request->selectedType !== 'TODO') $q->where('typeIncidenceId', request('selectedType'));
        })
        ->selectRaw('s.names as user, COUNT(incidence.id) as total')
        ->join('staff as s', 'incidence.staffId', '=', 's.id')
        ->groupBy('user')
        ->orderBy('total', 'desc')
        ->take(15)
        ->get();
        
        $formattedData = $data->map(function ($item) {
            return [
                'name'      => $item->user,
                'y'         => (int) $item->total,
                'drilldown' => $item->user
            ];
        });

        $drilldownData = []; # Añade aquí la lógica para datos de drilldown si es necesario

        return response()->json([
            'data'      => $formattedData,
            'drilldown' => $drilldownData
        ]);
    }

    // Bar Chart
    public function incidencebyAreas(Request $request){
        $data = Incidence::where(function ($q) use ($request) {
            if($request->filled('start_date')) $q->where('incidence.created_at', '>=', request('start_date'));
            if($request->filled('end_date')) $q->where('incidence.created_at', '<=', request('end_date'));
            if($request->selectedCategory !== 'TODO') $q->where('incidenceCategoryId', request('selectedCategory'));
            if($request->selectedType !== 'TODO') $q->where('typeIncidenceId', request('selectedType'));
        })
        ->selectRaw('s.direction, COUNT(incidence.id) as total')
        ->join('staff as s', 'incidence.staffId', '=', 's.id')
        ->groupBy('direction')
        ->having('total', '>', 0)
        ->orderBy('total', 'desc')
        ->take(15)
        ->get();

        $formattedData = $data->map(function ($item) {
            return [
                'name'      => $item->direction,
                'y'         => (int) $item->total,
                'drilldown' => $item->direction
            ];
        });

        $drilldownData = [];

        return response()->json([
            'data'      => $formattedData,
            'drilldown' => $drilldownData
        ]);
    }
}
