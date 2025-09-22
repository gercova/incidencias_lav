<?php

namespace App\Models;

use App\Enums\IncidenceStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;

class Incidence extends Model {
    use HasFactory, SoftDeletes;
    protected $table = 'incidence';
    protected $guarded = [];

    public function staff(): BelongsTo {
        return $this->belongsTo(Staff::class);
    }

    protected $dates = ['deleted_at'];

    protected $casts = [
        'status' => IncidenceStatus::class,
    ];

    public function DetailIncidence(): BelongsTo {
        return $this->belongsTo(DetailIncidenceApps::class);
    }

    public function AppsIncidence(): BelongsTo {
        return $this->belongsTo(Application::class);
    }

    /*public function incidencesByMonth(){
        $incidences = Incidence::selectRaw('EXTRACT(MONTH FROM created_at) as month, COUNT(*) as count')
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

    /*public function incidencesByLevels(){
        $data = Incidence::selectRaw('it.description as category, COUNT(incidence.id) as count')
            ->join('incidence_type as it', 'incidence.typeIncidenceId', '=', 'it.id')
            ->groupBy('category')
            ->having('count', '>', 0)
            ->orderBy('count', 'desc')
            ->get();
        
        $formattedData = $data->map(function ($item) {
            return [
                'name'  => $item->category,
                'y'     => $item->count
            ];
        });

        return response()->json($formattedData);
    }*/

    /*public function incidenceByCategories($startDate, $endDate, $level){
        $data = Incidence::query()
            ->when($startDate !== '', function ($q, $startDate) {
                $q->where('created_at', '>=', $startDate);
            })
            ->when($endDate !== '', function ($q, $endDate) {
                $q->where('created_at', '<=', $endDate);
            })
            ->when($level !== 'TODO', function ($q, $level) {
                $q->where('typeIncidenceId', '=', $level);
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
                'y'     => $item->count
            ];
        });

        return response()->json($formattedData);
    }*/

    /*public function incidenceByUsers($startDate, $endDate, $category, $level){
        $data = Incidence::query()
            ->when($startDate, function ($q, $startDate) {
                $q->where('created_at', '>=', $startDate);
            })
            ->when($endDate, function ($q, $endDate) {
                $q->where('created_at', '<=', $endDate);
            })
            ->when($category !== 'TODO', function ($q, $category) {
                $q->where('incidenceCategoryId', '=', $category);
            })
            ->when($level !== 'TODO', function ($q, $level) {
                $q->where('typeIncidenceId', '=', $level);
            })
            ->selectRaw('s.names user, COUNT(incidence.id) total')
            ->join('staff as s', 'incidence.staffId', '=', 's.id')
            ->groupBy('user')
            ->orderBy('total', 'desc')
            ->take(15)
            ->get();
        
        $formattedData = $data->map(function ($item) {
            return [
                'name'      => $item->user,
                'y'         => $item->total,
                'drilldown' => $item->user
            ];
        });

        $drilldownData = []; # Añade aquí la lógica para datos de drilldown si es necesario

        return response()->json([
            'data'      => $formattedData,
            'drilldown' => $drilldownData
        ]);
    }*/
}