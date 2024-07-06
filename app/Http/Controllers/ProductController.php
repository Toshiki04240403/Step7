<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Company;
use App\Http\Requests\ArticleRequest;

class ProductController extends Controller
{   

       
       public function index(Request $request)
    {
        $search = $request->input('search');
        $companyName = $request->input('company_id');

        $query = Product::query();


        if (isset($search)){
            $query->where('product_name', 'like', '%'.$search. '%');
        }

        if (isset($companyName)) {
            if ($companyName !== "all") {
            $query->where('company_id', $companyName);
            }
        }

        $products = $query->with('company')->orderBy('id', 'asc')->paginate(15);

    
        $companies = Company::pluck('company_name', 'id')->toArray();

        return view('list', compact('products', 'companies'));
    }

        public function create()
    {
        // companiesテーブルから全てのcompany_nameを取得
        $companies = Company::all();

        // salesビューにデータを渡す
        return view('sales', compact('companies'));
    }


public function store(Request $request)
{
    // バリデーションルールを定義
    $validatedData = $request->validate([
        'company_id' => 'required|numeric',
        'product_name' => 'required|max:255',
        'price' => 'required|numeric|min:0',
        'stock' => 'required|integer|min:0',
        'comment' => 'nullable|max:1000',
        'img_path' => 'nullable|image|max:2048'
    ]);

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

    public function update(Request $request, $id)
    {
    // バリデーションルールを定義
    $validatedData = $request->validate([
        'company_id' => 'required|numeric',
        'product_name' => 'required|max:255',
        'price' => 'required|numeric|min:0',
        'stock' => 'required|integer|min:0',
        'comment' => 'nullable|max:1000',
        'img_path' => 'nullable|image|max:2048'
    ]);

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


