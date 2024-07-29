<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Company;
use App\Http\Requests\ArticleRequest;

class ProductController extends Controller
{   
       
    // ProductsController.php
/*public function index(Request $request)
{
    $query = Product::query();

    if ($request->has('search')) {
        $query->where('product_name', 'like', '%' . $request->search . '%');
    }

    if ($request->has('company_id')) {
        $query->where('company_id', $request->company_id);
    }

    if ($request->has('sort')) {
        $sortDirection = substr($request->sort, -4) === 'desc' ? 'desc' : 'asc';
        $sortColumn = substr($request->sort, 0, -4);
        $query->orderBy($sortColumn, $sortDirection);
    }

    $products = $query->paginate(10);
    $companies = Company::pluck('company_name', 'id');

    if ($request->ajax()) {
        return response()->json([
            'products' => $products->items(),
            'companies' => $companies,
        ]);
    }

    return view('list', compact('products', 'companies'));
}*/

public function index(Request $request)
{
    $query = Product::query();

    // 商品名検索
    if ($request->has('search')) {
        $query->where('product_name', 'like', '%' . $request->search . '%');
    }

    // メーカー検索
    if ($request->has('company_id') && $request->company_id != '') {
        $query->where('company_id', $request->company_id);
    }

    // 価格検索
    if ($request->has('max_price') && $request->max_price != '') {
        $query->where('price', '<=', $request->max_price);
    }

    if ($request->has('min_price') && $request->min_price != '') {
        $query->where('price', '>=', $request->min_price);
    }

    // 在庫検索
    if ($request->has('max_stock') && $request->max_stock != '') {
        $query->where('stock', '<=', $request->max_stock);
    }

    if ($request->has('min_stock') && $request->min_stock != '') {
        $query->where('stock', '>=', $request->min_stock);
    }

    // ソート
    if ($request->has('sort')) {
        $sortDirection = substr($request->sort, -4) === 'desc' ? 'desc' : 'asc';
        $sortColumn = substr($request->sort, 0, -4);
        $query->orderBy($sortColumn, $sortDirection);
    }

    $products = $query->paginate(10);
    $companies = Company::pluck('company_name', 'id');

    if ($request->ajax()) {
        return response()->json([
            'products' => $products->items(),
            'companies' => $companies,
        ]);
    }

    return view('list', compact('products', 'companies'));
}


   

        public function create()
    {
        // companiesテーブルから全てのcompany_nameを取得
        $companies = Company::all();

        // salesビューにデータを渡す
        return view('sales', compact('companies'));
    }



    public function store(ArticleRequest $request)
{
    // バリデーションは自動的に行われる
    $validatedData = $request->validated();

    try {
        $product = new Product;
        $product->company_id = $validatedData['company_id'];
        $product->product_name = $validatedData['product_name'];
        $product->price = $validatedData['price'];
        $product->stock = $validatedData['stock'];
        $product->comment = $validatedData['comment'];

        if ($request->hasFile('img_path')) {
            $file = $request->file('img_path');
            $fileName = time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('images'), $fileName);
            $product->img_path = 'images/' . $fileName;
        }

        $product->save();
    } catch (\Exception $e) {
        // 例外が発生した場合の処理
        return redirect()->back()->withErrors(['error' => 'データの保存に失敗しました。']);
    }

    return redirect()->route('products.index')->with('success', '新しい商品を追加しました');
}

        

        public function showSalesView()
        {

            return view('sales');
        }

        public function show($id)
    {
        $product = Product::findOrFail($id);
        return view('show', compact('product'));
    }


     public function destroy($id)
    {
    try {
        $product = Product::findOrFail($id);
        $product->delete();
    } catch (\Exception $e) {
        // 例外が発生した場合の処理
        return redirect()->back()->withErrors(['error' => '商品の削除に失敗しました。']);
    }

    return redirect()->route('products.index')->with('success', '商品が削除されました。');
    }


    public function edit($id)
        {
            $product = Product::findOrFail($id);
            $companies = Company::all();
            return view('edit', compact('product', 'companies'));
        }

    public function update(ArticleRequest $request, $id)
{
    // バリデーションは自動的に行われる
    $validatedData = $request->validated();

    try {
        $product = Product::findOrFail($id);
        $product->company_id = $validatedData['company_id'];
        $product->product_name = $validatedData['product_name'];
        $product->price = $validatedData['price'];
        $product->stock = $validatedData['stock'];
        $product->comment = $validatedData['comment'];

        if ($request->hasFile('img_path')) {
            $file = $request->file('img_path');
            $fileName = time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('images'), $fileName);
            $product->img_path = 'images/' . $fileName;
        }

        $product->save();
    } catch (\Exception $e) {
        // 例外が発生した場合の処理
        return redirect()->back()->withErrors(['error' => 'データの更新に失敗しました。']);
    }

    return redirect()->route('products.index')->with('success', '商品情報を更新しました');
}

}
