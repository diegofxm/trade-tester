<?php

namespace App\Http\Controllers\Myfxbook;

use App\Models\Trade;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class TradeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Trade::with('account')->get();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'account_id' => 'required|exists:accounts,id',
            'ticket' => 'required|unique:trades,ticket',
            'symbol' => 'required|string',
            'type' => 'required|in:buy,sell',
            'lots' => 'required|numeric',
            'open_time' => 'required|date',
            'close_time' => 'nullable|date',
            'open_price' => 'required|numeric',
            'close_price' => 'nullable|numeric',
            'profit' => 'numeric',
            'swap' => 'numeric',
            'commission' => 'numeric',
            'magic_number' => 'nullable|integer',
        ]);

        return Trade::create($validated);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        return Trade::with('account')->findOrFail($id);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $trade = Trade::findOrFail($id);

        $validated = $request->validate([
            'symbol' => 'string',
            'type' => 'in:buy,sell',
            'lots' => 'numeric',
            'open_time' => 'date',
            'close_time' => 'nullable|date',
            'open_price' => 'numeric',
            'close_price' => 'nullable|numeric',
            'profit' => 'numeric',
            'swap' => 'numeric',
            'commission' => 'numeric',
            'magic_number' => 'nullable|integer',
        ]);

        $trade->update($validated);

        return $trade;
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $trade = Trade::findOrFail($id);
        $trade->delete();

        return response()->json(['message' => 'Trade deleted.']);
    }
}
