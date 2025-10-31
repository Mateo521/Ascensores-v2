<?php
// app/Http/Controllers/PublicController.php
namespace App\Http\Controllers;

use App\Models\Elevator;
use Inertia\Inertia;

class PublicController extends Controller
{
    public function show(string $token)
    {
        $elevator = Elevator::where('public_token', $token)->firstOrFail();
        $year = request()->query('year', now()->year);
        
        $revisions =$elevator->revisions()
            ->where('year', $year)
            ->get()
            ->keyBy('month')
            ->map(function ($revision) {
                return [
                    'checked' => $revision->checked,
                    'checked_at' => $revision->checked_at
                ];
            });
        
        $byMonth = [];
        for ($m = 1; $m <= 12; $m++) {
            $byMonth[$m] = $revisions->get($m, [
                'checked' => false,
                'checked_at' => null
            ]);
        }
        
        return Inertia::render('Public/Elevator', [
            'elevator' => $elevator->only('designation', 'address', 'public_token'),
            'year' => (int) $year,
            'revisions' => $byMonth
        ]);
    }
}
