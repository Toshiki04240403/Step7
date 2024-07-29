import './bootstrap';

$(document).ready(function () {
    $('.sort-button').on('click', function () {
        var sortParam = $(this).data('sort');
        var searchParams = new URLSearchParams(window.location.search);
        searchParams.set('sort', sortParam);

        var url = '{{ route("products.index") }}?' + searchParams.toString();

        $.ajax({
            url: url,
            type: 'GET',
            success: function (response) {
                $('table tbody').html('');

                $.each(response.products, function (index, product) {
                    var row = `
                        <tr>
                            <td>${product.id}</td>
                            <td><img src="${product.img_path}" alt="商品画像" width="50"></td>
                            <td>${product.product_name}</td>
                            <td>${product.price}円</td>
                            <td>${product.stock}本</td>
                            <td>${response.companies[product.company_id]}</td>
                            <td>
                                <button class="details" onclick="location.href='/products/${product.id}'">詳細</button>
                                <form class="inline" action="/products/${product.id}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button class="delete" type="submit" onclick="return confirm('本当に削除しますか？')">削除</button>
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
