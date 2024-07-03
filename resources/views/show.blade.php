<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>商品情報詳細画面</title>
    <style>
    body {
        font-family: 'Arial', sans-serif;
        margin: 0;
        padding: 20px;
        background-color: #f4f4f4;
    }
    .container {
        max-width: 800px;
        margin: auto;
        background: white;
        padding: 20px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    }
    h1 {
        color: #333;
    }
    .product-info {
        border: 1px solid #333;
        padding: 10px;
        margin-bottom: 20px;
    }
    .product-info div {
        margin-bottom: 10px;
    }
    strong {
        color: #333;
    }
    .button {
        display: inline-block;
        padding: 10px 20px;
        text-decoration: none;
        border-radius: 5px;
        margin-right: 10px;
    }
    .button-back {
        background-color: #007bff;
        color: white;
    }
    .button-back:hover {
        background-color: #0056b3;
    }
    .button-edit {
        background-color: #28a745;
        color: white;
    }
    .button-edit:hover {
        background-color: #218838;
    }
    .img_path img {
        max-width: 100%;
        height: auto;
    }
    </style>
</head>
<body>

<div class="container">
    <h1>商品情報詳細画面</h1>

    <div class="product-info">
        <div>
            <strong>商品名：</strong>
            <span>{{ $product->product_name }}</span>
        </div>
        <div>
            <strong>メーカー名：</strong>
            <span>{{ $product->company->company_name }}</span>
        </div>
        <div>
            <strong>価格：</strong>
            <span>{{ $product->price }}</span>
        </div>
        <div>
            <strong>在庫数：</strong>
            <span>{{ $product->stock }}</span>
        </div>
        <div>
            <strong>コメント：</strong>
            <p>{{ $product->comment }}</p>
        </div>
        <div class="img_path">
            <strong>商品画像：</strong>
            @if($product->img_path)
                <img src="{{ asset('storage/' . $product->img_path) }}" alt="{{ $product->product_name }}">
            @else
                <p>画像がありません。</p>
            @endif
        </div>
    </div>

    <a href="{{ route('products.index') }}" class="button button-back">戻る</a>
    <a href="{{ route('products.edit', $product->id) }}" class="button button-edit">編集</a>
</div>

</body>
</html>
