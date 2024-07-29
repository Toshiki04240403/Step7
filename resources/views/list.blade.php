<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>商品一覧</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="{{ asset('js/app.js') }}"></script>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f9f9f9;
            margin: 0;
            padding: 0;
        }
        h1 {
            text-align: center;
            margin-top: 20px;
            color: #333;
        }
    .search-container {
        background-color: #f9f9f9; /* 背景色を薄いグレーに */
        padding: 20px; /* 全体のパディングを追加 */
        border-radius: 8px; /* 角を丸く */
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1); /* 影を追加 */
    }

    .search-container form {
        display: flex;
        flex-wrap: wrap;
        gap: 10px; /* フォーム要素間の間隔 */
    }

    .search-container input[type="text"],
    .search-container input[type="number"],
    .search-container select {
        flex: 1; /* フォーム要素が均等に広がるように */
        padding: 10px; /* 入力フィールドの内側パディング */
        border: 1px solid #ccc; /* ボーダーの色 */
        border-radius: 4px; /* 入力フィールドの角を丸く */
    }

    .search-container button {
        flex: 0 1 auto; /* ボタンのサイズを自動調整 */
        padding: 10px 20px; /* ボタンの内側パディング */
        background-color: #007bff; /* ボタンの背景色 */
        color: white; /* ボタンの文字色 */
        border: none; /* ボーダーを無くす */
        border-radius: 4px; /* ボタンの角を丸く */
        cursor: pointer; /* カーソルをポインターに */
    }

    .search-container button:hover {
        background-color: #0056b3; /* ボタンのホバー時の色 */
    }
        table {
            width: 90%;
            margin: 20px auto;
            border-collapse: collapse;
            background-color: #fff;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
        th, td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        th {
            background-color: #f2f2f2;
            color: #333;
        }
        tr:hover {
            background-color: #f1f1f1;
        }
        button {
            padding: 8px 12px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        button.new-registration {
            background-color: #28a745;
            color: white;
        }
        button.details {
            background-color: #007bff;
            color: white;
        }
        button.delete {
            background-color: #dc3545;
            color: white;
        }
        button:hover {
            opacity: 0.8;
        }
        form.inline {
            display: inline;
        }
    </style>
</head>
<body>
    <h1>商品一覧</h1>

    <div class="search-container">
        <form action="{{ route('products.index') }}" method="GET">
            <input type="text" name="search" placeholder="商品名で検索" value="{{ request('search') }}">
            <select name="company_id">
                <option value="">全てのメーカー</option>
                @foreach ($companies as $id => $company_name)
                <option value="{{ $id }}" {{ request('company_id') == $id ? 'selected' : '' }}>
                {{ $company_name }}
                </option>
                @endforeach
            </select>
            <input type="number" name="max_price" placeholder="価格上限" value="{{ request('max_price') }}">
            <input type="number" name="min_price" placeholder="価格下限" value="{{ request('min_price') }}">
            <input type="number" name="max_stock" placeholder="在庫上限" value="{{ request('max_stock') }}">
            <input type="number" name="min_stock" placeholder="在庫下限" value="{{ request('min_stock') }}">
            
            <button type="submit">検索</button>
        </form>
    </div>
    
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>商品画像</th>
                <th>商品名</th>
                <th>
                    <button class="sort-button" data-sort="price_asc">価格</button>
                </th>
                <th>
                    <button class="sort-button" data-sort="stock_asc">在庫数</button>
                </th>
                <th>メーカー</th>
                <th><form action="{{ route('sales.index') }}" method="GET" style="display:inline;">
                        <button type="submit" class="new-registration">新規登録</button>
                </form></th>
            </tr>
        </thead>
        <tbody>
            @foreach ($products as $product)
                <tr>
                    <td>{{ $product->id }}</td>
                    <td><img src="{{ $product->img_path }}" alt="商品画像" width="50"></td>
                    <td>{{ $product->product_name }}</td>
                    <td>{{ $product->price }}円</td>
                    <td>{{ $product->stock }}本</td>
                    <td>{{ $product->company->company_name }}</td>
                    <td>
                        <button class="details" onclick="location.href='{{ url('/products/' . $product->id) }}'">詳細</button>
                        <form class="inline" action="{{ route('products.destroy', $product->id) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button class="delete" type="submit" onclick="return confirm('本当に削除しますか？')">削除</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <!-- 必要なスクリプトの読み込み -->
    <script src="{{ asset('js/app.js') }}"></script>
</body>
</html>
