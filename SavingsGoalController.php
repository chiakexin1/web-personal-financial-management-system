<?php

namespace App\Http\Controllers;

use App\Models\Budget;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BudgetController extends Controller
{
    public function index()
    {
        $budgets = Budget::with('category')
            ->where('user_id', Auth::id())
            ->latest()
            ->get();

        return view('budgets.index', compact('budgets'));
    }

    public function create()
    {
        $categories = Category::where('user_id', Auth::id())
            ->where('type', 'Expense')
            ->orderBy('name')
            ->get();

        return view('budgets.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'category_id' => ['required', 'exists:categories,id'],
            'month' => ['required', 'string', 'max:20'],
            'limit_amount' => ['required', 'numeric', 'min:0.01'],
            'spent_amount' => ['nullable', 'numeric', 'min:0'],
        ]);

        $category = Category::findOrFail($validated['category_id']);
        abort_if($category->user_id !== Auth::id(), 403);

        $validated['user_id'] = Auth::id();
        $validated['spent_amount'] = $validated['spent_amount'] ?? 0;

        Budget::create($validated);

        return redirect()->route('budgets.index')->with('success', 'Budget created successfully.');
    }

    public function edit(Budget $budget)
    {
        abort_if($budget->user_id !== Auth::id(), 403);

        $categories = Category::where('user_id', Auth::id())
            ->where('type', 'Expense')
            ->orderBy('name')
            ->get();

        return view('budgets.edit', compact('budget', 'categories'));
    }

    public function update(Request $request, Budget $budget)
    {
        abort_if($budget->user_id !== Auth::id(), 403);

        $validated = $request->validate([
            'category_id' => ['required', 'exists:categories,id'],
            'month' => ['required', 'string', 'max:20'],
            'limit_amount' => ['required', 'numeric', 'min:0.01'],
            'spent_amount' => ['required', 'numeric', 'min:0'],
        ]);

        $category = Category::findOrFail($validated['category_id']);
        abort_if($category->user_id !== Auth::id(), 403);

        $budget->update($validated);

        return redirect()->route('budgets.index')->with('success', 'Budget updated successfully.');
    }

    public function destroy(Budget $budget)
    {
        abort_if($budget->user_id !== Auth::id(), 403);

        $budget->delete();

        return redirect()->route('budgets.index')->with('success', 'Budget deleted successfully.');
    }
}