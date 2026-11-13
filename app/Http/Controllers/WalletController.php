<?php

namespace App\Http\Controllers;

use App\Models\wallets;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class WalletController extends Controller
{
    public function createWallet(Request $request): JsonResponse {
        $data = $request->validate([
            'name' => 'required',
            'currency_code' => 'required|exists:currencies,id'
        ],[
            'name.required' => 'The name field is required.',
            'currency_code.required' => 'The currency code field is required.',
            'currency_code.exists' => 'The selected currency code is invalid.',
        ]);

        $wallet = wallets::create([
            'user_id' => $request->user()->id,
            'currency_id' => $data['currency_code'],
            'name' => $data['name']
        ]);

        $wallet->currency_code = $wallet->currency()->value('code');

        return response()->json([
            'status' => 'success',
            'message' => 'Wallet added successful',
            'data' => $wallet
        ]);
    }

    public function editWallet(Request $request, $walletId): JsonResponse {
        $data = $request->validate([
            'name' => 'required'
        ]);

        $wallet = wallets::where('id', $walletId)->first();

        if(!$wallet) {
            return response()->json([
                'status' => 'error',
                'message' => 'Not found'
            ], 404);
        }else if($wallet->user_id !== $request->user()->id){
            return response()->json([
                'status' => 'error',
                'message' => 'Forbidden access'
            ], 403);
        }

        $wallet->update($data);
        $wallet->currency_code = $wallet->currency()->value('code');

        return response()->json([
            'status' => 'success',
            'message' => 'Wallet updated successful',
            'data' => $wallet
        ]);
    }

    public function deleteWallet(Request $request, $walletId): JsonResponse {
        $wallet = wallets::where('id', $walletId)->first();
        
        if(!$wallet) {
            return response()->json([
                'status' => 'error',
                'message' => 'Not found'
            ], 404);
        }else if($wallet->user_id !== $request->user()->id){
            return response()->json([
                'status' => 'error',
                'message' => 'Forbidden access'
            ], 403);
        }

        $wallet->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Wallet deleted successful'
        ]);
    }

    public function allWallets(Request $request): JsonResponse {
        $wallet = wallets::with('currency')->where('user_id', $request->user()->id)->get()->map(function (wallets $item) {
            $item->currency_code = $item->currency->code;

            return $item->makeHidden('currency');
        })->makeVisible('balance');


        return response()->json([
            'status' => 'success',
            'message' => 'Get all wallets successful',
            'data' => $wallet
        ]);
    }

    public function detailWallet(Request $request, $walletId): JsonResponse {
        $wallet = wallets::where('id', $walletId)->first()?->makeVisible('balance');

        if(!$wallet){
            return response()->json([
                'status' => 'error',
                'message' => 'Not found'
            ], 404);
        }else if($wallet->user_id !== $request->user()->id){
            return response()->json([
                'status' => 'error',
                'message' => 'Forbidden access'
            ], 403);
        }

        $wallet->currency_code = $wallet->currency()->value('code');

        return response()->json([
            'status' => 'success',
            'message' => 'Get detail wallet successful',
            'data' => $wallet
        ]);
    }
}
