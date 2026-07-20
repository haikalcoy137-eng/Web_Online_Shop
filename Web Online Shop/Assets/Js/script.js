// Toast Notification
function showToast(message, type = 'success') {
    const colors = {
        success: '#16a34a',
        error: '#ef4444',
        warning: '#f59e0b',
        info: '#3b82f6'
    };
    
    const toast = document.createElement('div');
    toast.className = 'toast';
    toast.style.background = colors[type] || colors.success;
    
    const icons = {
        success: 'fa-check-circle',
        error: 'fa-times-circle',
        warning: 'fa-exclamation-triangle',
        info: 'fa-info-circle'
    };
    
    toast.innerHTML = `<i class="fas ${icons[type] || icons.success}"></i> ${message}`;
    document.body.appendChild(toast);
    
    setTimeout(() => {
        toast.style.opacity = '0';
        toast.style.transform = 'translateX(100%)';
        toast.style.transition = '.3s';
        setTimeout(() => toast.remove(), 300);
    }, 3000);
}

// Image Slider
document.addEventListener('DOMContentLoaded', function() {
    const slides = document.querySelectorAll('.slide-img');
    const dots = document.querySelectorAll('.banner-dots span');
    
    if (slides.length > 0 && dots.length > 0) {
        let current = 0;
        
        function showSlide(index) {
            slides.forEach(s => s.style.display = 'none');
            dots.forEach(d => d.classList.remove('active'));
            slides[index].style.display = 'block';
            dots[index].classList.add('active');
        }
        
        showSlide(0);
        
        setInterval(() => {
            current = (current + 1) % slides.length;
            showSlide(current);
        }, 4000);
        
        dots.forEach((dot, i) => {
            dot.addEventListener('click', () => {
                current = i;
                showSlide(current);
            });
        });
    }
});

// Add to Cart via AJAX
function addToCart(produkId, qty = 1) {
    fetch('ajax_tambah_keranjang.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ id_produk: produkId, jumlah: qty })
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            showToast('Produk berhasil ditambahkan ke keranjang!');
            // Update badge
            const badge = document.querySelector('.cart-badge');
            if (badge) badge.textContent = data.total_keranjang;
        } else {
            showToast(data.message || 'Gagal menambahkan ke keranjang', 'error');
        }
    })
    .catch(() => showToast('Terjadi kesalahan', 'error'));
}

// Wishlist Toggle
function toggleWishlist(produkId, btn) {
    fetch('ajax_wishlist.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ id_produk: produkId })
    })
    .then(res => res.json())
    .then(data => {
        if (data.status === 'added') {
            btn.classList.add('active');
            showToast('Ditambahkan ke Wishlist');
        } else if (data.status === 'removed') {
            btn.classList.remove('active');
            showToast('Dihapus dari Wishlist');
        } else {
            showToast(data.message || 'Silakan login terlebih dahulu', 'warning');
            window.location.href = 'login.php';
        }
    })
    .catch(() => showToast('Terjadi kesalahan', 'error'));
}

// Update Cart Quantity
function updateCart(idKeranjang, jumlah, el) {
    if (jumlah < 1) return;
    
    fetch('ajax_update_keranjang.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ id_keranjang: idKeranjang, jumlah: jumlah })
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            if (el) el.textContent = jumlah;
            // Update subtotal
            const row = document.querySelector(`[data-id="${idKeranjang}"]`);
            if (row) {
                const price = parseFloat(row.dataset.harga);
                const totalEl = row.querySelector('.item-total');
                if (totalEl) {
                    totalEl.textContent = 'Rp ' + (price * jumlah).toLocaleString('id-ID');
                }
            }
            updateCartSummary();
        } else {
            showToast('Gagal mengupdate jumlah', 'error');
        }
    })
    .catch(() => showToast('Terjadi kesalahan', 'error'));
}

// Delete Cart Item
function deleteCartItem(idKeranjang, el) {
    if (!confirm('Hapus item ini dari keranjang?')) return;
    
    fetch('ajax_hapus_keranjang.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ id_keranjang: idKeranjang })
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            const item = el.closest('.cart-item');
            item.style.transition = '.3s';
            item.style.opacity = '0';
            item.style.transform = 'translateX(50px)';
            setTimeout(() => item.remove(), 300);
            updateCartSummary();
            showToast('Item dihapus dari keranjang');
        } else {
            showToast('Gagal menghapus item', 'error');
        }
    })
    .catch(() => showToast('Terjadi kesalahan', 'error'));
}

// Update Cart Summary
function updateCartSummary() {
    const items = document.querySelectorAll('.cart-item');
    let subtotal = 0;
    
    items.forEach(item => {
        const totalEl = item.querySelector('.item-total');
        if (totalEl) {
            const total = parseInt(totalEl.textContent.replace(/[^0-9]/g, ''));
            subtotal += total;
        }
    });
    
    const subtotalEl = document.querySelector('.cart-subtotal');
    const totalEl = document.querySelector('.cart-total');
    
    if (subtotalEl) subtotalEl.textContent = 'Rp ' + subtotal.toLocaleString('id-ID');
    if (totalEl) totalEl.textContent = 'Rp ' + subtotal.toLocaleString('id-ID');
}

// Format Rupiah
function formatRupiah(angka) {
    return 'Rp ' + angka.toLocaleString('id-ID');
}