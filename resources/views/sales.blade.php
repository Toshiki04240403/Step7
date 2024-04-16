<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="UTF-8">
<title>商品新規登録画面</title>
</head>
<body>

<h2>商品新規登録画面</h2>

<form action="/path/to/your/script" method="post" enctype="multipart/form-data">
  <div>
    <label for="product-name">商品名：</label>
    <input type="text" id="product-name" name="product_name" required>
  </div>
  <div>
    <label for="manufacturer-name">メーカー名：</label>
    <input type="text" id="manufacturer-name" name="manufacturer_name" required>
  </div>
  <div>
    <label for="price">価格：</label>
    <input type="number" id="price" name="price" required>
  </div>
  <div>
    <label for="stock">在庫数：</label>
    <input type="number" id="stock" name="stock" required>
  </div>
  <div>
    <label for="comment">コメント：</label>
    <textarea id="comment" name="comment" rows="4" required></textarea>
  </div>
  <div>
    <label for="product-image">商品画像：</label>
    <input type="file" id="product-image" name="product_image" accept="image/*" required>
  </div>
  <br>
  <button type="submit">新規登録</button>
  <button type="button" onclick="history.back()">戻る</button>
</form>

</body>
</html>
