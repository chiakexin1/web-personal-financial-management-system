<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TransactionController extends Controller
{
    public function index(Request $request)
    {
        $query = Auth::user()->transactions()->latest();

        if ($request->filled('type') && in_array($request->type, ['income', 'expense'])) {
            $query->where('type', $request->type);
        }

        if ($request->filled('search')) {
            $query->where('title', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('date')) {
            $query->whereDate('date', $request->date);
        }

        $transactions = $query->paginate(10)->withQueryString();

        $income  = Auth::user()->transactions()->where('type', 'income')->sum('amount');
        $expense = Auth::user()->transactions()->where('type', 'expense')->sum('amount');
        $balance = $income - $expense;

        return view('transactions.index', compact('transactions', 'income', 'expense', 'balance'));
    }

    public function create()
    {
        return view('transactions.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title'       => 'required|string|max:255',
            'amount'      => 'required|numeric|min:0.01|max:9999999.99',
            'type'        => 'required|in:income,expense',
            'description' => 'nullable|string|max:500',
            'date'        => 'required|date|before_or_equal:today',
        ]);

        Auth::user()->transactions()->create($validated);

        if ($request->has('from_dashboard')) {
            return redirect()->route('dashboard')
                ->with('success', 'Transaction added successfully!');
        }

        return redirect()->route('transactions.index')
            ->with('success', 'Transaction added successfully!');
    }

    public function edit($id)
    {
        $transaction = Auth::user()->transactions()->findOrFail($id);
        return view('transactions.edit', compact('transaction'));
    }

    public function update(Request $request, $id)
    {
        $transaction = Auth::user()->transactions()->findOrFail($id);

        $validated = $request->validate([
            'title'       => 'required|string|max:255',
            'amount'      => 'required|numeric|min:0.01|max:9999999.99',
            'type'        => 'required|in:income,expense',
            'description' => 'nullable|string|max:500',
            'date'        => 'required|date|before_or_equal:today',
        ]);

        $transaction->update($validated);

        return redirect()->route('transactions.index')
            ->with('success', 'Transaction updated successfully!');
    }

    public function destroy($id)
    {
        $transaction = Auth::user()->transactions()->findOrFail($id);
        $transaction->delete();

        return redirect()->route('transactions.index')
            ->with('success', 'Transaction deleted successfully!');
    }

    public function dashboard()
    {
        $transactions = Auth::user()->transactions()->latest()->get();

        $income  = $transactions->where('type', 'income')->sum('amount');
        $expense = $transactions->where('type', 'expense')->sum('amount');
        $balance = $income - $expense;
        $recent  = $transactions->take(5);

        return view('dashboard', compact('transactions', 'income', 'expense', 'balance', 'recent'));
    }
}