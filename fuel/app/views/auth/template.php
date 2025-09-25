<!DOCTYPE html>
<html lang="vi">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title><?php echo $title; ?> - Blog CMS</title>
	
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
	<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
	
	<style>
		/* Basic reset và global styles */
		* {
			margin: 0;
			padding: 0;
			box-sizing: border-box;
		}

		:root {
			--primary-color: #2c3e50;
			--secondary-color: #3498db;
			--accent-color: #e74c3c;
			--success-color: #2ecc71;
			--warning-color: #f39c12;
			--light-gray: #f8f9fa;
			--dark-gray: #6c757d;
			--border-color: #e1e8ed;
		}
		
		body {
			font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
			background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
			min-height: 100vh;
			display: flex;
			align-items: center;
			justify-content: center;
			padding: 20px;
		}

		/* Utility classes */
		.text-gradient {
			background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
			-webkit-background-clip: text;
			-webkit-text-fill-color: transparent;
			background-clip: text;
		}

		.shadow-custom {
			box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
		}

		.border-radius-custom {
			border-radius: 20px;
		}

		/* Animation keyframes */
		@keyframes fadeInUp {
			from {
				opacity: 0;
				transform: translateY(30px);
			}
			to {
				opacity: 1;
				transform: translateY(0);
			}
		}

		@keyframes pulse {
			0%, 100% {
				transform: scale(1);
			}
			50% {
				transform: scale(1.05);
			}
		}

		@keyframes spin {
			0% {
				transform: rotate(0deg);
			}
			100% {
				transform: rotate(360deg);
			}
		}

		/* Animation classes */
		.animate-fadeInUp {
			animation: fadeInUp 0.6s ease-out;
		}

		.animate-pulse {
			animation: pulse 2s infinite;
		}

		.animate-spin {
			animation: spin 1s linear infinite;
		}

		/* Flash messages styling */
		.flash-messages {
			position: fixed;
			top: 20px;
			right: 20px;
			z-index: 9999;
			max-width: 400px;
		}

		.flash-message {
			padding: 15px 20px;
			border-radius: 10px;
			margin-bottom: 10px;
			display: flex;
			align-items: center;
			gap: 10px;
			box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
			animation: slideInRight 0.3s ease-out;
		}

		@keyframes slideInRight {
			from {
				transform: translateX(100%);
				opacity: 0;
			}
			to {
				transform: translateX(0);
				opacity: 1;
			}
		}

		.flash-success {
			background: #d4edda;
			color: #155724;
			border-left: 4px solid #28a745;
		}

		.flash-error {
			background: #f8d7da;
			color: #721c24;
			border-left: 4px solid #dc3545;
		}

		.flash-warning {
			background: #fff3cd;
			color: #856404;
			border-left: 4px solid #ffc107;
		}

		.flash-info {
			background: #d1ecf1;
			color: #0c5460;
			border-left: 4px solid #17a2b8;
		}

		.flash-close {
			background: none;
			border: none;
			font-size: 1.2rem;
			cursor: pointer;
			margin-left: auto;
			opacity: 0.7;
			transition: opacity 0.3s ease;
		}

		.flash-close:hover {
			opacity: 1;
		}

		/* Loading overlay */
		.loading-overlay {
			position: fixed;
			top: 0;
			left: 0;
			width: 100%;
			height: 100%;
			background: rgba(255, 255, 255, 0.9);
			display: flex;
			align-items: center;
			justify-content: center;
			z-index: 10000;
			backdrop-filter: blur(5px);
		}

		.loading-spinner {
			width: 60px;
			height: 60px;
			border: 4px solid var(--border-color);
			border-top: 4px solid var(--secondary-color);
			border-radius: 50%;
			animation: spin 1s linear infinite;
		}

		/* Responsive utilities */
		@media (max-width: 768px) {
			body {
				padding: 10px;
			}

			.flash-messages {
				top: 10px;
				right: 10px;
				left: 10px;
				max-width: none;
			}
		}

		/* Custom scrollbar */
		::-webkit-scrollbar {
			width: 8px;
		}

		::-webkit-scrollbar-track {
			background: rgba(255, 255, 255, 0.1);
		}

		::-webkit-scrollbar-thumb {
			background: rgba(255, 255, 255, 0.3);
			border-radius: 4px;
		}

		::-webkit-scrollbar-thumb:hover {
			background: rgba(255, 255, 255, 0.5);
		}
	</style>
