<!DOCTYPE html>
<html lang="en" data-bs-theme="dark">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Coalition Tech</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-4Q6Gf2aSP4eDXB8Miphtr37CMZZQ5oXLH2yaXMJ2w8e2ZtHTl7GptT4jmndRuHDT" crossorigin="anonymous">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
</head>

<style>
    .homepage {
        padding-top: 4rem;
        padding-left: 4rem;
        padding-right: 4rem;
    }

    .pt-2 {
        padding-top: 2rem;
    }

    .pb-2 {
        padding-bottom: 2rem;
    }

    .hidden {
        display: none;
    }
</style>

<body>
    <div class="homepage">

        <div id="result" class=" hidden alert alert-success pb-2 pt-2"></div>
        <div id="error" class=" hidden alert alert-danger pb-2 pt-2"></div>
        <form action="{{ route('products.store') }}" method="POST" id="productForm">
            @csrf
            <div class="mb-3">
                <label for="exampleFormControlInput1" class="form-label">Product Name</label>
                <input type="text" class="form-control" id="exampleFormControlInput1" placeholder="Product Name">
            </div>
            <div class="mb-3">
                <label for="exampleFormControlInput2" class="form-label">Quantity in stock</label>
                <input type="number" class="form-control" id="exampleFormControlInput2" placeholder="Product Quantity"
                    name="quantity">
            </div>
            <div class="input-group mb-3" for="exampleFormControlInput3">
                <span class="input-group-text">Price per item($)</span>
                <input type="text" class="form-control" aria-label="Amount (to the nearest dollar)" name="price"
                    id="exampleFormControlInput3">
                <span class="input-group-text">.00</span>
            </div>
            <button type="submit" class="btn btn-primary">Submit</button>
        </form>
        <br><br><br>
        <table class="table">
            <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Product Name</th>
                    <th scope="col">Quantity In Stock</th>
                    <th scope="col">Price Per Item</th>
                    <th scope="col">DateTime Submitted</th>
                    <th scope="col">Total Value Number</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @foreach ($products as $product)
                    <tr>
                        <td scope="row">{{ $loop->index + 1 }}</td>
                        <td>{{ $product['name'] }}</td>
                        <td>{{ $product['quantity'] }}</td>
                        <td>${{ $product['price'] }}</td>
                        <td>{{ $product['created_at'] }}</td>
                        <td>${{ $product['price'] * $product['quantity'] }}</td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot>
                @if (count($products) > 0)
                    <tr>
                        <td colspan="4"></td>
                        <td>Total price</td>
                        <td>${{ $totalValue }}</td>
                        <td></td>
                    </tr>
                @endif
            </tfoot>
        </table>


    </div>


</body>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-j1CDi7MgGQ12Z7Qab0qlWQ/Qqz24Gc6BM0thvEMVjHnfYGF0rmFCozFSxQBxwHKO" crossorigin="anonymous">
</script>
<script>
    $("#productForm").submit(function(event) {

        event.preventDefault();

        var $form = $(this);
        var $url = $form.attr('action');
        var $name = $('#exampleFormControlInput1').val();
        var posting = $.post($url, {
            name: $('#exampleFormControlInput1').val(),
            quantity: $('#exampleFormControlInput2').val(),
            price: $('#exampleFormControlInput3').val(),
            _token: '{{ csrf_token() }}'
        });

        posting.done(function(data) {
            renderProducts(data);
            $('#result').removeClass('hidden').text('SUCCESS');
            $('#error').addClass('hidden').text('');
            $('#productForm')[0].reset();
        });
        posting.fail(function(data) {
            var $errorMessages = data?.responseJSON?.errors ?? [];
            for (let key in $errorMessages) {
                $errorMessage = $errorMessages[key] ?? "ERROR HAPPENED";
            }
            $('#error').removeClass('hidden').text($errorMessage);
            $('#result').addClass('hidden').text('');
        });


    });

    function renderProducts(data) {
        const products = data.products;
        const totalValue = data.totalValue;

        let rows = '';
        products.forEach((p, index) => {
            rows += `
          <tr>
            <td>${index + 1}</td>
            <td>${p.name}</td>
            <td>${p.quantity}</td>
            <td>$${p.price}</td>
            <td>${p.created_at}</td>
            <td>$${p.price * p.quantity}</td>
          </tr>`;
        });

        $('table tbody').html(rows);
        $('table tfoot').html(`
        <tr>
            <td colspan="4"></td>
            <td>Total price</td>
            <td>$${totalValue}</td>
        </tr>`);
    }
</script>

</html>
