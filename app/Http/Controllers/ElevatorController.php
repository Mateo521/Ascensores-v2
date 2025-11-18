<?php
// app/Http/Controllers/ElevatorController.php
namespace App\Http\Controllers;

use App\Models\Elevator;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;

class ElevatorController extends Controller
{
    public function index(\Illuminate\Http\Request $request)
    {
        $perPage = (int) $request->query('per_page', 10); // por defecto 10 por página
        $elevators = \App\Models\Elevator::orderBy('designation')
            ->paginate($perPage)
            ->withQueryString();

        return \Inertia\Inertia::render('Elevators/Index', [
            'elevators' => $elevators,
            'filters'   => [
                'per_page' => $perPage,
            ],
        ]);
    }


    public function show(Elevator $elevator)
    {
        $year = request()->query('year', now()->year);

        // Cargar revisiones del año
        $revisions = $elevator->revisions()
            ->where('year', $year)
            ->get()
            ->keyBy('month')
            ->map(fn($r) => [
                'checked' => $r->checked,
                'checked_at' => $r->checked_at,
                'notes' => $r->notes
            ]);

        // Rellenar meses faltantes
        $byMonth = [];
        for ($m = 1; $m <= 12; $m++) {
            $byMonth[$m] = $revisions->get($m, [
                'checked' => false,
                'checked_at' => null,
                'notes' => null
            ]);
        }



        return Inertia::render('Elevators/Show', [
            'elevator' => $elevator,
            'year' => (int)$year,
            'revisions' => $byMonth,
            'publicUrl' => url("/p/{$elevator->public_token}")
        ]);
    }

    public function getRevisions(\App\Models\Elevator $elevator, Request $request)
    {
        $year = $request->query('year', now()->year);
        $month = $request->query('month', now()->month);

        $revision = $elevator->revisions()
            ->where('year', $year)
            ->where('month', $month)
            ->first();

        return response()->json([
            'revision' => $revision ? [
                'id' => $revision->id,
                'year' => $revision->year,
                'month' => $revision->month,
                'checked' => (bool) $revision->checked,
                'checked_at' => $revision->checked_at?->locale('es')->isoFormat('D [de] MMMM YYYY HH:mm'),
                'notes' => $revision->notes,
            ] : null,
            'year' => (int) $year,
            'month' => (int) $month,
        ]);
    }


    public function store(Request $request)
    {
        $validated = $request->validate([
            'designation' => 'required|string|max:255',
            'address'     => 'nullable|string|max:500',
            'max_capacity_kg' => 'nullable|integer|min:0|max:100000',
            'comments'    => 'nullable|string|max:1000',
        ]);

        $validated['public_token'] = \Illuminate\Support\Str::random(32);
        $validated['created_by'] = Auth::id(); //

        Elevator::create($validated);

        return redirect()->route('elevators.index')->with('success', 'Ascensor creado con éxito.');
    }


    public function edit(Elevator $elevator)
    {
        return Inertia::render('Elevators/Edit', [
            'elevator' => $elevator
        ]);
    }

    public function update(Request $request, Elevator $elevator)
    {
        $validated = $request->validate([
            'designation' => 'required|string|max:255',
            'address'     => 'nullable|string|max:500',
            'max_capacity_kg' => 'nullable|integer|min:0|max:100000',
            'comments'    => 'nullable|string|max:1000',
        ]);
        // Opcional: trackear quién actualizó
        // $validated['updated_by'] = auth()->id();
        $elevator->update($validated);

        return redirect()->route('elevators.index')->with('success', 'Ascensor actualizado.');
    }
    public function destroy(\App\Models\Elevator $elevator)
    {
        $elevator->delete();
        return redirect()
            ->route('elevators.index')
            ->with('success', 'Ascensor eliminado correctamente.');
    }
}
