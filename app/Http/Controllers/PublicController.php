<?php

namespace App\Http\Controllers;

use App\Models\Elevator;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Carbon\Carbon;

class PublicController extends Controller
{
    public function show(string $token)
    {
        $elevator = Elevator::where('public_token', $token)->firstOrFail();

        $year = request()->query('year', now()->year);

        // Obtener todas las revisiones del año
        $revisions = $elevator->revisions()
            ->where('year', $year)
            ->get()
            ->keyBy('month')
            ->map(function ($revision) {
                return [
                    'id' => $revision->id,
                    'year' => $revision->year,
                    'month' => $revision->month,
                    'checked' => (bool) $revision->checked,
                    'checked_at' => $this->formatDate($revision->checked_at),
                    'notes' => $revision->notes,
                ];
            });

        // Convertir a objeto plano (no colección de Laravel)
        $revisionsArray = $revisions->toArray();

        return Inertia::render('Public/Show', [
            'elevator' => [
                'id' => $elevator->id,
                'designation' => $elevator->designation,
                'address' => $elevator->address,
                'public_token' => $elevator->public_token,
            ],
            'revisions' => (object) $revisionsArray, // ← Convertir a objeto
            'year' => (int) $year,
        ]);
    }


    public function getRevision(Request $request, string $token)
    {
        $elevator = Elevator::where('public_token', $token)->firstOrFail();

        $year = $request->query('year', now()->year);

        // Devolver todas las revisiones del año (para polling)
        $revisions = $elevator->revisions()
            ->where('year', $year)
            ->get()
            ->keyBy('month')
            ->map(function ($revision) {
                return [
                    'id' => $revision->id,
                    'year' => $revision->year,
                    'month' => $revision->month,
                    'checked' => (bool) $revision->checked,
                    'checked_at' => $this->formatDate($revision->checked_at),
                    'notes' => $revision->notes,
                    'updated_at' => $revision->updated_at->timestamp,
                ];
            });

        return response()->json([
            'revisions' => $revisions,
            'year' => (int) $year,
        ]);
    }

    /**
     * Formatea la fecha de forma segura
     */
    private function formatDate($date)
    {
        if (!$date) {
            return null;
        }

        if ($date instanceof \Carbon\Carbon) {
            return $date->locale('es')->isoFormat('D [de] MMMM YYYY HH:mm');
        }

        try {
            return Carbon::parse($date)->locale('es')->isoFormat('D [de] MMMM YYYY HH:mm');
        } catch (\Exception $e) {
            return null;
        }
    }
}