</head>
<?php
use Fuel\Core\Uri;
use Fuel\Core\Session;
use Fuel\Core\Fuel;
?>
<body>
	<!-- Flash Messages -->
	<div class="flash-messages">
		<?php if (Session::get_flash('success')): ?>
			<div class="flash-message flash-success">
				<i class="bi bi-check-circle-fill"></i>
				<span><?php echo implode('<br>', (array) Session::get_flash('success')); ?></span>
				<button type="button" class="flash-close" onclick="this.parentElement.remove()">
					<i class="bi bi-x"></i>
				</button>
			</div>
		<?php endif; ?>

		<?php if (Session::get_flash('error')): ?>
			<div class="flash-message flash-error">
				<i class="bi bi-exclamation-triangle-fill"></i>
				<span><?php echo implode('<br>', (array) Session::get_flash('error')); ?></span>
				<button type="button" class="flash-close" onclick="this.parentElement.remove()">
					<i class="bi bi-x"></i>
				</button>
			</div>
		<?php endif; ?>

		<?php if (Session::get_flash('warning')): ?>
			<div class="flash-message flash-warning">
				<i class="bi bi-exclamation-triangle-fill"></i>
				<span><?php echo implode('<br>', (array) Session::get_flash('warning')); ?></span>
				<button type="button" class="flash-close" onclick="this.parentElement.remove()">
					<i class="bi bi-x"></i>
				</button>
			</div>
		<?php endif; ?>

		<?php if (Session::get_flash('info')): ?>
			<div class="flash-message flash-info">
				<i class="bi bi-info-circle-fill"></i>
				<span><?php echo implode('<br>', (array) Session::get_flash('info')); ?></span>
				<button type="button" class="flash-close" onclick="this.parentElement.remove()">
					<i class="bi bi-x"></i>
				</button>
			</div>
		<?php endif; ?>
	</div>

	<!-- Main Content -->
	<main class="animate-fadeInUp">
		<?php echo $content; ?>
	</main>

	<!-- Loading Overlay (ẩn mặc định) -->
	<div class="loading-overlay" id="loadingOverlay" style="display: none;">
		<div class="loading-spinner"></div>
	</div>

	<!-- Scripts -->
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
	
	<script>
		// Auto-hide flash messages after 5 seconds
		document.addEventListener('DOMContentLoaded', function() {
			const flashMessages = document.querySelectorAll('.flash-message');
			flashMessages.forEach(function(message) {
				setTimeout(function() {
					message.style.transition = 'opacity 0.3s ease, transform 0.3s ease';
					message.style.opacity = '0';
					message.style.transform = 'translateX(100%)';
					setTimeout(function() {
						message.remove();
					}, 300);
				}, 5000);
			});
		});

		// Global loading functions
		window.showLoading = function() {
			document.getElementById('loadingOverlay').style.display = 'flex';
		};

		window.hideLoading = function() {
			document.getElementById('loadingOverlay').style.display = 'none';
		};

		// Global form submission handler
		document.addEventListener('submit', function(e) {
			const form = e.target;
			if (form.tagName === 'FORM') {
				showLoading();
				
				// Hide loading after form submission timeout
				setTimeout(function() {
					hideLoading();
				}, 10000); // 10 seconds timeout
			}
		});

		// Back to top functionality (if needed)
		window.scrollToTop = function() {
			window.scrollTo({
				top: 0,
				behavior: 'smooth'
			});
		};

		// Utility function for smooth scrolling to elements
		window.scrollToElement = function(elementId) {
			const element = document.getElementById(elementId);
			if (element) {
				element.scrollIntoView({
					behavior: 'smooth',
					block: 'start'
				});
			}
		};

		// Enhanced error handling
		window.addEventListener('error', function(e) {
			console.error('JavaScript Error:', e.error);
			// Optionally show user-friendly error message
		});

		// Prevent form double submission
		document.addEventListener('DOMContentLoaded', function() {
			const forms = document.querySelectorAll('form');
			forms.forEach(function(form) {
				let submitted = false;
				form.addEventListener('submit', function(e) {
					if (submitted) {
						e.preventDefault();
						return false;
					}
					submitted = true;
					
					// Re-enable form after timeout (in case of errors)
					setTimeout(function() {
						submitted = false;
					}, 5000);
				});
			});
		});

		// Enhanced keyboard navigation
		document.addEventListener('keydown', function(e) {
			// ESC key to close modals/overlays
			if (e.key === 'Escape') {
				const loadingOverlay = document.getElementById('loadingOverlay');
				if (loadingOverlay && loadingOverlay.style.display !== 'none') {
					hideLoading();
				}
				
				// Close flash messages
				const flashMessages = document.querySelectorAll('.flash-message');
				flashMessages.forEach(function(message) {
					message.remove();
				});
			}
		});

		// Simple analytics/tracking (if needed)
		window.trackEvent = function(eventName, data) {
			// Add your analytics tracking code here
			console.log('Event tracked:', eventName, data);
		};
	</script>
</body>
</html>
