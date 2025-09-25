# Hướng dẫn Setup Google OAuth2 Login

## Bước 1: Cài đặt thư viện
```bash
composer install
```

## Bước 2: Tạo Google Cloud Project

1. Truy cập [Google Cloud Console](https://console.cloud.google.com/)
2. Tạo project mới hoặc chọn project hiện có
3. Enable Google+ API:
   - Vào "APIs & Services" > "Library"
   - Tìm "Google+ API" và enable

## Bước 3: Tạo OAuth 2.0 Credentials

1. Vào "APIs & Services" > "Credentials"
2. Click "Create Credentials" > "OAuth client ID"
3. Chọn "Web application"
4. Thiết lập:
   - **Name**: FuelPHP Blog App
   - **Authorized JavaScript origins**: 
     - `http://localhost`
     - `http://your-domain.com`
   - **Authorized redirect URIs**:
     - `http://localhost/auth/google/callback`
     - `http://your-domain.com/auth/google/callback`

## Bước 4: Cập nhật Configuration

Mở file `fuel/app/config/google.php` và cập nhật:

```php
return array(
    'client_id'     => 'YOUR_ACTUAL_CLIENT_ID.apps.googleusercontent.com',
    'client_secret' => 'YOUR_ACTUAL_CLIENT_SECRET',
    'redirect_uri'  => 'http://your-domain.com/auth/google/callback',
    // ... phần còn lại giữ nguyên
);
```

## Bước 5: Chạy Migration

```bash
php oil refine migrate
```

## Bước 6: Test Google Login

1. Vào trang `/login`
2. Click "Đăng nhập với Google"
3. Đăng nhập với Google account
4. Kiểm tra user được tạo trong database

## Cấu trúc Database

Migration sẽ thêm các cột sau vào bảng `users`:

- `google_id` - Google User ID
- `google_avatar` - URL avatar từ Google
- `is_google_account` - Flag đánh dấu account Google
- `google_access_token` - Access token (để mở rộng tương lai)
- `google_refresh_token` - Refresh token (để mở rộng tương lai)

## Luồng hoạt động

1. User click "Đăng nhập với Google"
2. Redirect đến Google OAuth
3. User authorize ở Google
4. Google redirect về `/auth/google/callback` với code
5. Server exchange code với access token
6. Lấy thông tin user từ Google
7. Tìm hoặc tạo user trong database
8. Đăng nhập user vào hệ thống

## Xử lý lỗi

- Check logs tại `fuel/app/logs/`
- Đảm bảo redirect URI khớp chính xác
- Kiểm tra Google+ API đã enable
- Verify client_id và client_secret

## Security Notes

- Đừng commit client_secret vào git
- Sử dụng environment variables cho production
- Định kỳ rotate credentials
- Chỉ cho phép domain tin cậy trong redirect URIs
