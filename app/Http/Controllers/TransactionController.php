<?php

namespace App\Http\Controllers;

use App\Models\transactions;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    public function createTransaction(Request $request): JsonResponse {
        $validateTransaction = $request->validate([
            'wallet_id' => 'required|exists:wallets,id',
            'category_id' => 'required|exists:categories,id',
            'amount' => 'required|integer|min:1',
            'date' => 'required|date_format:Y-m-d',
        ], [
            'wallet_id.required' => 'The wallet id is required.',
            'category_id.required' => 'The category id is required.',
            'amount.required' => 'The amount is required.',
            'date.required' => 'The date id is required.',
            'wallet_id.exists:wallets,id' => 'The selected wallet id invalid.',
            'category_id.exists:wallets,id' => 'The selected category id invalid.',
            'amount.min' => 'The amount minimum 1',
            'date.date_format' => 'The date must be in YYYY-MM-DD format.',
            'amount.integer' => 'The amount must be integer format.'
        ]);

        $validateTransaction["note"] = $request->note;

        $transaction = transactions::create($validateTransaction);

        return response()->json([
            'status' => 'success',
            'message' => 'Transaction added successful',
            'data' => $transaction
        ]);
    }

    public function deleteTransaction(Request $request, $transactionId): JsonResponse {
        $transaction = transactions::where('id', $transactionId)->with('wallet')->first();

        if(!$transaction){
            return response()->json([
                'status' => 'error',
                'message' => 'Not found'
            ], 404);
        }else if($transaction->wallet()->value('user_id') !== $request->user()->id){
            return response()->json([
                'status' => 'error',
                'message' => 'Forbidden access'
            ], 403);
        }

        $transaction->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Transaction deleted successful'
        ]);
    }

    public function allTransaction(Request $request): JsonResponse {
        $request->validate([
            'page' => 'nullable|integer|min:1',
            'per_page' => 'nullable|integer|min:25',
            'month' => 'nullable|integer|min:1|max:12',
            'year' => 'nullable|integer'
        ]);

        $transactions = transactions::whereHas('wallet', fn($e) => $e->where('user_id', $request->user()->id))
            ->when($request->filled('month'), fn($q) => $q->whereMonth('date', $request->month))
            ->when($request->filled('year'), fn($q) => $q->whereYear('date', $request->year))
            ->with(['category', 'wallet'])
            ->latest()
            ->paginate($request->integer('per_page', 25), ['*'], 'page', $request->integer('page', 1))
            ->through(fn($item) => $item->makeVisible('wallet'));
        
        return response()->json([
            'status' => 'success',
            'data' => $transactions
        ]); 
    }
}
