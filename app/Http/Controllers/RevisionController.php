<?php

namespace App\Http\Controllers;

use App\Models\Elevator;
use App\Models\Revision;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class RevisionController extends Controller
{
    public function bulkUpdate(Request $request, Elevator $elevator)
    {
        $validated =$request->validate([
            'year' => 'required|integer|min:2000|max:2100',
            'months' => 'required|array',
            'months.*' => 'boolean'
        ]);
        
        $technicianId = Auth::id();
        $now = Carbon::now();

        foreach ($validated['months'] as $monthNumber => $isChecked) {
            $month = (int)$monthNumber;
            
            if ($month < 1 || $month > 12) {
                continue;
            }

            $revision = Revision::firstOrNew([
                'elevator_id' => $elevator->id,
                'year' => $validated['year'],
                'month' => $month
            ]);
            
            $revision->checked = (bool) $isChecked;
            $revision->technician_id = $technicianId;
            $revision->checked_at = $isChecked ? $now : null;
            $revision->save();
        }

        return redirect()->back();
    }
}
