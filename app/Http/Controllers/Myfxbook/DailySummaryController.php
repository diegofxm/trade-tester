<?php

namespace App\Http\Controllers\Myfxbook;

use App\Models\DailySummary;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DailySummaryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return DailySummary::with('account')->get();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'account_id' => 'required|exists:accounts,id',
            'date' => 'required|date',
            'balance' => 'required|numeric',
            'equity' => 'required|numeric',
            'drawdown' => 'numeric',
            'deposits' => 'numeric',
            'withdrawals' => 'numeric',
        ]);

        $summary = DailySummary::updateOrCreate(
            [
                'account_id' => $validated['account_id'],
                'date' => $validated['date']
            ],
            $validated
        );

        return $summary;
    }


    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        return DailySummary::with('account')->findOrFail($id);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $summary = DailySummary::findOrFail($id);

        $validated = $request->validate([
            'date' => 'date',
            'balance' => 'numeric',
            'equity' => 'numeric',
            'drawdown' => 'numeric',
            'deposits' => 'numeric',
            'withdrawals' => 'numeric',
        ]);

        $summary->update($validated);

        return $summary;
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $summary = DailySummary::findOrFail($id);
        $summary->delete();

        return response()->json(['message' => 'Summary deleted.']);
    }
}
