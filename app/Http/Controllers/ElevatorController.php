<?php
// app/Http/Controllers/ElevatorController.php
namespace App\Http\Controllers;

use App\Models\Elevator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

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

    public function store(Request $req)
    {
        $data = $req->validate([
            'designation' => 'required|string|max:255',
            'address' => 'nullable|string|max:255',
            'is_active' => 'boolean'
        ]);
        $data['created_by'] = Auth::id();
        Elevator::create($data);

        return redirect()->route('elevators.index');
    }

    public function update(Request $req, Elevator $elevator)
    {
        $data = $req->validate([
            'designation' => 'string|max:255',
            'address' => 'nullable|string|max:255',
            'is_active' => 'boolean'
        ]);
        $elevator->update($data);

        return redirect()->back();
    }

    public function destroy(Elevator $elevator)
    {
        $elevator->delete();
        return redirect()->route('elevators.index');
    }
}
