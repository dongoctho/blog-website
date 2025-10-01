<!DOCTYPE html>
<html lang="vi">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title><?php echo $title; ?> - Blog CMS</title>
	
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
	<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
	
	<!-- Facebook Open Graph Meta Tags -->
	<meta property="og:url" content="<?php echo Uri::current(); ?>" />
	<meta property="og:type" content="website" />
	<meta property="og:title" content="<?php echo $title; ?> - Blog CMS" />
	<meta property="og:description" content="Hệ thống quản lý blog được xây dựng bằng FuelPHP" />
	<meta property="og:image" content="<?php echo Uri::base(); ?>uploads/logo.png" />
	
	<style>
		.nav-link {
			color: white;
		}

		body {
			padding-top: 70px;
		}

		.pagination {
			display: flex;
			gap: 8px;
			justify-content: center;
			margin: 20px 0;
			font-family: Arial, sans-serif;
		}

		.pagination span {
			display: inline-block;
		}

		.pagination span a {
			display: block;
			padding: 8px 12px;
			text-decoration: none;
			color: #007bff;
			border: 1px solid #007bff;
			border-radius: 4px;
			transition: background-color 0.3s ease;
		}

		.pagination span a:hover {
			background-color: #007bff;
			color: white;
		}

		.pagination span.active a {
			background-color: #007bff;
			color: white;
			cursor: default;
		}

		.pagination span.previous-inactive a,
		.pagination span.next-inactive a {
			color: #ccc;
			border-color: #ccc;
			cursor: default;
			pointer-events: none;
		}

		:root {
			--primary-color: #2c3e50;
			--secondary-color: #3498db;
			--accent-color: #e74c3c;
			--light-gray: #f8f9fa;
			--dark-gray: #6c757d;
		}
		
		body {
			background: repeating-linear-gradient(90deg, #fff, #fff 0.4rem, #fef7f9 0.3rem, #fef7f9 0.7rem);
			background-size: 20px 100%;
			font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
		}
		
		.navbar {
			background: #FAACC8;
			box-shadow: 0 2px 15px rgba(102, 126, 234, 0.3);
			backdrop-filter: blur(10px);
			position: relative;
			z-index: 2000;
			position: fixed;
			top: 0;
			left: 0;
			right: 0;
			width: 100%;
		}
		
		.navbar-brand {
			font-weight: bold;
			font-size: 1.5rem;
		}
		
		.main-container {
			margin-top: 2rem;
			margin-bottom: 3rem;
			position: relative;
		}
		
		.main-container::before {
			content: '';
			position: absolute;
			top: -50px;
			left: -50px;
			right: -50px;
			bottom: -50px;
			pointer-events: none;
			z-index: -1;
		}
		
		.card {
			border: none;
			border-radius: 15px;
			box-shadow: 0 5px 15px rgba(0,0,0,0.08);
			transition: all 0.3s ease;
		}
		
		.card:hover {
			transform: translateY(-5px);
			box-shadow: 0 8px 25px rgba(0,0,0,0.15);
		}
		
		.btn-primary {
			background: linear-gradient(135deg, var(--secondary-color), #2980b9);
			border: none;
			border-radius: 25px;
			padding: 0.5rem 1.5rem;
			font-weight: 500;
		}
		
		.btn-primary:hover {
			background: linear-gradient(135deg, #2980b9, var(--secondary-color));
			transform: translateY(-2px);
		}
		
		.alert {
			border: none;
			border-radius: 10px;
			margin-bottom: 1.5rem;
		}
		
		.page-header {
			background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
			color: white;
			padding: 3rem 0;
			margin-bottom: 2rem;
			border-radius: 15px;
		}
		
		.footer {
			background: #FAACC8;
			color: white;
			padding: 2rem 0;
			margin-top: 4rem;
		}
		
		.nav-pills .nav-link.active {
			background: linear-gradient(135deg, var(--secondary-color), #2980b9);
			border-radius: 25px;
		}
		
		.form-control, .form-select {
			border-radius: 10px;
			border: 2px solid #e9ecef;
			padding: 0.75rem;
		}
		
		.form-control:focus, .form-select:focus {
			border-color: var(--secondary-color);
			box-shadow: 0 0 0 0.2rem rgba(52, 152, 219, 0.25);
		}
		
		.image-gallery {
			display: grid;
			grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
			gap: 1rem;
			margin: 1rem 0;
		}
		
		.image-item {
			position: relative;
			border-radius: 10px;
			overflow: hidden;
			box-shadow: 0 3px 10px rgba(0,0,0,0.1);
		}
		
		.image-item img {
			width: 100%;
			height: 150px;
			object-fit: cover;
		}
		
		.image-item .delete-btn {
			position: absolute;
			top: 5px;
			right: 5px;
			background: rgba(231, 76, 60, 0.9);
			color: white;
			border: none;
			border-radius: 50%;
			width: 30px;
			height: 30px;
			display: none;
		}
		
		.image-item:hover .delete-btn {
			display: block;
		}
		
		.post-meta {
			color: var(--dark-gray);
			font-size: 0.9rem;
		}
		
		.post-excerpt {
			color: #6c757d;
			line-height: 1.6;
		}
		
		.category-badge {
			background: linear-gradient(135deg, var(--accent-color), #c0392b);
			color: white;
			padding: 0.25rem 0.75rem;
			border-radius: 15px;
			font-size: 0.8rem;
		}
		
		@media (max-width: 768px) {
			.main-container {
				margin-top: 1rem;
			}
			
			.page-header {
				padding: 2rem 0;
			}
		}
		
		.loading {
			opacity: 0.7;
			pointer-events: none;
		}
		
		::-webkit-scrollbar {
			width: 8px;
		}
		
		::-webkit-scrollbar-track {
			background: #f1f1f1;
		}
		
		::-webkit-scrollbar-thumb {
			background: var(--secondary-color);
			border-radius: 4px;
		}
		
		::-webkit-scrollbar-thumb:hover {
			background: #2980b9;
		}
		
		/* Advertisement Sidebar Styling */
		.ad-sidebar {
			position: sticky;
			top: 20px;
			padding: 0 10px;
			margin-top: 20px;
			z-index: 1;
		}
		
		.ad-banner {
			width: 100%;
		}
		
		.ad-placeholder {
			width: 100%;
			height: 250px;
			background: #f8f9fa;
			border: 2px solid #dee2e6;
			border-radius: 8px;
			display: flex;
			align-items: center;
			justify-content: center;
			transition: all 0.3s ease;
			cursor: pointer;
			position: relative;
			overflow: hidden;
		}
		
		.ad-placeholder:hover {
			border-color: #FAACC8;
			transform: translateY(-2px);
			box-shadow: 0 4px 15px rgba(0,0,0,0.1);
		}
		
		.ad-content {
			text-align: center;
			color: #6c757d;
		}
		
		.ad-content i {
			opacity: 0.6;
			margin-bottom: 10px;
		}
		
		.ad-placeholder::before {
			content: '';
			position: absolute;
			top: 0;
			left: -100%;
			width: 100%;
			height: 100%;
			background: linear-gradient(90deg, transparent, rgba(255,255,255,0.4), transparent);
			transition: left 0.5s;
		}
		
		.ad-placeholder:hover::before {
			left: 100%;
		}
		
		/* Mobile responsive for sidebars */
		@media (max-width: 991.98px) {
			.ad-sidebar {
				display: none !important;
			}
			
			.col-lg-8 {
				width: 100%;
				max-width: 100%;
			}
		}
		
		/* Tablet responsive */
		@media (max-width: 1199.98px) and (min-width: 992px) {
			.ad-sidebar {
				padding: 0 5px;
			}
			
			.ad-placeholder {
				height: 200px;
			}
		}
		
		/* Search wrapper styling */
		.search-wrapper {
			display: flex;
			justify-content: flex-end;
			align-items: flex-end;
			height: 100%;
		}
		
		.search-form {
			width: 100%;
			max-width: 300px;
			margin-right: 115px;
		}
		
		.search-form .input-group {
			box-shadow: 0 2px 10px rgba(0,0,0,0.1);
			border-radius: 25px;
			overflow: hidden;
		}
		
		.search-form .form-control {
			border: 2px solid #FAACC8;
			border-right: none;
			border-radius: 25px 0 0 25px;
			padding: 0.75rem 1rem;
			font-size: 0.9rem;
		}
		
		.search-form .form-control:focus {
			border-color: #FAACC8;
			box-shadow: 0 0 0 0.2rem rgba(250, 172, 200, 0.25);
		}
		
		.search-form .btn {
			border: 2px solid #FAACC8;
			border-left: none;
			border-radius: 0 25px 25px 0;
			background: #FAACC8;
			color: white;
			padding: 0.75rem 1rem;
			transition: all 0.3s ease;
		}
		
		.search-form .btn:hover {
			background: #f8a0c0;
			border-color: #f8a0c0;
			transform: translateY(-1px);
		}
		
		/* Category grid styling */
		.category-grid {
			display: grid;
			grid-template-columns: repeat(4, 1fr);
			gap: 12px;
			justify-content: center;
		}
		
		.category-tile {
			display: flex;
			align-items: center;
			justify-content: center;
			padding: 12px 8px;
			border-radius: 8px;
			background: #ffffff;
			color: #333;
			font-weight: 500;
			cursor: pointer;
			transition: all .3s ease;
			box-shadow: 0 1px 3px rgba(0,0,0,0.1);
			width: 100%;
			height: 50px;
			font-size: 0.9rem;
		}
		
		.category-tile:hover { 
			transform: translateY(-2px);
			box-shadow: 0 4px 12px rgba(0,0,0,0.15);
			border-width: 3px;
		}
		
		.category-tile.active {
			background: linear-gradient(135deg, var(--tile-color-1), var(--tile-color-2));
			color: #fff;
			border-color: transparent;
			transform: translateY(-1px);
			box-shadow: 0 6px 16px rgba(0,0,0,0.2);
		}
		
		.category-name { 
			white-space: nowrap; 
			font-size: 0.9rem;
		}
		
		/* Featured posts styling */
		.featured-post-card {
			background: rgba(255, 255, 255, 0.9);
			backdrop-filter: blur(10px);
			box-shadow: 0 4px 20px rgba(0,0,0,0.1);
			transition: all .3s ease;
			border: 2px solid #FAACC8;
		}
		
		.featured-post-card:hover {
			transform: translateY(-5px);
			box-shadow: 0 8px 30px rgba(0,0,0,0.15);
			background: rgba(255, 255, 255, 0.95);
		}
		
		/* Regular posts styling */
		.post-card {
			background: rgba(255, 255, 255, 0.9);
			backdrop-filter: blur(10px);
			box-shadow: 0 4px 20px rgba(0,0,0,0.1);
			transition: all .3s ease;
			border: 2px solid #FAACC8;
		}
		
		.post-card:hover {
			transform: translateY(-3px);
			box-shadow: 0 6px 25px rgba(0,0,0,0.12);
			background: rgba(255, 255, 255, 0.95);
		}
		
		/* Responsive design for search and categories */
		@media (max-width: 768px) {
			.category-grid {
				grid-template-columns: repeat(2, 1fr);
				gap: 10px;
				padding: 15px;
			}
			
			.category-tile {
				height: 45px;
				padding: 10px 6px;
				font-size: 0.85rem;
			}
			
			.search-wrapper {
				justify-content: center;
				margin-top: 1rem;
			}
			
			.search-form {
				max-width: 100%;
			}
		}
		
		@media (max-width: 576px) {
			.category-grid {
				grid-template-columns: 1fr;
				gap: 8px;
				padding: 12px;
			}
			
			.category-tile {
				height: 40px;
				padding: 8px 4px;
				font-size: 0.8rem;
			}
			
			.search-form .form-control,
			.search-form .btn {
				padding: 0.6rem 0.8rem;
				font-size: 0.85rem;
			}
		}
		
		/* Chatbot Styling */
		.chatbot-container {
			position: fixed;
			bottom: 20px;
			right: 20px;
			width: 350px;
			height: 500px;
			background: white;
			border-radius: 15px;
			box-shadow: 0 8px 30px rgba(0,0,0,0.15);
			z-index: 9999;
			display: none;
			flex-direction: column;
			overflow: hidden;
			border: 2px solid #FAACC8;
		}
		
		.chatbot-container.active {
			display: flex;
		}
		
		.chatbot-header {
			background: linear-gradient(135deg, #FAACC8, #f8a0c0);
			color: white;
			padding: 15px 20px;
			display: flex;
			justify-content: space-between;
			align-items: center;
			font-weight: 600;
		}
		
		.chatbot-header h6 {
			margin: 0;
			font-size: 1rem;
		}
		
		.chatbot-close {
			background: none;
			border: none;
			color: white;
			font-size: 1.2rem;
			cursor: pointer;
			padding: 0;
			width: 25px;
			height: 25px;
			display: flex;
			align-items: center;
			justify-content: center;
			border-radius: 50%;
			transition: background-color 0.3s ease;
		}
		
		.chatbot-close:hover {
			background-color: rgba(255,255,255,0.2);
		}
		
		.chatbot-messages {
			flex: 1;
			padding: 20px;
			overflow-y: auto;
			background: #f8f9fa;
			display: flex;
			flex-direction: column;
			gap: 15px;
		}
		
		.chatbot-message {
			max-width: 80%;
			padding: 12px 16px;
			border-radius: 18px;
			font-size: 0.9rem;
			line-height: 1.4;
			word-wrap: break-word;
		}
		
		.chatbot-message.bot {
			background: #e9ecef;
			color: #333;
			align-self: flex-start;
			border-bottom-left-radius: 5px;
		}
		
		.chatbot-message.user {
			background: linear-gradient(135deg, #FAACC8, #f8a0c0);
			color: white;
			align-self: flex-end;
			border-bottom-right-radius: 5px;
		}
		
		/* Markdown content inside bot message */
		.chatbot-message.bot ul,
		.chatbot-message.bot ol {
			padding-left: 1.2rem;
			margin: 0.25rem 0;
		}
		.chatbot-message.bot p { margin: 0.25rem 0; }
		.chatbot-message.bot h1,
		.chatbot-message.bot h2,
		.chatbot-message.bot h3 {
			margin: 0.3rem 0;
			font-size: 1rem;
			font-weight: 600;
		}
		.chatbot-message.bot code { background:#f1f3f5; padding:0 4px; border-radius:4px; }
		
		/* Skeleton loading animation */
		.chatbot-skeleton {
			background: #e9ecef;
			border-radius: 18px;
			align-self: flex-start;
			border-bottom-left-radius: 5px;
			position: relative;
			overflow: hidden;
		}
		
		.chatbot-skeleton::before {
			content: '';
			position: absolute;
			top: 0;
			left: -100%;
			width: 100%;
			height: 100%;
			background: linear-gradient(90deg, transparent, rgba(255,255,255,0.6), transparent);
			animation: skeleton-loading 1.5s infinite;
		}
		
		@keyframes skeleton-loading {
			0% {
				left: -100%;
			}
			100% {
				left: 100%;
			}
		}
		
		.chatbot-skeleton-content {
			padding: 12px 16px;
			display: flex;
			gap: 8px;
			align-items: center;
		}
		
		.chatbot-skeleton-dot {
			width: 8px;
			height: 8px;
			background: #dee2e6;
			border-radius: 50%;
			animation: skeleton-dot 1.4s infinite ease-in-out both;
		}
		
		.chatbot-skeleton-dot:nth-child(1) { animation-delay: -0.32s; }
		.chatbot-skeleton-dot:nth-child(2) { animation-delay: -0.16s; }
		.chatbot-skeleton-dot:nth-child(3) { animation-delay: 0s; }
		
		@keyframes skeleton-dot {
			0%, 80%, 100% {
				transform: scale(0);
			}
			40% {
				transform: scale(1);
			}
		}
		
		.chatbot-input-container {
			padding: 15px 20px;
			background: white;
			border-top: 1px solid #e9ecef;
			display: flex;
			gap: 10px;
		}
		
		.chatbot-input {
			flex: 1;
			border: 2px solid #e9ecef;
			border-radius: 25px;
			padding: 10px 15px;
			font-size: 0.9rem;
			outline: none;
			transition: border-color 0.3s ease;
		}
		
		.chatbot-input:focus {
			border-color: #FAACC8;
		}
		
		.chatbot-send {
			background: linear-gradient(135deg, #FAACC8, #f8a0c0);
			border: none;
			border-radius: 50%;
			width: 40px;
			height: 40px;
			color: white;
			cursor: pointer;
			display: flex;
			align-items: center;
			justify-content: center;
			transition: all 0.3s ease;
		}
		
		.chatbot-send:hover {
			transform: scale(1.05);
			box-shadow: 0 4px 15px rgba(250, 172, 200, 0.4);
		}
		
		.chatbot-send:disabled {
			opacity: 0.6;
			cursor: not-allowed;
			transform: none;
		}
		
		.chatbot-send:disabled:hover {
			transform: none;
			box-shadow: none;
		}
		
		.chatbot-input:disabled {
			opacity: 0.6;
			cursor: not-allowed;
		}
		
		.chatbot-toggle {
			position: fixed;
			bottom: 20px;
			right: 20px;
			width: 60px;
			height: 60px;
			background: linear-gradient(135deg, #FAACC8, #f8a0c0);
			border: none;
			border-radius: 50%;
			color: white;
			font-size: 1.5rem;
			cursor: pointer;
			box-shadow: 0 4px 20px rgba(250, 172, 200, 0.4);
			z-index: 10000;
			transition: all 0.3s ease;
			display: flex;
			align-items: center;
			justify-content: center;
		}
		
		.chatbot-toggle:hover {
			transform: scale(1.1);
			box-shadow: 0 6px 25px rgba(250, 172, 200, 0.6);
		}
		
		.chatbot-toggle.hidden {
			display: none;
		}
		
		/* Mobile responsive for chatbot */
		@media (max-width: 576px) {
			.chatbot-container {
				width: calc(100vw - 20px);
				height: calc(100vh - 100px);
				bottom: 10px;
				right: 10px;
				left: 10px;
			}
			
			.chatbot-toggle {
				bottom: 15px;
				right: 15px;
				width: 55px;
				height: 55px;
				font-size: 1.3rem;
			}
		}
	</style>
</head>
<?php
use Fuel\Core\Uri;
use Fuel\Core\Session;
use Fuel\Core\Fuel;
?>
<body>
	<nav class="navbar navbar-expand-lg navbar-dark">
		<div class="container">
			<a class="navbar-brand" href="<?php echo Uri::base(); ?>">
				<i class="bi bi-bootstrap-fill"></i> Blog CMS
			</a>
			
			<button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
				<span class="navbar-toggler-icon"></span>
			</button>
			
			<div class="collapse navbar-collapse" id="navbarNav">
				<ul class="navbar-nav me-auto">
					<li class="nav-item">
						<a class="nav-link" href="<?php echo Uri::create('post'); ?>">
							<i class="bi bi-house"></i> Trang chủ
						</a>
					</li>
					<li class="nav-item">
						<a class="nav-link" href="<?php echo Uri::create('discussion'); ?>">
							<i class="bi bi-chat-fill"></i> Thảo luận
						</a>
					</li>
				</ul>
				
				<ul class="navbar-nav">
					<?php if (Auth::check()): ?>
						<!-- User đã đăng nhập -->
						<li class="nav-item dropdown">
							<a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
								<i class="bi bi-person-circle"></i>
								<?php echo Auth::get('username'); ?>
							</a>
							<ul class="dropdown-menu">
								<li>
									<a class="dropdown-item" href="<?php echo Uri::create('admin'); ?>">
										<i class="bi bi-speedometer2"></i> Dashboard
									</a>
								</li>
								<li>
									<a class="dropdown-item" href="#">
										<i class="bi bi-person"></i> Hồ sơ
									</a>
								</li>
								<li><hr class="dropdown-divider"></li>
								<li>
									<a class="dropdown-item" href="<?php echo Uri::create('auth/logout'); ?>">
										<i class="bi bi-box-arrow-right"></i> Đăng xuất
									</a>
								</li>
							</ul>
						</li>
					<?php else: ?>
						<!-- User chưa đăng nhập -->
						<li class="nav-item">
							<a class="nav-link" href="<?php echo Uri::create('auth/login'); ?>">
								<i class="bi bi-box-arrow-in-right"></i> Đăng nhập
							</a>
						</li>
					<?php endif; ?>
				</ul>
			</div>
		</div>
	</nav>
<?php
$banner_ads_1 = [
	'1' => 'uploads/1.png',
	'2' => 'uploads/2.png',
	'3' => 'uploads/3.png',
	'4' => 'uploads/4.png',
	'5' => 'uploads/5.png',
	'6' => 'uploads/6.png',
];

$banner_ads_2 = [
	'1' => 'uploads/7.png',
	'2' => 'uploads/8.png',
	'3' => 'uploads/9.png',
	'4' => 'uploads/10.png',
	'5' => 'uploads/11.png',
	'6' => 'uploads/12.png',
];

?>
	<!-- Main Content Layout with Sidebars -->
	<div class="container-fluid">
		<div class="row">
			<!-- Left Sidebar - Advertisements -->
			<div class="col-lg-2 col-md-3 d-none d-lg-block">
				<div class="ad-sidebar">
					<?php foreach ($banner_ads_1 as $key => $value): ?>
					<div class="ad-banner mb-3">
						<div class="ad-placeholder">
							<div class="ad-content">
								<img src="<?php echo Uri::base() . $value; ?>" alt="Banner <?php echo $key; ?>" style="width: 100%; height: 100%; object-fit: cover; border-radius: 8px;">
							</div>
						</div>
					</div>
					<?php endforeach; ?>
				</div>
			</div>

			<!-- Main Content -->
			<div class="col-lg-8 col-md-9">
				<div class="main-container">
					<?php echo $content; ?>
				</div>
			</div>

			<!-- Right Sidebar - Advertisements -->
			<div class="col-lg-2 col-md-3 d-none d-lg-block">
				<div class="ad-sidebar">
					<?php foreach ($banner_ads_2 as $key => $value): ?>
					<div class="ad-banner mb-3">
						<div class="ad-placeholder">
							<div class="ad-content">
								<img src="<?php echo Uri::base() . $value; ?>" alt="Banner <?php echo $key; ?>" style="width: 100%; height: 100%; object-fit: cover; border-radius: 8px;">
							</div>
						</div>
					</div>
					<?php endforeach; ?>
				</div>
			</div>
		</div>
	</div>

	<footer class="footer">
		<div class="container">
			<div class="row">
				<div class="col-md-6">
					<h5><i class="bi bi-bootstrap-fill"></i> Blog CMS</h5>
					<p class="mb-0">Hệ thống quản lý blog được xây dựng bằng FuelPHP</p>
				</div>
				<div class="col-md-6 text-md-end">
					<p class="mb-1">
						<small>Thời gian xử lý: {exec_time}s | Bộ nhớ sử dụng: {mem_usage}mb</small>
					</p>
					<p class="mb-0">
						<small>Powered by <a href="https://fuelphp.com" class="text-decoration-none text-light">FuelPHP <?php echo e(Fuel::VERSION); ?></a></small>
					</p>
				</div>
			</div>
		</div>
	</footer>

	<!-- Chatbot Toggle Button -->
	<button class="chatbot-toggle hidden" id="chatbotToggle" title="Mở chatbot">
		<i class="bi bi-chat-dots"></i>
	</button>

	<!-- Chatbot Container -->
	<div class="chatbot-container active" id="chatbotContainer">
		<div class="chatbot-header">
			<h6><i class="bi bi-robot"></i> ThoGPT</h6>
			<button class="chatbot-close" id="chatbotClose" title="Đóng chatbot">
				<i class="bi bi-x"></i>
			</button>
		</div>
		
		<div class="chatbot-messages" id="chatbotMessages">
			<div class="chatbot-message bot">
				Xin chào! Tôi là ThoGPT. Tôi có thể giúp gì cho bạn?
			</div>
		</div>
		
		<div class="chatbot-input-container">
			<input type="text" class="chatbot-input" id="chatbotInput" placeholder="Nhập tin nhắn của bạn..." maxlength="500">
			<button class="chatbot-send" id="chatbotSend" title="Gửi tin nhắn">
				<i class="bi bi-send"></i>
			</button>
		</div>
	</div>

	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
	<!-- Markdown render + sanitize -->
	<script src="https://cdn.jsdelivr.net/npm/marked/marked.min.js"></script>
	<script src="https://cdn.jsdelivr.net/npm/dompurify@3.1.7/dist/purify.min.js"></script>
	
	<!-- Facebook SDK for JavaScript -->
	<div id="fb-root"></div>
	<script>(function(d, s, id) {
		var js, fjs = d.getElementsByTagName(s)[0];
		if (d.getElementById(id)) return;
		js = d.createElement(s); js.id = id;
		js.src = "https://connect.facebook.net/vi_VN/sdk.js#xfbml=1&version=v18.0";
		fjs.parentNode.insertBefore(js, fjs);
	}(document, 'script', 'facebook-jssdk'));</script>
	
	<!-- CKEditor 5 CDN chính thức -->
	
	<script>
		setTimeout(function() {
			var alerts = document.querySelectorAll('.alert');
			alerts.forEach(function(alert) {
				var bsAlert = new bootstrap.Alert(alert);
				bsAlert.close();
			});
		}, 5000);
		
		document.querySelectorAll('a[href^="#"]').forEach(anchor => {
			anchor.addEventListener('click', function (e) {
				e.preventDefault();
				document.querySelector(this.getAttribute('href')).scrollIntoView({
					behavior: 'smooth'
				});
			});
		});
		
		document.querySelectorAll('form').forEach(form => {
			form.addEventListener('submit', function() {
				var submitBtn = form.querySelector('button[type="submit"]');
			});
		});
		
		// Chatbot functionality
		document.addEventListener('DOMContentLoaded', function() {
			const chatbotToggle = document.getElementById('chatbotToggle');
			const chatbotContainer = document.getElementById('chatbotContainer');
			const chatbotClose = document.getElementById('chatbotClose');
			const chatbotInput = document.getElementById('chatbotInput');
			const chatbotSend = document.getElementById('chatbotSend');
			const chatbotMessages = document.getElementById('chatbotMessages');
			
			// Mở/đóng chatbot
			chatbotToggle.addEventListener('click', function() {
				chatbotContainer.classList.add('active');
				chatbotToggle.classList.add('hidden');
				chatbotInput.focus();
			});
			
			chatbotClose.addEventListener('click', function() {
				chatbotContainer.classList.remove('active');
				chatbotToggle.classList.remove('hidden');
			});
			
			// Gửi tin nhắn
			function sendMessage() {
				const message = chatbotInput.value.trim();
				if (message === '') return;
				
				// Disable input và button khi đang gửi
				setLoadingState(true);
				
				// Thêm tin nhắn của user
				addMessage(message, 'user');
				chatbotInput.value = '';
				
				// Hiển thị skeleton loading
				showSkeletonLoading();
				
				// Gọi API ChatGPT
				callChatGPTAPI(message);
			}
			
			// Set loading state
			function setLoadingState(isLoading) {
				chatbotInput.disabled = isLoading;
				chatbotSend.disabled = isLoading;
				
				if (isLoading) {
					chatbotSend.innerHTML = '<i class="bi bi-hourglass-split"></i>';
				} else {
					chatbotSend.innerHTML = '<i class="bi bi-send"></i>';
				}
			}
			
			// Hiển thị skeleton loading
			function showSkeletonLoading() {
				const skeletonDiv = document.createElement('div');
				skeletonDiv.className = 'chatbot-skeleton';
				skeletonDiv.id = 'chatbotSkeleton';
				skeletonDiv.innerHTML = `
					<div class="chatbot-skeleton-content">
						<div class="chatbot-skeleton-dot"></div>
						<div class="chatbot-skeleton-dot"></div>
						<div class="chatbot-skeleton-dot"></div>
					</div>
				`;
				chatbotMessages.appendChild(skeletonDiv);
				chatbotMessages.scrollTop = chatbotMessages.scrollHeight;
			}
			
			// Ẩn skeleton loading
			function hideSkeletonLoading() {
				const skeleton = document.getElementById('chatbotSkeleton');
				if (skeleton) {
					skeleton.remove();
				}
			}
			
			// Gọi API ChatGPT
			async function callChatGPTAPI(message) {
				try {
					const response = await fetch('<?php echo Uri::create("chatbot/send_message"); ?>', {
						method: 'POST',
						headers: {
							'Content-Type': 'application/x-www-form-urlencoded',
						},
						body: 'message=' + encodeURIComponent(message)
					});
					
					const data = await response.json();
					
					// Ẩn skeleton loading và bật lại input
					hideSkeletonLoading();
					setLoadingState(false);
					
					if (data.success) {
						// Hiển thị phản hồi từ ChatGPT
						addMessage(data.message, 'bot');
					} else {
						// Hiển thị lỗi
						addMessage(data.error || 'Có lỗi xảy ra khi xử lý tin nhắn.', 'bot');
					}
					
				} catch (error) {
					// Ẩn skeleton loading và bật lại input
					hideSkeletonLoading();
					setLoadingState(false);
					
					// Hiển thị lỗi kết nối
					addMessage('Xin lỗi, không thể kết nối đến server. Vui lòng thử lại sau.', 'bot');
					console.error('ChatGPT API Error:', error);
				}
			}
			
			// Thêm tin nhắn vào chat
			function addMessage(text, sender) {
				const messageDiv = document.createElement('div');
				messageDiv.className = `chatbot-message ${sender}`;
				if (sender === 'bot') {
					// Render Markdown an toàn
					const html = marked.parse(text || '');
					messageDiv.innerHTML = DOMPurify.sanitize(html);
				} else {
					messageDiv.textContent = text;
				}
				chatbotMessages.appendChild(messageDiv);
				chatbotMessages.scrollTop = chatbotMessages.scrollHeight;
			}
			
			// Xử lý sự kiện gửi tin nhắn
			chatbotSend.addEventListener('click', sendMessage);
			
			chatbotInput.addEventListener('keypress', function(e) {
				if (e.key === 'Enter') {
					sendMessage();
				}
			});
		});
	</script>
</body>
</html>
