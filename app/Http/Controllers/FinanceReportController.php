<?php

namespace App\Http\Controllers;

use App\Models\categories;
use App\Models\transactions;
use Illuminate\Http\Request;

class FinanceReportController extends Controller
{
    public function expense(Request $request){
        $request->validate([
            'month' => 'integer|min:1|max:12',
            'year' => 'integer'
        ]);

        $summary = transactions::query()
                ->whereHas('wallet', fn($q) => $q->where('user_id', $request->user()->id))
                ->whereHas('category', fn($q) => $q->where('type', 'expense'))
                ->when($request->filled('month'), fn($q) => $q->whereMonth('date', $request->month))
                ->when($request->filled('year'), fn($q) => $q->whereYear('date', $request->year))
                ->selectRaw('category_id, SUM(amount) as total')
                ->groupBy('category_id')
                ->with('category')
                ->get()
                ->map(fn($transaction) => [
                    'category' => $transaction->category,
                    'amout' => (float) $transaction->total
                ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Get summary by income category successful',
            'data' => $summary
        ]);
    }

    public function income(Request $request){
        $request->validate([
            'month' => 'integer|min:1|max:12',
            'year' => 'integer'
        ]);

        $summary = transactions::query()
                ->whereHas('wallet', fn($q) => $q->where('user_id', $request->user()->id))
                ->whereHas('category', fn($q) => $q->where('type', 'income'))
                ->when($request->filled('month'), fn($q) => $q->whereMonth('date', $request->month))
                ->when($request->filled('year'), fn($q) => $q->whereYear('date', $request->year))
                ->selectRaw('category_id, SUM(amount) as total')
                ->groupBy('category_id')
                ->with('category')
                ->get()
                ->map(fn($transaction) => [
                    'category' => $transaction->category,
                    'amout' => (float) $transaction->total
                ]);

        // dd($summary);

        return response()->json([
            'status' => 'success',
            'message' => 'Get summary by income category successful',
            'data' => $summary
        ]);
    }
}
