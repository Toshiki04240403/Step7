<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>商品一覧</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
   
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
            background-color: #f9f9f9;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            width: 90%;
            margin: 20px auto;
        }
        .search-container form {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
        }
        .search-container input[type="text"],
        .search-container input[type="number"],
        .search-container select {
            flex: 1;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        .search-container button {
            flex: 0 1 auto;
            padding: 10px 20px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        .search-container button:hover {
            background-color: #0056b3;
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

        .notification {
            display: none;
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 1050;
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
                <th><a href="{{ request()->fullUrlWithQuery(['sort' => 'price', 'direction' => request('direction') === 'asc' ? 'desc' : 'asc']) }}">価格</a></th>
                <th><a href="{{ request()->fullUrlWithQuery(['sort' => 'stock', 'direction' => request('direction') === 'asc' ? 'desc' : 'asc']) }}">在庫数</a></th>
                <th>メーカー</th>
                <th><form action="{{ route('sales.index') }}" method="GET" style="display:inline;">
                        <button type="submit" class="new-registration">新規登録</button>
                </form></th>
            </tr>
        </thead>
        <tbody>
            @foreach ($products as $product)
                <tr id="product-{{ $product->id }}">
                    <td>{{ $product->id }}</td>
                    <td><img src="{{ $product->img_path }}" alt="商品画像" width="50"></td>
                    <td>{{ $product->product_name }}</td>
                    <td>{{ $product->price }}円</td>
                    <td>{{ $product->stock }}本</td>
                    <td>{{ $product->company->company_name }}</td>
                     <td>
                        <button class="details" onclick="location.href='{{ url('/products/' . $product->id) }}'">詳細</button>
                       <button class="delete" data-id="{{ $product->id }}" data-url="{{ route('products.destroy', $product->id) }}">削除</button>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

        <script>
        function purchaseProduct(productId) {
            const quantity = 1; // 購入する数量を設定（ここでは1固定）

            $.ajax({
                url: 'api/purchase',
                type: 'POST',
                data: {
                    product_id: productId,
                    quantity: quantity,
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    alert(response.success);
                    location.reload();
                },
                error: function(response) {
                    alert(response.responseJSON.error);
                }
            });
        }
    </script>

    
   <script>
        function purchaseProduct(productId, quantity) {
            const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

            fetch('/purchase', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': token
                },
                body: JSON.stringify({
                    product_id: productId,
                    quantity: quantity
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.error) {
                    alert(data.error);
                } else {
                    alert(data.message);
                    location.reload();
                }
            })
            .catch(error => console.error('Error:', error));
        }
    </script>


<script>
$(document).ready(function () {
    let sortDirection = 'asc';

    // CSRFトークンをすべてのAjaxリクエストに含める設定
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    // 並べ替えボタンのクリックイベント
    $(document).on('click', '.sort-button', function () {
        let sortParam = $(this).data('sort');
        sortDirection = sortDirection === 'asc' ? 'desc' : 'asc';

        $.ajax({
            url: productIndexUrl, // 予め定義されたURL変数を使います
            type: 'GET',
            data: {
                sort: sortParam,
                direction: sortDirection,
                // 他の検索条件が必要ならここに追加
            },
            success: function (response) {
                $('table tbody').html('');

                $.each(response.products, function (index, product) {
                    let row = `
                        <tr id="product-${product.id}">
                            <td>${product.id}</td>
                            <td><img src="${product.img_path}" alt="商品画像" width="50"></td>
                            <td>${product.product_name}</td>
                            <td>${product.price}円</td>
                            <td>${product.stock}本</td>
                            <td>${response.companies[product.company_id]}</td>
                            <td>
                                <button class="details" onclick="location.href='/products/${product.id}'">詳細</button>
                                <form class="inline" action="/products/${product.id}" method="POST">
                                    <input type="hidden" name="_token" value="${$('meta[name="csrf-token"]').attr('content')}">
                                    <input type="hidden" name="_method" value="DELETE">
                                    <button class="delete" type="submit" data-id="${product.id}">削除</button>
                                </form>
                            </td>
                        </tr>
                    `;
                    $('table tbody').append(row);
                });
            }
        });
     });
    });
</script>
   

<script>
$(document).on('click', '.delete', function () {
    var productId = $(this).data('id');
    var deleteUrl = $(this).data('url');

    if (confirm('本当にこの商品を削除しますか？')) {
        $.ajax({
            url: deleteUrl,
            type: 'POST', // POSTメソッドを使用
            data: {
                _method: 'DELETE', // _methodでDELETEを指定
                _token: '{{ csrf_token() }}' // CSRFトークンを含める
            },
            success: function (response) {
                if (response.success) {
                    $('#product-' + productId).remove();
                    alert('商品が削除されました。');
                } else {
                    alert('商品の削除に失敗しました。');
                }
            },
            error: function (xhr) {
                // エラーメッセージを明確に
                if (xhr.status === 404) {
                    alert('商品が見つかりませんでした。');
                } else {
                    alert('削除処理中にエラーが発生しました。');
                }
            }
        });
    }
});
</script>

    <script>
    function showNotification(message, type) {
        var notification = $('<div class="notification alert alert-' + type + ' alert-dismissible fade show" role="alert">' +
            message +
            '<button type="button" class="close" data-dismiss="alert" aria-label="Close">' +
            '<span aria-hidden="true">&times;</span>' +
            '</button>' +
            '</div>');
        $('body').append(notification);
        notification.fadeIn();

        setTimeout(function () {
            notification.fadeOut(function () {
                $(this).remove();
            });
        }, 3000);
    }
    </script>

</body>
</html>
