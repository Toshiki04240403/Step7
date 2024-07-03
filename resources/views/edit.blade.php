<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>商品情報編集</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
        }
        .container {
            max-width: 600px;
            margin: auto;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        h1 {
            text-align: center;
        }
        .form-group {
            margin-bottom: 15px;
        }
        .form-group label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }
        .form-group input,
        .form-group textarea {
            width: 100%;
            padding: 8px;
            box-sizing: border-box;
        }
        .form-group select {
            width: 100%;
            padding: 8px;
            box-sizing: border-box;
        }
        .form-group img {
            max-width: 100%;
            height: auto;
            display: block;
            margin-top: 10px;
        }
        .buttons {
            display: flex;
            justify-content: space-between;
        }
        .buttons button {
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        .buttons .submit {
            background-color: #28a745;
            color: white;
        }
        .buttons .back {
            background-color: #dc3545;
            color: white;
        }
    </style>
</head>
<body>

<div class="container">
    <h1>商品情報編集</h1>

    <form action="{{ route('products.update', $product->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        
        <div class="form-group">
            <label>ID:</label>
            <span>{{ $product->id }}</span>
        </div>
        
        <div class="form-group">
            <label for="product_name">商品名:</label>
            <input type="text" id="product_name" name="product_name" value="{{ old('product_name', $product->product_name) }}">
        </div>
        
        <div class="form-group">
                <label for="company_id">メーカー名:</label>
                    <select id="company_id" name="company_id">
                    <option value="">メーカーを選択してください</option>
                         @foreach ($companies as $company)
                        <option value="{{ $company->id }}" {{ old('company_id', $product->company_id) == $company->id ? 'selected' : '' }}>
                        {{ $company->company_name }}
                        </option>
                        @endforeach
                </select>
            </div>
        
        <div class="form-group">
            <label for="price">価格:</label>
            <input type="number" id="price" name="price" value="{{ old('price', $product->price) }}">
        </div>
        
        <div class="form-group">
            <label for="stock">在庫数:</label>
            <input type="number" id="stock" name="stock" value="{{ old('stock', $product->stock) }}">
        </div>
        
        <div class="form-group">
            <label for="comment">コメント:</label>
            <textarea id="comment" name="comment">{{ old('comment', $product->comment) }}</textarea>
        </div>
        
        <div class="form-group">
            <label for="img_path">商品画像:</label>
            <input type="file" id="img_path" name="img_path">
            @if($product->img_path)
                <img src="{{ asset('storage/' . $product->img_path) }}" alt="{{ $product->product_name }}">
            @endif
        </div>
        
        <div class="buttons">
            <button type="submit" class="submit">編集実行</button>
            <button type="button" class="back" onclick="location.href='{{ route('products.show', $product->id) }}'">戻る</button>
        </div>
    </form>
</div>

</body>
</html>
