<?php

namespace App\Http\Controllers;

use App\Models\Elevator;
use App\Models\Revision;
use App\Models\Setting;
use Illuminate\Http\Request;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $now = now();
        $year = (int) $request->query('year', $now->year);
        $month = (int) $request->query('month', $now->month);

        $total = Elevator::count();

        $revisadosMes = Revision::whereYear('checked_at', $year)
            ->whereMonth('checked_at', $month)
            ->where('checked', true)
            ->distinct('elevator_id')
            ->count('elevator_id');

        $pendientesMes = max(0, $total - $revisadosMes);

        // Top 10 sin revisar en el mes actual
        $elevatorsCheckedIds = Revision::where('year', $year)
            ->where('month', $month)
            ->where('checked', true)
            ->pluck('elevator_id')
            ->all();

        $pendientesLista = Elevator::query()
            ->when(count($elevatorsCheckedIds) > 0, fn($q) => $q->whereNotIn('id', $elevatorsCheckedIds))
            ->orderBy('designation')
            ->limit(10)
            ->get(['id','designation','address','public_token']);

        // Últimos movimientos (revisiones cambiadas hace poco)
        $ultimos = Revision::with('elevator:id,designation')
            ->orderByDesc('updated_at')
            ->limit(5)
            ->get(['id','elevator_id','year','month','checked','checked_at','updated_at'])
            ->map(function($r){
                return [
                    'id' => $r->id,
                    'elevator' => $r->elevator?->designation,
                    'year' => $r->year,
                    'month' => $r->month,
                    'checked' => (bool)$r->checked,
                    'checked_at' => optional($r->checked_at)->format('d/m/Y H:i'),
                    'updated_at' => $r->updated_at->diffForHumans(),
                ];
            });

        $settings = Setting::first();

        return Inertia::render('Dashboard', [
            'stats' => [
                'total' => $total,
                'revisadosMes' => $revisadosMes,
                'pendientesMes' => $pendientesMes,
                'year' => $year,
                'month' => $month,
            ],
            'pendientes' => $pendientesLista,
            'ultimos' => $ultimos,
            'settings' => [
                'company_name' => $settings?->company_name ?? 'Ascensores Nuevo Cuyo',
                'emergency_phone' => $settings?->emergency_phone ?? '2664555572',
                'logo_path' => $settings?->logo_path,
            ],
        ]);
    }
}
