<?php
use Fuel\Core\Uri;
use Fuel\Core\Str;
use Fuel\Core\Fuel;
?>
<!-- Statistics Cards -->
<div class="row mb-4">
	<div class="col-lg-3 col-md-6">
		<div class="stats-card">
			<div class="d-flex justify-content-between align-items-center">
				<div>
					<div class="stats-number"><?php echo $total_posts; ?></div>
					<div class="stats-label">Tổng bài viết</div>
				</div>
				<div class="stats-icon">
					<i class="bi bi-file-earmark-text"></i>
				</div>
			</div>
		</div>
	</div>
	
	<div class="col-lg-3 col-md-6">
		<div class="stats-card">
			<div class="d-flex justify-content-between align-items-center">
				<div>
					<div class="stats-number"><?php echo $published_posts; ?></div>
					<div class="stats-label">Đã xuất bản</div>
				</div>
				<div class="stats-icon">
					<i class="bi bi-check-circle"></i>
				</div>
			</div>
		</div>
	</div>
	
	<div class="col-lg-3 col-md-6">
		<div class="stats-card">
			<div class="d-flex justify-content-between align-items-center">
				<div>
					<div class="stats-number"><?php echo $total_categories ?? 0; ?></div>
					<div class="stats-label">Danh mục</div>
				</div>
				<div class="stats-icon">
					<i class="bi bi-tags"></i>
				</div>
			</div>
		</div>
	</div>

	<div class="col-lg-3 col-md-6">
		<div class="stats-card">
			<div class="d-flex justify-content-between align-items-center">
				<div>
					<div class="stats-number"><?php echo $total_users ?? 0; ?></div>
					<div class="stats-label">Người dùng</div>
				</div>
				<div class="stats-icon">
					<i class="bi bi-people"></i>
				</div>
			</div>
		</div>
	</div>
</div>

