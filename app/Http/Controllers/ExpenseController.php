<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use Illuminate\Http\Request;

class ExpenseController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'expense_date' => 'required|date',
            'description'  => 'required|string|max:255',
            'category'     => 'required|string',
            'amount'       => 'required|numeric|min:0',
            'is_income'    => 'nullable|boolean',
        ]);

        // 🔥 income = valor positivo | expense = valor negativo
        $amount = $request->boolean('is_income')
            ? abs($request->amount)     // ENTRADA
            : -abs($request->amount);   // SAÍDA

        Expense::create([
            'user_id'      => auth()->id(),
            'expense_date' => $request->expense_date,
            'description'  => $request->description,
            'category'     => $request->category,
            'amount'       => $amount,
        ]);

        return redirect()->back()
            ->with('success', __('ui.expense_created_success'));
    }
}
