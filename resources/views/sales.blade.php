<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="UTF-8">
<title>商品新規登録画面</title>
<style>
    body {
        font-family: Arial, sans-serif;
        margin: 0;
        padding: 20px;
        background-color: #f4f4f4;
    }
    .container {
        max-width: 600px;
        margin: 0 auto;
        background-color: #fff;
        padding: 20px;
        border-radius: 8px;
        box-shadow: 0 0 10px rgba(0,0,0,0.1);
    }
    h2 {
        text-align: center;
        color: #333;
    }
    .form-group {
        margin-bottom: 15px;
    }
    .form-group label {
        display: block;
        margin-bottom: 5px;
        color: #333;
    }
    .form-group input,
    .form-group textarea {
        width: 100%;
        padding: 8px;
        box-sizing: border-box;
        border: 1px solid #ccc;
        border-radius: 4px;
    }
    .form-group input[type="file"] {
        padding: 3px;
    }
    .button-group {
        display: flex;
        justify-content: space-between;
    }
    button {
        display: inline-block;
        padding: 10px 20px;
        color: #fff;
        background-color: #007bff;
        border: none;
        border-radius: 4px;
        cursor: pointer;
    }
    button[type="button"] {
        background-color: #6c757d;
    }
    button:hover {
        opacity: 0.9;
    }
    button.back {
        background-color: #ffc107; /* 黄色い背景 */
        color: black; /* 黒色のテキスト */
    }
</style>
</head>
<body>

<div class="container">
    <h2>商品新規登録画面</h2>

    <form action="{{ route('sales.index') }}" method="post" enctype="multipart/form-data">
        @csrf
        <div class="form-group">
            <label for="product-name">商品名：</label>
            <input type="text" id="product-name" name="product_name" required>
        </div>
        <div class="form-group">
            <label for="company_id">メーカー名：</label>
            <input type="text" id="company_id" name="company_id" required>
        </div>
        <div class="form-group">
            <label for="price">価格：</label>
            <input type="number" id="price" name="price" required>
        </div>
        <div class="form-group">
            <label for="stock">在庫数：</label>
            <input type="number" id="stock" name="stock" required>
        </div>
        <div class="form-group">
            <label for="comment">コメント：</label>
            <textarea id="comment" name="comment" rows="4" ></textarea>
        </div>
        <div class="form-group">
            <label for="product-image">商品画像：</label>
            <input type="file" id="product-image" name="product_image" accept="image/*" >
        </div>
        <br>
        <div class="button-group">
            <button type="submit">新規登録</button>
            </form>
            <form action="{{ route('products.index') }}" method="GET" style="display:inline;">
                <button type="submit" class="back">戻る</button>
            </form>
        </div>
</div>

</body>
</html>