<div class="row">
	<!-- Recent Posts -->
	<div class="col-lg-8">
		<div class="card">
			<div class="card-header">
				<h5 class="mb-0">
					<i class="bi bi-clock-history me-2"></i>Bài viết gần đây
				</h5>
			</div>
			<div class="card-body">
				<?php if (!empty($recent_posts)): ?>
					<div class="table-responsive">
						<table class="table table-hover">
							<thead>
								<tr>
									<th>Tiêu đề</th>
									<th>Tác giả</th>
									<th>Danh mục</th>
									<th>Trạng thái</th>
									<th>Ngày tạo</th>
									<th>Thao tác</th>
								</tr>
							</thead>
							<tbody>
								<?php foreach ($recent_posts as $post): ?>
									<tr>
										<td>
											<div class="d-flex align-items-center">
												<div>
													<h6 class="mb-1"><?php echo e(Str::truncate($post->title, 50)); ?></h6>
													<small class="text-muted"><?php echo e(Str::truncate($post->get_excerpt(), 80)); ?></small>
												</div>
											</div>
										</td>
										<td>
											<span class="badge bg-secondary">
												<?php echo e($post->user->username ?? 'Unknown'); ?>
											</span>
										</td>
										<td>
											<?php 
											$categories = $post->get_categories();
											if (!empty($categories)) {
												foreach ($categories as $category) {
													echo '<span class="badge bg-info me-1">' . e($category->name) . '</span>';
												}
											} else {
												echo '<span class="badge bg-secondary">Uncategorized</span>';
											}
											?>
										</td>
										<td>
											<?php if ($post->is_published): ?>
												<span class="badge bg-success">
													<i class="bi bi-check-circle me-1"></i>Đã xuất bản
												</span>
											<?php else: ?>
												<span class="badge bg-warning">
													<i class="bi bi-clock me-1"></i>Bản nháp
												</span>
											<?php endif; ?>
										</td>
										<td>
											<small class="text-muted">
												<?php echo date('d/m/Y', strtotime($post->created_at)); ?>
											</small>
										</td>
										<td>
											<div class="btn-group btn-group-sm">
												<a href="<?php echo Uri::base(); ?>admin/posts/view/<?php echo $post->id; ?>" 
												   class="btn btn-outline-primary btn-sm" 
												   target="_blank" 
												   title="Xem bài viết">
													<i class="bi bi-eye"></i>
												</a>
												<a href="<?php echo Uri::base(); ?>admin/posts/edit/<?php echo $post->id; ?>" 
												   class="btn btn-outline-secondary btn-sm" 
												   title="Chỉnh sửa">
													<i class="bi bi-pencil"></i>
												</a>
											</div>
										</td>
									</tr>
								<?php endforeach; ?>
							</tbody>
						</table>
					</div>
					<div class="text-center mt-3">
						<a href="<?php echo Uri::base(); ?>admin/posts" class="btn btn-primary">
							<i class="bi bi-arrow-right me-2"></i>Xem tất cả bài viết
						</a>
					</div>
				<?php else: ?>
					<div class="text-center py-4">
						<i class="bi bi-file-earmark-text" style="font-size: 3rem; color: #dee2e6;"></i>
						<h5 class="text-muted mt-3">Chưa có bài viết nào</h5>
						<p class="text-muted">Hãy tạo bài viết đầu tiên của bạn</p>
						<a href="<?php echo Uri::base(); ?>admin/posts/create" class="btn btn-primary">
							<i class="bi bi-plus-circle me-2"></i>Viết bài mới
						</a>
					</div>
				<?php endif; ?>
			</div>
		</div>
	</div>
	
	<!-- Quick Stats & Actions -->
	<div class="col-lg-4">
		<!-- Quick Actions -->
		<div class="card mb-4">
			<div class="card-header">
				<h5 class="mb-0">
					<i class="bi bi-lightning me-2"></i>Thao tác nhanh
				</h5>
			</div>
			<div class="card-body">
				<div class="d-grid gap-2">
					<a href="<?php echo Uri::base(); ?>admin/posts/create" class="btn btn-primary">
						<i class="bi bi-plus-circle me-2"></i>Viết bài mới
					</a>
					<?php if (Auth::get('role_id') == 1 || Auth::get('role_id') == 2): ?>
						<a href="<?php echo Uri::base(); ?>admin/categories" class="btn btn-outline-secondary">
							<i class="bi bi-tags me-2"></i>Quản lý danh mục
						</a>
					<?php endif; ?>
					<a href="<?php echo Uri::base(); ?>" class="btn btn-outline-info" target="_blank">
						<i class="bi bi-box-arrow-up-right me-2"></i>Xem website
					</a>
				</div>
			</div>
		</div>
		
		<!-- System Info -->
		<div class="card">
			<div class="card-header">
				<h5 class="mb-0">
					<i class="bi bi-info-circle me-2"></i>Thông tin hệ thống
				</h5>
			</div>
			<div class="card-body">
				<div class="small text-muted">
					<div class="d-flex justify-content-between">
						<span>PHP Version:</span>
						<span><?php echo PHP_VERSION; ?></span>
					</div>
					<div class="d-flex justify-content-between">
						<span>FuelPHP Version:</span>
						<span><?php echo Fuel::VERSION; ?></span>
					</div>
					<div class="d-flex justify-content-between">
						<span>Server Time:</span>
						<span><?php echo date('H:i:s d/m/Y'); ?></span>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<!-- Charts Section -->
<div class="row mt-4">
	<div class="col-12">
		<div class="card">
			<div class="card-header">
				<h5 class="mb-0">
					<i class="bi bi-bar-chart me-2"></i>Thống kê tổng quan
				</h5>
			</div>
			<div class="card-body">
				<canvas id="statsChart" width="400" height="100"></canvas>
			</div>
		</div>
	</div>
</div>

<script>
// Chart.js configuration
document.addEventListener('DOMContentLoaded', function() {
	published_posts = <?php echo $published_posts; ?>;
	draft_posts = <?php echo $draft_posts; ?>;
	total_categories = <?php echo $total_categories ?? 0; ?>;
	total_users = <?php echo $total_users ?? 0; ?>;

	const ctx = document.getElementById('statsChart').getContext('2d');
	const chart = new Chart(ctx, {
		type: 'doughnut',
		data: {
			labels: ['Đã xuất bản', 'Bản nháp', 'Danh mục', 'Người dùng'],
			datasets: [{
				data: [published_posts, draft_posts, total_categories, total_users],
				backgroundColor: [
					'rgba(102, 126, 234, 0.8)',
					'rgba(255, 193, 7, 0.8)', 
					'rgba(13, 202, 240, 0.8)',
					'rgba(25, 135, 84, 0.8)'
				],
				borderColor: [
					'rgba(102, 126, 234, 1)',
					'rgba(255, 193, 7, 1)',
					'rgba(13, 202, 240, 1)', 
					'rgba(25, 135, 84, 1)'
				],
				borderWidth: 2
			}]
		},
		options: {
			responsive: true,
			maintainAspectRatio: false,
			plugins: {
				legend: {
					position: 'bottom'
				}
			}
		}
	});
});
</script>
