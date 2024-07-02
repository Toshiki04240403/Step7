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
        
        // バリデーションを実施
        $validated = $request->validated();

        // Productモデルを使用してデータベースに新しい商品を登録
         $product = new Product();
        $product->product_name = $validated['product_name'];
        $product->company_id = $validated['company_id'];
        $product->price = $validated['price'];
        $product->stock = $validated['stock'];
        $product->comment = $validated['comment'];

        // 画像ファイルが存在する場合の処理
        if ($request->hasFile('product_image')) {
            $path = $request->file('product_image')->store('public/products');
            $product->img_path = basename($path);
        }

        $product->save(); // データベースに保存

        // 商品リストページなど、適切なページにリダイレクト
        // データベースへの保存処理後
        return redirect()->route('sales.view')->with('success', '商品が正常に登録されました。');

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

    // 商品情報編集画面を表示するメソッド
    public function edit($id)
    {
        $product = Product::findOrFail($id);
        $companies = Company::all();
        return view('edit', compact('product','companies'));
    }
    public function update(ArticleRequest $request, $id)
    {
        // バリデーション
        $validated = $request->validated();

        // 商品を取得
        $product = Product::findOrFail($id);
        // データの更新
        $product->update($validated);
        

        // 画像のアップロード処理
        if ($request->hasFile('image_path')) {
            $imagePath = $request->file('image_path')->store('images', 'public');
            $product->image_path = $imagePath;

            // データベースに保存
            $product->save();
        }

        

        // リダイレクト
        return redirect()->route('products.show', $product->id)->with('success', '商品情報を更新しました。');
    }
    // 商品情報を更新するメソッド
    /*public function update(ArticleRequest $request, $id)
    {
        // バリデーションを実施
        $validated = $request->validated();

        $product = Product::findOrFail($id);


        $product->product_name = $validated['product_name'];
        $product->company_id = $validated['company_id'];
        $product->price = $validated['price'];
        $product->stock = $validated['stock'];
        $product->comment = $validated['comment'];

        if ($request->hasFile('image_path')) {
            $path = $request->file('image_path')->store('public');
            $product->image_path = basename($path);
        }

        $product->save();

        return redirect()->route('products.index')->with('success', '商品情報が更新されました。');
    }
        */
}


