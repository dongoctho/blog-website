# Blog CMS với FuelPHP

Hệ thống quản lý blog hiện đại được xây dựng bằng FuelPHP framework với giao diện đẹp và tính năng đầy đủ.

## Tính năng chính

### ✨ Quản lý bài viết
- ✅ Tạo, chỉnh sửa, xóa bài viết
- ✅ Phân loại theo danh mục (categories)
- ✅ Trạng thái xuất bản/bản nháp
- ✅ SEO-friendly URLs (slug)
- ✅ Tìm kiếm bài viết real-time

### 🖼️ Quản lý hình ảnh
- ✅ Upload multiple images
- ✅ Drag & drop interface
- ✅ Preview images trước khi upload
- ✅ Xóa images individual
- ✅ Image gallery cho mỗi bài viết

### 🎨 Giao diện hiện đại
- ✅ Responsive design (Bootstrap 5)
- ✅ Dark/Light theme support
- ✅ Beautiful animations và transitions
- ✅ Icon system (Bootstrap Icons)
- ✅ Mobile-friendly interface

### 🚀 Tính năng nâng cao
- ✅ Auto-save drafts
- ✅ Character counters
- ✅ Form validation
- ✅ Social sharing buttons
- ✅ Image lightbox/gallery
- ✅ Loading states và feedback

## Cài đặt và thiết lập

### 1. Yêu cầu hệ thống
- PHP 7.4+
- MySQL 5.7+ hoặc MariaDB
- Apache/Nginx với mod_rewrite
- Composer

### 2. Cài đặt database
```bash
# Tạo database
mysql -u root -p
CREATE DATABASE blog_cms DEFAULT CHARSET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

### 3. Cấu hình database
Cập nhật file `fuel/app/config/development/db.php`:
```php
return array(
    'default' => array(
        'connection' => array(
            'dsn'        => 'mysql:host=localhost;dbname=blog_cms',
            'username'   => 'your_username',
            'password'   => 'your_password',
        ),
    ),
);
```

### 4. Chạy migrations
```bash
# Di chuyển đến thư mục project
cd /path/to/project_fuel

# Chạy migrations để tạo tables
php oil refine migrate

# Hoặc chạy từng migration cụ thể
php oil refine migrate --version=012
```

### 5. Set permissions cho uploads
```bash
# Tạo thư mục uploads nếu chưa có
mkdir -p public/uploads
chmod 755 public/uploads
```

### 6. Cấu hình Apache/Nginx

#### Apache (.htaccess đã có sẵn)
Đảm bảo mod_rewrite được enable:
```bash
sudo a2enmod rewrite
sudo systemctl restart apache2
```

#### Nginx
Thêm vào config:
```nginx
location / {
    try_files $uri $uri/ /index.php?$query_string;
}

location /uploads/ {
    expires 1M;
    add_header Cache-Control "public, immutable";
}
```

## Sử dụng hệ thống

### Truy cập website
- **Trang chủ**: `http://yoursite.com/`
- **Tạo bài mới**: `http://yoursite.com/post/create`
- **Danh sách bài viết**: `http://yoursite.com/post`

### Accounts mặc định
Sau khi chạy migration 012, bạn sẽ có:

**Admin Account:**
- Username: `admin`
- Email: `admin@example.com` 
- Password: `123456`

**Editor Account:**
- Username: `editor`
- Email: `editor@example.com`
- Password: `123456`

### Tạo bài viết mới
1. Click **"Viết bài mới"** trên navbar
2. Nhập tiêu đề (tự động tạo slug)
3. Chọn danh mục
4. Viết nội dung
5. Upload hình ảnh (kéo thả hoặc chọn file)
6. Chọn "Xuất bản" hoặc lưu "Bản nháp"
7. Click **"Tạo bài viết"**

### Upload hình ảnh
- **Drag & Drop**: Kéo files vào vùng upload
- **Click to select**: Click nút "Chọn hình ảnh"
- **Preview**: Xem trước images trước khi upload
- **Remove**: Xóa images individual bằng nút X

### Quản lý bài viết
- **Xem**: Click "Đọc tiếp" hoặc tiêu đề bài viết
- **Chỉnh sửa**: Click icon ✏️ hoặc nút "Chỉnh sửa"
- **Xóa**: Click icon 🗑️ (có confirmation)
- **Tìm kiếm**: Dùng search box ở góc phải

## Cấu trúc thư mục

```
project_fuel/
├── fuel/app/
│   ├── classes/
│   │   ├── controller/post.php     # Controller chính
│   │   └── model/                  # Models (Post, Category, Image, User)
│   ├── views/
│   │   ├── template.php           # Layout chính
│   │   └── post/                  # Views cho posts
│   ├── migrations/                # Database migrations
│   └── config/routes.php          # URL routing
├── public/
│   ├── uploads/                   # Thư mục chứa images
│   └── index.php
└── README.md
```

## Tính năng nâng cao

### SEO-friendly URLs
- Bài viết: `/post/slug-bai-viet`
- Danh mục: `/category/ten-danh-muc`
- Tìm kiếm: `/search/tu-khoa`

### Auto-save Drafts
- Tự động lưu draft mỗi 30 giây
- Lưu manual bằng nút "Lưu nháp"
- Khôi phục draft khi reload page

### Image Gallery
- Single image: Hiển thị full-width
- Multiple images: Grid layout
- Lightbox modal để xem chi tiết
- Lazy loading cho performance

### Responsive Design
- Mobile-first approach
- Breakpoints: xs, sm, md, lg, xl
- Touch-friendly interfaces
- Optimized cho tất cả devices

## Troubleshooting

### Lỗi 500 - Internal Server Error
1. Check file permissions (755 cho folders, 644 cho files)
2. Check .htaccess file
3. Check PHP error logs
4. Verify database connection

### Images không upload được
1. Check permissions thư mục `public/uploads/` (755)
2. Check PHP upload settings trong php.ini:
   ```ini
   upload_max_filesize = 10M
   post_max_size = 10M
   max_file_uploads = 20
   ```
3. Check disk space

### CSS/JS không load
1. Check file paths trong template
2. Clear browser cache
3. Check server configuration

### Database connection errors
1. Verify database credentials
2. Check if database exists
3. Ensure MySQL service is running
4. Check firewall settings

## Customization

### Thay đổi theme colors
Edit CSS variables trong `fuel/app/views/template.php`:
```css
:root {
    --primary-color: #2c3e50;    /* Màu chính */
    --secondary-color: #3498db;  /* Màu phụ */
    --accent-color: #e74c3c;     /* Màu nhấn */
}
```

### Thêm fields cho posts
1. Tạo migration mới
2. Update Model_Post
3. Update views (create/edit forms)
4. Update controller validation

### Custom validation rules
Add trong controller `action_create()`:
```php
$val->add_field('title', 'Tiêu đề', 'required|min_length[5]|max_length[255]');
```

## Support

Nếu gặp vấn đề, hãy:
1. Check logs tại `fuel/app/logs/`
2. Enable debug mode trong `fuel/app/config/config.php`
3. Check documentation tại [FuelPHP.com](https://fuelphp.com/docs)

## License

Project này được phát hành dưới MIT License.

---

**Enjoy blogging! 🚀**
