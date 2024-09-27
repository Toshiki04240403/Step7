<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>商品一覧</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/tablesorter/2.31.3/css/theme.default.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/tablesorter/2.31.3/js/jquery.tablesorter.min.js"></script>

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
            background-color: #fff;
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
            padding: 5px;
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
    </style>
</head>
<body>
    <h1>商品一覧</h1>

<div class="search-container">
    <form id="search-query" method="GET">
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
        <button type="button" id="search-button">検索</button>
    </form>
</div>

<table class="tablesorter">
    <thead>
        <tr>
            <th><a href="#" class="sort-link" data-sort="id">ID</a></th>
            <th>商品画像</th>
            <th>商品名</th>
            <th><a href="#" class="sort-link" data-sort="price">価格</a></th>
            <th><a href="#" class="sort-link" data-sort="stock">在庫数</a></th>
            <th>メーカー</th>
            <th>
                <form action="{{ route('sales.index') }}" method="GET" style="display:inline;">
                    <button type="submit" class="new-registration">新規登録</button>
                </form>
            </th>
        </tr>
    </thead>
    <tbody id="product-list">
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

{{ $products->links() }}

<script>
    $(document).ready(function () {
        let currentSort = "{{ request('sort', 'id') }}"; // 初期はID
        let currentDirection = "{{ request('direction', 'desc') }}"; // 初期は降順

        // 検索ボタンのクリックイベント
        $('#search-button').on('click', function () {
            performSearch();
        });

        // ソートリンクのクリックイベント
        $(document).on('click', '.sort-link', function (e) {
            e.preventDefault();
            const sort = $(this).data('sort');

            if (currentSort === sort) {
                currentDirection = currentDirection === 'asc' ? 'desc' : 'asc'; // 切り替え
            } else {
                currentSort = sort;
                currentDirection = 'asc'; // 新しいソートカラムで昇順
            }

            performSearch();
        });

        // 削除ボタンのクリックイベント
        $(document).on('click', '.delete', function () {
            const id = $(this).data('id');
            const url = $(this).data('url');

            $.ajax({
                url: url,
                type: 'DELETE',
                data: {
                    _token: '{{ csrf_token() }}'
                },
                success: function () {
                    $(`#product-${id}`).remove();
                },
                error: function (error) {
                    console.error(error);
                }
            });
        });

        // 検索を実行する関数
        function performSearch() {
            const formData = $('#search-query').serializeArray();
            formData.push({ name: 'sort', value: currentSort });
            formData.push({ name: 'direction', value: currentDirection });

            $.ajax({
                url: "{{ route('products.index') }}",
                method: "GET",
                data: $.param(formData),
                success: function (response) {
                    $('#product-list').html($(response).find('#product-list').html());
                    $(".tablesorter").trigger("update");
                },
                error: function (xhr) {
                    alert('検索に失敗しました。');
                }
            });
        }

        // 初期ソート設定
        $(".tablesorter").tablesorter({
            sortList: [[0, 1]] // 初期表示はIDの降順
        });
    });
</script>


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
