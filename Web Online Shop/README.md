# 🛒 Web Online Shop - Tokopedia Clone

A full-featured e-commerce web application built with PHP, MySQL, HTML, CSS, and JavaScript. This project is inspired by Tokopedia's design and functionality, providing a complete online shopping experience.

## ✨ Features

### 🏠 Frontend (User)
- **Modern UI** - Tokopedia-inspired design with green theme
- **Product Catalog** - Grid view with 5 columns, product cards with images, prices, ratings
- **Product Search** - Search products by name or description
- **Category Filter** - Filter products by category (Laptop, Keyboard, Mouse, etc.)
- **Sorting** - Sort by newest, bestseller, cheapest, most expensive
- **Pagination** - Navigate through product pages
- **Product Detail** - Image gallery, rating stars, quantity selector, add to cart
- **Shopping Cart** - Add/update/delete items, real-time total calculation via AJAX
- **Wishlist** - Save favorite products with heart button toggle
- **Checkout** - Select shipping address, order summary, place order
- **Order History** - View all orders with status tracking
- **User Profile** - Edit profile, change password, manage addresses
- **Reviews & Ratings** - Rate products (1-5 stars) and write reviews
- **Authentication** - Login/Register with modern card design
- **Responsive Design** - Works on desktop, tablet, and mobile

### 👑 Admin Dashboard
- **Statistics** - Total products, users, orders, revenue
- **Product Management** - Add, edit, delete products with categories
- **Order Management** - Update order status (process/ship/complete/cancel)
- **User Management** - View all registered users

### ⚡ Technical Features
- **AJAX** - Real-time cart updates, wishlist toggle, review submission
- **Password Hashing** - Secure bcrypt password storage
- **Session Management** - User authentication and admin role protection
- **Image Upload** - Product image management
- **Pagination** - Efficient product browsing

## 🖥️ Screenshots

*(Add your screenshots here)*

## 🛠️ Technologies Used

- **Backend:** PHP (Native)
- **Database:** MySQL
- **Frontend:** HTML5, CSS3, JavaScript
- **Icons:** Font Awesome 6
- **Server:** XAMPP / Apache

## 📋 Database Structure

### Tables
- `users` - User accounts (admin & customer)
- `produk` - Products with categories, stock, ratings
- `keranjang` - Shopping cart items
- `pesanan` - Orders with status tracking
- `detail_pesanan` - Order line items
- `wishlist` - User wishlist
- `alamat` - User shipping addresses
- `review` - Product reviews & ratings
- `ongkir` - Shipping cost data

## 🚀 Installation

### Prerequisites
- XAMPP (or any PHP + MySQL server)
- Web browser

### Steps

1. **Clone the repository**
   ```bash
   git clone https://github.com/yourusername/web-online-shop.git
   ```

2. **Move to XAMPP htdocs**
   ```bash
   cp -r web-online-shop C:/xampp/htdocs/
   ```

3. **Import Database**
   - Open phpMyAdmin (http://localhost/phpmyadmin)
   - Create database `toko_online`
   - Import `database.sql` (if available) or run the SQL schema below

4. **Configure Database**
   - Open `config/koneksi.php`
   - Adjust database credentials if needed

5. **Run the Application**
   - Open browser and go to: `http://localhost/Web%20Online%20Shop/`

## 🔑 Default Accounts

| Role  | Email                 | Password |
|-------|-----------------------|----------|
| Admin | admin@gmail.com       | 12345    |
| User  | haikalcoy137@gmail.com | Haikal   |

## 📁 Project Structure

```
Web Online Shop/
├── index.php                 # Homepage
├── header.php                # Global header (navbar)
├── footer.php                # Global footer
├── produk.php                # Product listing
├── detail_produk.php         # Product detail page
├── keranjang.php             # Shopping cart
├── checkout.php              # Checkout page
├── wishlist.php              # Wishlist page
├── profil.php                # User profile
├── pesanan_saya.php          # Order history
├── login.php                 # Login page
├── register.php              # Register page
├── logout.php                # Logout handler
├── proses_login.php          # Login processor
├── proses_register.php       # Register processor
├── ajax_*.php                # AJAX handlers
│
├── admin/                    # Admin panel
│   ├── index.php             # Dashboard
│   ├── tambah_produk.php     # Add product
│   ├── edit_produk.php       # Edit product
│   ├── simpan_produk.php     # Save product
│   ├── update_produk.php     # Update product
│   ├── hapus_produk.php      # Delete product
│   ├── pesanan.php           # Order management
│   └── user.php              # User management
│
├── Assets/
│   ├── css/
│   │   └── asset.css         # Main stylesheet
│   ├── js/
│   │   └── script.js         # JavaScript & AJAX
│   └── images/               # Product images
│
└── config/
    └── koneksi.php           # Database connection
```

## 🎯 Key Features Explained

### Product Cards
- 5-column responsive grid
- Hover effects with image zoom
- Wishlist heart button (AJAX)
- Discount badge
- Price with original price strikethrough
- Rating stars and sold count

### Shopping Cart
- Real-time quantity update via AJAX
- Delete items with animation
- Subtotal calculation
- Checkout button with order summary

### Checkout Process
- Select shipping address
- Review ordered items
- Place order (updates stock & creates order record)
- Redirects to order history

### Admin Dashboard
- Statistics cards (products, users, orders, revenue)
- Product table with edit/delete actions
- Order management with status workflow:
  - Pending → Process → Ship → Complete
  - Or Cancel anytime

## 🤝 Contributing

Feel free to fork this project and submit pull requests. You can also open issues for bugs or feature requests.

## 📄 License

This project is open source and available under the [MIT License](LICENSE).

## 👨‍💻 Author

**Haikal** - [GitHub Profile](https://github.com/yourusername)

---

⭐ Star this repository if you find it useful!