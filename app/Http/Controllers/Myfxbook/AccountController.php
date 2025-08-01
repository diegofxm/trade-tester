<?php

namespace App\Http\Controllers\Myfxbook;

use App\Models\Account;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AccountController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Account::withCount('trades')->get();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string',
            'broker_name' => 'nullable|string',
            'platform' => 'required|in:MT4,MT5',
            'currency' => 'required|string|max:3',
            'initial_balance' => 'required|numeric',
            'is_public' => 'boolean',
        ]);

        return Account::create([
            ...$validated,
            'user_id' => $request->user()->id,
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        return Account::with(['trades', 'dailySummaries'])->findOrFail($id);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $account = Account::findOrFail($id);

        $validated = $request->validate([
            'name' => 'sometimes|required|string',
            'broker_name' => 'nullable|string',
            'platform' => 'in:MT4,MT5',
            'currency' => 'string|max:3',
            'initial_balance' => 'numeric',
            'is_public' => 'boolean',
        ]);

        $account->update($validated);

        return $account;
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $account = Account::findOrFail($id);
        $account->delete();

        return response()->json(['message' => 'Account deleted.']);
    }
}
