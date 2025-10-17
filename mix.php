<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Find easily a doctor and book online an appointment">
    <meta name="author" content="Ansonika">
    <title>FIND A BEST NAMKEEN</title>
    <?php include('head.php');?>

</head>

<body>

    <div class="layer"></div>
    <!-- Mobile menu overlay mask -->

    <div id="preloader">
        <div data-loader="circle-side"></div>
    </div>
    <!-- End Preload -->


    <?php include('navbar.php');?>

    <div class="container">

        <div class="product-container">

            <!-- Left: Images -->
            <div class="product-images">
                <div class="thumbnails mb-2">
                    <img src="img/Bikaji - Ratlami Chatpata Sev Bhujia.jpeg" alt="" class="active"
                        onclick="changeImage(this)">
                    <img src="img/AdobeStock_365664596_Preview.jpeg" alt="" onclick="changeImage(this)">

                </div>
                <div class="main-image">
                    <img id="productImage" src="img/AdobeStock_365664596_Preview.jpeg" alt="">
                </div>
            </div>

            <!-- Right: Details -->
            <div class="product-details">
                <h1>Sev</h1>
                <div class="price mb-2">Price: Rs. <span id="productPrice">140</span>.00</div>

                <div class="weight-section mb-2">
                    <strong>Weight:</strong>
                    <div class="weights">
         
                        <button data-price="140" class="active" onclick="selectWeight(this)">500g</button>
                        <button data-price="280" onclick="selectWeight(this)">1kg</button>
                    </div>
                </div>

                <div class="quantity-section mb-2">
                    <strong>Quantity:</strong>
                    <div class="quantity">
                        <button type="button" id="decrease">-</button>
                        <input type="text" id="quantity" value="1" readonly>
                        <button type="button" id="increase">+</button>

                    </div>
                </div>

                <div class="total-box">
                    Total: Rs. <span id="totalAmount">140</span>.00
                </div>
                <button class="btn btn-buy-now mt-3" data-bs-toggle="modal" data-bs-target="#buyNowModal">
                    Buy Now
                </button>


                <div class="description mt-3">
                    <h3>Description:</h3>
                    <p>Aakash Kenya Chivda – crispy, flavorsome namkeen with nuts & spices.</p>
                </div>
            </div>
        </div>
    </div>


    <!-- Buy Now Modal -->
    <div class="modal fade" id="buyNowModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header custom-header">
                    <h5 class="modal-title">Customer Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form id="buyNowForm">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="customerName" class="form-label">Full Name</label>
                            <input type="text" name="customerName" id="customerName" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label for="customerAddress" class="form-label">Address</label>
                            <input type="text" name="customerAddress" id="customerAddress" class="form-control"
                                required>
                        </div>
                        <div class="mb-3">
                            <label for="customerEmail" class="form-label">Email</label>
                            <input type="email" name="customerEmail" id="customerEmail" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label for="customerPhone" class="form-label">Mobile</label>
                            <input type="tel" name="customerPhone" class="form-control" required pattern="[0-9]{10}"
                                maxlength="10" placeholder="Enter 10-digit mobile number"
                                oninput="this.value=this.value.replace(/[^0-9]/g,'');">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn-cancel" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn-place-order">Place Order</button>

                    </div>
                </form>
            </div>
        </div>
    </div>



 <script>
    function changeImage(img) {
        document.getElementById('productImage').src = img.src;
        document.querySelectorAll('.thumbnails img').forEach(i => i.classList.remove('active'));
        img.classList.add('active');
    }

    function selectWeight(btn) {
        document.querySelectorAll('.weights button').forEach(b => b.classList.remove('active'));
        btn.classList.add('active');
        updateTotal();
    }

    function updateQuantity(val) {
        let qtyInput = document.getElementById('quantity');
        let qty = Number(qtyInput.value) || 1; // safe conversion
        qty += val;
        if (qty < 1) qty = 1;
        qtyInput.value = qty;
        updateTotal();
    }

    // You need to add event listeners for the quantity buttons
    document.getElementById('increase').addEventListener('click', () => updateQuantity(1));
    document.getElementById('decrease').addEventListener('click', () => updateQuantity(-1));

    function updateTotal() {
        let price = parseInt(document.querySelector('.weights button.active').dataset.price);
        let qty = parseInt(document.getElementById('quantity').value);
        document.getElementById('totalAmount').innerText = price * qty;
    }

    // AJAX submit
    document.getElementById('buyNowForm').addEventListener('submit', function(e) {
        e.preventDefault();
        let formData = new FormData(this);
        
        // Dynamically get the product name from the H1 tag
        let productName = document.querySelector('.product-details h1').innerText;
        formData.append('product', productName); // Use the real product name

        formData.append('weight', document.querySelector('.weights button.active').innerText);
        formData.append('quantity', document.getElementById('quantity').value);
        formData.append('total', document.getElementById('totalAmount').innerText);

        fetch('saveorder.php', {
                method: 'POST',
                body: formData
            })
            .then(res => res.json())
            .then(data => {
                if (data.status === "success") {
                    // Redirect to thankyou.php with order ID
                    window.location.href = "thankyou.php?order_id=" + data.order_id;
                } else {
                    alert("❌ " + data.msg);
                }
            })
            .catch(err => console.error(err));
    });
</script>
    <?php include('footer.php');?>
    <?php include('js.php');?>
</body>

</html>