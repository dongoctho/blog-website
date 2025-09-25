<!DOCTYPE html>
<html lang="vi">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title><?php echo isset($title) ? $title : 'Admin'; ?> - Blog CMS Admin</title>
	
	<!-- Bootstrap 5 -->
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
	<!-- Bootstrap Icons -->
	<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
	<!-- Chart.js -->
	<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

	<script src="https://cdn.ckeditor.com/ckeditor5/41.4.2/super-build/ckeditor.js"></script>

	
	<style>
		/* Admin Custom Styles */
		body {
			background-color: #f8f9fa;
			font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
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
		
		.sidebar {
			min-height: 100vh;
			background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
			box-shadow: 2px 0 10px rgba(0,0,0,0.1);
		}
		
		.sidebar .nav-link {
			color: rgba(255,255,255,0.8);
			padding: 12px 20px;
			margin: 2px 10px;
			border-radius: 8px;
			transition: all 0.3s ease;
		}
		
		.sidebar .nav-link:hover,
		.sidebar .nav-link.active {
			color: white;
			background-color: rgba(255,255,255,0.2);
			transform: translateX(5px);
		}
		
		.sidebar .nav-link i {
			margin-right: 10px;
			width: 20px;
		}
		
		.main-content {
			padding: 0;
		}
		
		.top-navbar {
			background: white;
			box-shadow: 0 2px 10px rgba(0,0,0,0.1);
			padding: 15px 25px;
			margin-bottom: 25px;
		}
		
		.content-wrapper {
			padding: 0 25px 25px 25px;
		}
		
		.card {
			border: none;
			box-shadow: 0 2px 15px rgba(0,0,0,0.08);
			border-radius: 12px;
		}
		
		.card-header {
			background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
			color: white;
			border-radius: 12px 12px 0 0 !important;
			border: none;
			padding: 15px 20px;
		}
		
		.stats-card {
			background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
			color: white;
			border-radius: 15px;
			padding: 25px;
			margin-bottom: 20px;
			transition: transform 0.3s ease;
		}
		
		.stats-card:hover {
			transform: translateY(-5px);
		}
		
		.stats-card .stats-icon {
			font-size: 3rem;
			opacity: 0.8;
		}
		
		.stats-card .stats-number {
			font-size: 2.5rem;
			font-weight: bold;
			margin: 10px 0 5px 0;
		}
		
		.stats-card .stats-label {
			font-size: 0.9rem;
			opacity: 0.9;
		}
		
		.btn-primary {
			background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
			border: none;
			border-radius: 8px;
			padding: 10px 20px;
			transition: all 0.3s ease;
		}
		
		.btn-primary:hover {
			transform: translateY(-2px);
			box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
		}
		
		.table {
			border-radius: 12px;
			overflow: hidden;
		}
		
		.table thead th {
			background-color: #f8f9fa;
			border: none;
			font-weight: 600;
			color: #495057;
		}
		
		.brand-text {
			font-size: 1.5rem;
			font-weight: bold;
			color: white;
			text-decoration: none;
			padding: 20px;
			display: block;
			border-bottom: 1px solid rgba(255,255,255,0.1);
			margin-bottom: 20px;
		}
		
		.brand-text:hover {
			color: white;
			text-decoration: none;
		}
		
		.page-title {
			color: #495057;
			font-weight: 600;
			margin: 0;
		}
		
		.breadcrumb {
			background: none;
			padding: 0;
			margin: 0;
		}
		
		.breadcrumb-item + .breadcrumb-item::before {
			color: #6c757d;
		}
		
		/* Alert Styles */
		.alert {
			border: none;
			border-radius: 10px;
		}
		
		.alert-success {
			background: linear-gradient(135deg, #56ab2f 0%, #a8e6cf 100%);
			color: white;
		}
		
		.alert-danger {
			background: linear-gradient(135deg, #ff416c 0%, #ff4b2b 100%);
			color: white;
		}
	</style>
</head>
<?php 
use Fuel\Core\Uri;
use Fuel\Core\Session;
?>
<body>
	<div class="container-fluid">
		<div class="row">
			<!-- Sidebar -->
			<div class="col-md-2 p-0">
				<div class="sidebar">
					<a href="<?php echo Uri::base(); ?>admin" class="brand-text">
						<i class="bi bi-speedometer2"></i> Admin Panel
					</a>
					
					<nav class="nav flex-column">
						<a class="nav-link <?php echo Uri::segment(2) == '' ? 'active' : ''; ?>" href="<?php echo Uri::base(); ?>admin">
							<i class="bi bi-house-door"></i> Dashboard
						</a>
						<a class="nav-link <?php echo Uri::segment(2) == 'posts' ? 'active' : ''; ?>" href="<?php echo Uri::base(); ?>admin/posts">
							<i class="bi bi-file-earmark-text"></i> Bài viết
						</a>
						<a class="nav-link <?php echo Uri::segment(2) == 'discussion' ? 'active' : ''; ?>" href="<?php echo Uri::base(); ?>admin/discussion">
							<i class="bi bi-chat"></i> Thảo luận
						</a>
						<?php if (Auth::get('role_id') == 1 || Auth::get('role_id') == 2): ?>
							<a class="nav-link <?php echo Uri::segment(2) == 'categories' ? 'active' : ''; ?>" href="<?php echo Uri::base(); ?>admin/categories">
								<i class="bi bi-tags"></i> Danh mục
							</a>
							<a class="nav-link <?php echo Uri::segment(2) == 'users' ? 'active' : ''; ?>" href="<?php echo Uri::base(); ?>admin/users">
								<i class="bi bi-people"></i> Người dùng
							</a>
						<?php endif; ?>
						<hr style="color: rgba(255,255,255,0.3); margin: 20px 10px;">
						<a class="nav-link" href="<?php echo Uri::base(); ?>" target="_blank">
							<i class="bi bi-box-arrow-up-right"></i> Xem website
						</a>
						<a class="nav-link" href="<?php echo Uri::base(); ?>logout">
							<i class="bi bi-box-arrow-right"></i> Đăng xuất
						</a>
					</nav>
				</div>
			</div>
			
			<!-- Main Content -->
			<div class="col-md-10 main-content">
				<!-- Top Navbar -->
				<div class="top-navbar">
					<div class="d-flex justify-content-between align-items-center">
						<div>
							<h4 class="page-title"><?php echo isset($title) ? $title : 'Dashboard'; ?></h4>
							<nav aria-label="breadcrumb">
								<ol class="breadcrumb">
									<li class="breadcrumb-item"><a href="<?php echo Uri::base(); ?>admin">Admin</a></li>
									<?php if (Uri::segment(2)): ?>
										<li class="breadcrumb-item active"><?php echo ucfirst(Uri::segment(2)); ?></li>
									<?php endif; ?>
								</ol>
							</nav>
						</div>
						<div>
							<span class="text-muted">
								<i class="bi bi-person-circle"></i> Xin chào, <strong>Admin</strong>
							</span>
						</div>
					</div>
				</div>
				
				<!-- Flash Messages -->
				<div class="content-wrapper">
					<?php if (Session::get_flash('success')): ?>
						<div class="alert alert-success alert-dismissible fade show" role="alert">
							<i class="bi bi-check-circle me-2"></i>
							<?php echo Session::get_flash('success'); ?>
							<button type="button" class="btn-close" data-bs-dismiss="alert"></button>
						</div>
					<?php endif; ?>
					
					<?php if (Session::get_flash('error')): ?>
						<div class="alert alert-danger alert-dismissible fade show" role="alert">
							<i class="bi bi-exclamation-circle me-2"></i>
							<?php echo implode('<br>', (array) Session::get_flash('error')); ?>
							<button type="button" class="btn-close" data-bs-dismiss="alert"></button>
						</div>
					<?php endif; ?>
					
					<!-- Page Content -->
					<?php echo $content; ?>
				</div>
			</div>
		</div>
	</div>

	<!-- Bootstrap 5 JS -->
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
	
	<!-- Custom Admin JS -->
	<script>
		// Auto dismiss alerts after 5 seconds
		setTimeout(function() {
			let alerts = document.querySelectorAll('.alert');
			alerts.forEach(function(alert) {
				let bsAlert = new bootstrap.Alert(alert);
				bsAlert.close();
			});
		}, 5000);

		// Confirm delete actions
		document.addEventListener('click', function(e) {
			if (e.target.classList.contains('btn-delete') || e.target.closest('.btn-delete')) {
				if (!confirm('Bạn có chắc chắn muốn xóa không?')) {
					e.preventDefault();
					return false;
				}
			}
		});
	</script>
</body>
</html>
