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




    public function store(ArticleRequest $request)
    {
        
        $validatedData = $request->validated();

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
        $product = Product::findOrFail($id);
        $product->delete();

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
    $product = Product::findOrFail($id);
    $product->company_id = $request->input('company_id');
    $product->product_name = $request->input('product_name');
    $product->price = $request->input('price');
    $product->stock = $request->input('stock');
    $product->comment = $request->input('comment');
    if ($request->hasFile('img_path')) {
        $file = $request->file('img_path');
        $fileName = time() . '.' . $file->getClientOriginalExtension();
        $file->move(public_path('images'), $fileName);
        $product->img_path = 'images/' . $fileName;
    }
    $product->save();
    return redirect()->route('products.index')->with('success', '商品情報を更新しました');
}



    // 商品情報編集画面を表示するメソッド
    /*public function edit($id)
    {
        $product = Product::findOrFail($id);
        $companies = Company::all();
        return view('edit', compact('product','companies'));
    }

    public function update(ArticleRequest $request, $id)
{
    try {
        $product = Product::findOrFail($id);

        // 画像アップロードの処理
        if ($request->hasFile('img_path')) {
            $imagePath = $request->file('img_path')->store('images', 'public');
            $product->img_path = $imagePath;
        }

        $product->fill($request->validated());
        // company_idを設定
        $product->company_id = $request->input('company_id');

        $product->save();

        return redirect()->route('products.show', $product->id)->with('success', '商品情報を更新しました。');
    } catch (\Illuminate\Validation\ValidationException $e) {
        // バリデーションエラーの処理
        return redirect()->back()->withErrors($e->errors())->withInput();
    } catch (\Exception $e) {
        // その他のエラーの処理
        dd($e->getMessage());
    }
}*/








    /*
    public function update(ArticleRequest $request, $id)
    {

        // バリデーションデータの確認
        dd($request->validated());
        // バリデーション
        //$validated = $request->validated();

        // 商品を取得
        $product = Product::findOrFail($id);
        // データの更新
        $product->update($validated);
        

        // 画像のアップロード処理
        if ($request->hasFile('img_path')) {
            $imgPath = $request->file('img_path')->store('imges', 'public');
            $product->img_path = $imgPath;

            // データベースに保存
            $product->save();
        }

        

        // リダイレクト
        return redirect()->route('products.show', $product->id)->with('success', '商品情報を更新しました。');
    }*/
    
}


