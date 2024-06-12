<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\companies;

class ProductController extends Controller
{   
    public function store(Request $request)
    {
        // バリデーションを実施
        $validated = $request->validate([
            'product_name' => 'required|max:255',
            'manufacturer_name' => 'required|max:255',
            'price' => 'required|numeric',
            'stock' => 'required|numeric',
            'comment' => 'nullable',
            'product_image' => 'nullable|image|max:2048', // 画像ファイルがある場合、画像に対するバリデーションを指定
        ]);

        // Productモデルを使用してデータベースに新しい商品を登録
        $product = new Product();
        $product->product_name = $request->product_name;
        $product->manufacturer_name = $request->manufacturer_name;
        $product->price = $request->price;
        $product->stock = $request->stock;
        $product->comment = $request->comment;

        // 画像ファイルが存在する場合の処理
        if ($request->hasFile('product_image')) {
            $path = $request->file('product_image')->store('public/products');
            $product->product_image = basename($path);
        }

        $product->save(); // データベースに保存

        // 商品リストページなど、適切なページにリダイレクト
        // データベースへの保存処理後
        return redirect()->route('sales.view')->with('success', '商品が正常に登録されました。');

    }

    public function showSalesView()
    {

            return view('sales', []);
    }


    


    /**
     * 商品リストの表示
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
{
    $query = Product::query();

    // 検索条件
    if ($search = $request->input('search')) {
        $query->where('product_name', 'like', "%{$search}%");
    }

    if ($companyId = $request->input('company_id')) {
        $query->where('company_id', $companyId);
    }

    // ソート条件
    if ($request->input('sort') == 'company_name') {
            $query->join('companies', 'products.company_id', '=', 'companies.id')
                  ->orderBy('companies.name')
                  ->select('products.*'); // 重要: selectを指定しないとカラムが競合する可能性があります
        }

        $products = $query->get();
        $companies = Product::all();

    return view('list', compact('products', 'companies'));
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


