
function changeImage(img) {
    document.getElementById('productImage').src = img.src;
    document.querySelectorAll('.thumbnails img').forEach(i => i.classList.remove('active'));
    img.classList.add('active');
}

function selectWeight(btn) {
    document.querySelectorAll('.weights button').forEach(b => b.classList.remove('active'));
    btn.classList.add('active');

    // Update hidden form fields
    document.getElementById('formWeight').value = btn.dataset.weight;
    updateTotal();
}

function updateQuantity(val) {
    let qtyInput = document.getElementById('quantity');
    let qty = Number(qtyInput.value) || 1;
    qty += val;
    if(qty < 1) qty = 1;
    qtyInput.value = qty;
    updateTotal();
}

document.getElementById('increase').addEventListener('click', () => updateQuantity(1));
document.getElementById('decrease').addEventListener('click', () => updateQuantity(-1));

function updateTotal() {
    let price = parseInt(document.querySelector('.weights button.active').dataset.price);
    let qty = parseInt(document.getElementById('quantity').value);
    let total = price * qty;
    document.getElementById('totalAmount').innerText = total;
    document.getElementById('formQuantity').value = qty;
    document.getElementById('formTotal').value = total;
}
function shareWhatsApp() {
    const productName = "<?= addslashes($product['name']) ?>";
    const referralText = referralCode ? ' | Referral Code: ' + referralCode : '';
    const message = `${productName} - Check this product: ${window.location.href}${referralText}`;
    const encodedMessage = encodeURIComponent(message);

    // Detect mobile vs desktop
    const isMobile = /iPhone|iPad|iPod|Android/i.test(navigator.userAgent);
    const waLink = isMobile 
        ? `https://wa.me/?text=${encodedMessage}` 
        : `https://web.whatsapp.com/send?text=${encodedMessage}`;

    window.open(waLink, '_blank');
}

function shareFacebook() {
    const url = encodeURIComponent(window.location.href);
    window.open(`https://www.facebook.com/sharer/sharer.php?u=${url}`, '_blank');
}

function shareInstagram() {
    const url = encodeURIComponent(window.location.href);
    window.open(`https://www.instagram.com/?url=${url}`, '_blank');
}
