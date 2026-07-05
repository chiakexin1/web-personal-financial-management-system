<?php

namespace App\Http\Controllers;

use App\Models\SavingsGoal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SavingsGoalController extends Controller
{
    public function index()
    {
        $goals = SavingsGoal::where('user_id', Auth::id())
            ->latest()
            ->get();

        return view('savings-goals.index', compact('goals'));
    }

    public function create()
    {
        return view('savings-goals.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'target_amount' => ['required', 'numeric', 'min:0.01'],
            'current_amount' => ['nullable', 'numeric', 'min:0'],
            'deadline' => ['nullable', 'date'],
        ]);

        $validated['user_id'] = Auth::id();
        $validated['current_amount'] = $validated['current_amount'] ?? 0;

        SavingsGoal::create($validated);

        return redirect()->route('savings-goals.index')->with('success', 'Savings goal created successfully.');
    }

    public function edit(SavingsGoal $savings_goal)
    {
        abort_if($savings_goal->user_id !== Auth::id(), 403);

        return view('savings-goals.edit', ['goal' => $savings_goal]);
    }

    public function update(Request $request, SavingsGoal $savings_goal)
    {
        abort_if($savings_goal->user_id !== Auth::id(), 403);

        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'target_amount' => ['required', 'numeric', 'min:0.01'],
            'current_amount' => ['required', 'numeric', 'min:0'],
            'deadline' => ['nullable', 'date'],
        ]);

        $savings_goal->update($validated);

        return redirect()->route('savings-goals.index')->with('success', 'Savings goal updated successfully.');
    }

    public function destroy(SavingsGoal $savings_goal)
    {
        abort_if($savings_goal->user_id !== Auth::id(), 403);

        $savings_goal->delete();

        return redirect()->route('savings-goals.index')->with('success', 'Savings goal deleted successfully.');
    }
}