<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Sale;
use Illuminate\Support\Facades\DB;

class PurchaseController extends Controller
{
    public function purchase(Request $request)
    {
        $request->validate([
            'product_id' => 'required|integer|exists:products,id',
            'quantity' => 'required|integer|min:1',
        ]);

        $product = Product::find($request->product_id);

        if ($product->stock < $request->quantity) {
            return response()->json(['error' => '在庫が不足しています'], 400);
        }

        DB::transaction(function () use ($product, $request) {
            // salesテーブルにレコードを追加
            Sale::create([
                'product_id' => $product->id,
            ]);

            // productsテーブルの在庫数を減算
            $product->decrement('stock', $request->quantity);
        });

        return response()->json(['message' => '購入が完了しました'], 200);
    }
}
