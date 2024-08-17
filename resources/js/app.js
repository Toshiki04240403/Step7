import './bootstrap';

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

    $(document).ready(function () {
        // CSRFトークンをすべてのAjaxリクエストに含める設定
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        // 削除ボタンのクリックイベント
        $(document).on('click', '.delete', function () {
            var productId = $(this).closest('.delete-form').data('id');

            if (confirm('本当に削除しますか？')) {
                $.ajax({
                    url: '/products/' + productId,
                    type: 'DELETE',
                    success: function (response) {
                        if (response.success) {
                            $('#product-' + productId).remove();
                            alert(response.success);
                        } else {
                            alert('商品の削除に失敗しました。');
                        }
                    },
                    error: function () {
                        alert('商品の削除に失敗しました。');
                    }
                });
            }
        });
    });

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
});
