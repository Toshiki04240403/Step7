<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;

class ProductController extends Controller
{
    /**
     * 販売ページの表示
     */
    public function showsales()
    {
        return view('sales');
    }


    /**
     * 商品リストの表示
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // Productモデルのクエリビルダを初期化
        $query = Product::query();

        // 検索機能
        if ($request->has('search') && !empty($request->input('search'))) {
            $query->where('product_name', 'like', '%' . $request->input('search') . '%');
        }

        // メーカー別ソート機能
        if ($request->has('maker') && !empty($request->input('maker'))) {
            $query->where('maker', $request->input('maker'));
        }

        // クエリビルダを実行して結果を取得
        $products = $query->get();

        // 結果をビューに渡して表示
        return view('list', ['products' => $products]);
    }
        public function show($id)
    {
        $product = Product::findOrFail($id);
        return view('show', compact('product'));
    }


        public function destroy($id)
    {
        $product = Product::findOrFail($id);
        $product->delete();

        return redirect()->route('products.index')->with('success', '商品が削除されました。');
    }

    // 商品情報編集画面を表示するメソッド
    public function edit($id)
    {
        $product = Product::findOrFail($id);
        return view('edit', compact('product'));
    }

    // 商品情報を更新するメソッド
    public function update(Request $request, $id)
    {
        $request->validate([
            'product_name' => 'required|string|max:255',
            'company_name' => 'required|string|max:255',
            'price' => 'required|numeric',
            'stock' => 'required|numeric',
            'comment' => 'nullable|string',
            'image_path' => 'nullable|image|max:2048'
        ]);

        $product = Product::findOrFail($id);
        $product->product_name = $request->input('product_name');
        $product->company_id = $request->input('company_name');
        $product->price = $request->input('price');
        $product->stock = $request->input('stock');
        $product->comment = $request->input('comment');

        if ($request->hasFile('image_path')) {
            $path = $request->file('image_path')->store('public');
            $product->image_path = basename($path);
        }

        $product->save();

        return redirect()->route('products.index')->with('success', '商品情報が更新されました。');
    }
}


