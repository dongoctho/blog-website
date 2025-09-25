<?php
use Fuel\Core\Uri;
?>

<div class="d-flex justify-content-between align-items-center mb-4">
	<div>
		<h5 class="mb-1">Chi tiết bài viết</h5>
		<p class="text-muted mb-0">Xem thông tin đầy đủ của bài viết</p>
	</div>
	<div>
		<a href="<?php echo Uri::base(); ?>admin/posts" class="btn btn-secondary me-2">
			<i class="bi bi-arrow-left me-2"></i>Quay lại danh sách
		</a>
		<a href="<?php echo Uri::base(); ?>admin/posts/edit/<?php echo $post->id; ?>" class="btn btn-primary">
			<i class="bi bi-pencil me-2"></i>Chỉnh sửa
		</a>
	</div>
</div>

<div class="row">
	<div class="col-lg-8">
		<!-- Nội dung bài viết -->
		<div class="card mb-4">
			<div class="card-header">
				<h6 class="mb-0">
					<i class="bi bi-file-text me-2"></i>Nội dung bài viết
				</h6>
			</div>
			<div class="card-body">
				<div class="post-content">
					<h2 class="post-title text-primary mb-3">
						<?php echo $post->title; ?>
					</h2>
					
					<div class="post-meta mb-4">
						<div class="row">
							<div class="col-md-6">
								<p class="mb-1">
									<i class="bi bi-person-circle text-muted"></i>
									<strong>Tác giả:</strong> 
									<span class="text-primary"><?php echo isset($post->user) ? $post->user->username : 'N/A'; ?></span>
								</p>
								<p class="mb-1">
									<i class="bi bi-tags text-muted"></i>
									<strong>Danh mục:</strong> 
									<?php 
									$categories = $post->get_categories();
									if (!empty($categories)) {
										foreach ($categories as $category) {
											echo '<span class="badge bg-primary me-1">' . e($category->name) . '</span>';
										}
									} else {
										echo '<span class="badge bg-secondary">N/A</span>';
									}
									?>
								</p>
							</div>
							<div class="col-md-6">
								<p class="mb-1">
									<i class="bi bi-calendar text-muted"></i>
									<strong>Ngày tạo:</strong> 
									<?php echo date('d/m/Y H:i', strtotime($post->created_at)); ?>
								</p>
								<p class="mb-1">
									<i class="bi bi-clock text-muted"></i>
									<strong>Cập nhật:</strong> 
									<?php echo date('d/m/Y H:i', strtotime($post->updated_at)); ?>
								</p>
								<p class="mb-1">
									<i class="bi bi-eye text-muted"></i>
									<strong>Số lượt xem:</strong> 
									<span class="badge bg-info"><?php echo number_format($post->views ?? 0); ?></span>
								</p>
								<?php if (!empty($post->publish_start_date)): ?>
								<p class="mb-1">
									<i class="bi bi-calendar-plus text-success"></i>
									<strong>Bắt đầu xuất bản:</strong> 
									<?php echo date('d/m/Y H:i', strtotime($post->publish_start_date)); ?>
								</p>
								<?php endif; ?>
								<?php if (!empty($post->publish_end_date)): ?>
								<p class="mb-1">
									<i class="bi bi-calendar-x text-danger"></i>
									<strong>Kết thúc xuất bản:</strong> 
									<?php echo date('d/m/Y H:i', strtotime($post->publish_end_date)); ?>
								</p>
								<?php endif; ?>
							</div>
						</div>
					</div>

					<div class="post-slug mb-3">
						<p>
							<i class="bi bi-link text-muted"></i>
							<strong>Slug:</strong> 
							<code><?php echo $post->slug; ?></code>
							<a href="<?php echo Uri::base(); ?>post/view/<?php echo $post->slug; ?>" 
							   target="_blank" 
							   class="btn btn-outline-primary btn-sm ms-2"
							   title="Xem trên website">
								<i class="bi bi-box-arrow-up-right"></i> Xem trên web
							</a>
						</p>
					</div>

					<hr class="my-4">

					<div class="post-body">
						<h6 class="mb-3">
							<i class="bi bi-file-earmark-text"></i> Nội dung:
						</h6>
						<div class="content-preview border rounded p-3 bg-light">
                        <?php echo htmlspecialchars_decode($post->content); ?>
						</div>
					</div>
				</div>
			</div>
		</div>

		<!-- Hình ảnh của bài viết -->
		<?php if (isset($post->images) && count($post->images) > 0): ?>
		<div class="card">
			<div class="card-header">
				<h6 class="mb-0">
					<i class="bi bi-images me-2"></i>Hình ảnh đính kèm
					<span class="badge bg-secondary ms-2"><?php echo count($post->images); ?></span>
				</h6>
			</div>
			<div class="card-body">
				<div class="images-gallery">
					<div class="row g-3">
						<?php foreach ($post->images as $image): ?>
						<div class="col-md-4 col-sm-6">
							<div class="image-item">
								<div class="image-wrapper">
									<img src="<?php echo Uri::base(); ?>uploads/<?php echo $image->file_path; ?>" 
										 alt="Image" 
										 class="img-fluid rounded"
										 onclick="showImageModal(this.src)">
								</div>
								<div class="image-info mt-2">
									<small class="text-muted d-block">
										<i class="bi bi-file-earmark"></i>
										<?php echo $image->file_path; ?>
									</small>
									<small class="text-muted">
										<i class="bi bi-calendar-event"></i>
										<?php echo date('d/m/Y H:i', strtotime($image->uploaded_at)); ?>
									</small>
								</div>
							</div>
						</div>
						<?php endforeach; ?>
					</div>
				</div>
			</div>
		</div>
		<?php endif; ?>
	</div>
	
	<!-- Sidebar -->
	<div class="col-lg-4">
		<!-- Trạng thái bài viết -->
		<div class="card mb-3">
			<div class="card-header">
				<h6 class="mb-0">
					<i class="bi bi-gear me-2"></i>Thông tin bài viết
				</h6>
			</div>
			<div class="card-body">
				<div class="status-info">
					<div class="mb-3">
						<label class="form-label fw-bold">Trạng thái xuất bản:</label>
						<?php if ($post->is_published): ?>
							<span class="badge bg-success fs-6">
								<i class="bi bi-check-circle me-1"></i>Đã xuất bản
							</span>
						<?php else: ?>
							<span class="badge bg-secondary fs-6">
								<i class="bi bi-clock me-1"></i>Bản nháp
							</span>
						<?php endif; ?>
					</div>
					
					<div class="mb-3">
						<label class="form-label fw-bold">ID bài viết:</label>
						<p class="mb-0"><code>#<?php echo $post->id; ?></code></p>
					</div>
					
					<div class="mb-3">
						<label class="form-label fw-bold">Số lượng hình ảnh:</label>
						<p class="mb-0">
							<span class="badge bg-info">
								<?php echo isset($post->images) ? count($post->images) : 0; ?> ảnh
							</span>
						</p>
					</div>
				</div>
			</div>
		</div>

		<!-- Lịch xuất bản -->
		<?php if (!empty($post->publish_start_date) || !empty($post->publish_end_date)): ?>
		<div class="card mb-3">
			<div class="card-header">
				<h6 class="mb-0">
					<i class="bi bi-calendar-check me-2"></i>Lịch xuất bản
				</h6>
			</div>
			<div class="card-body">
				<?php if (!empty($post->publish_start_date)): ?>
				<div class="schedule-item mb-3">
					<div class="d-flex align-items-center">
						<i class="bi bi-calendar-plus text-success me-2"></i>
						<div>
							<strong>Bắt đầu xuất bản</strong>
							<div class="text-muted small">
								<?php echo date('d/m/Y - H:i', strtotime($post->publish_start_date)); ?>
							</div>
						</div>
					</div>
				</div>
				<?php endif; ?>

				<?php if (!empty($post->publish_end_date)): ?>
				<div class="schedule-item">
					<div class="d-flex align-items-center">
						<i class="bi bi-calendar-x text-danger me-2"></i>
						<div>
							<strong>Kết thúc xuất bản</strong>
							<div class="text-muted small">
								<?php echo date('d/m/Y - H:i', strtotime($post->publish_end_date)); ?>
							</div>
						</div>
					</div>
				</div>
				<?php else: ?>
				<div class="schedule-item">
					<div class="d-flex align-items-center">
						<i class="bi bi-infinity text-info me-2"></i>
						<div>
							<strong>Không giới hạn thời gian</strong>
							<div class="text-muted small">
								Bài viết sẽ được xuất bản vô thời hạn
							</div>
						</div>
					</div>
				</div>
				<?php endif; ?>
			</div>
		</div>
		<?php endif; ?>

		<!-- Thao tác nhanh -->
		<div class="card">
			<div class="card-header">
				<h6 class="mb-0">
					<i class="bi bi-lightning me-2"></i>Thao tác nhanh
				</h6>
			</div>
			<div class="card-body">
				<div class="d-grid gap-2">
					<a href="<?php echo Uri::base(); ?>admin/posts/edit/<?php echo $post->id; ?>" 
					   class="btn btn-primary">
						<i class="bi bi-pencil me-2"></i>Chỉnh sửa bài viết
					</a>
					
					<a href="<?php echo Uri::base(); ?>post/view/<?php echo $post->slug; ?>" 
					   target="_blank" 
					   class="btn btn-outline-info">
						<i class="bi bi-box-arrow-up-right me-2"></i>Xem trên website
					</a>
					
					<?php if ($post->is_published): ?>
						<button type="button" class="btn btn-outline-warning" onclick="togglePublishStatus(<?php echo $post->id; ?>, 0)">
							<i class="bi bi-eye-slash me-2"></i>Ẩn bài viết
						</button>
					<?php else: ?>
						<button type="button" class="btn btn-outline-success" onclick="togglePublishStatus(<?php echo $post->id; ?>, 1)">
							<i class="bi bi-eye me-2"></i>Xuất bản
						</button>
					<?php endif; ?>
					
					<hr class="my-2">
					
					<a href="<?php echo Uri::base(); ?>admin/posts/delete/<?php echo $post->id; ?>" 
					   class="btn btn-outline-danger btn-delete">
						<i class="bi bi-trash me-2"></i>Xóa bài viết
					</a>
				</div>
			</div>
		</div>
	</div>
</div>

<!-- Modal xem ảnh -->
<div class="modal fade" id="imageModal" tabindex="-1">
	<div class="modal-dialog modal-lg modal-dialog-centered">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title">Xem ảnh</h5>
				<button type="button" class="btn-close" data-bs-dismiss="modal"></button>
			</div>
			<div class="modal-body text-center">
				<img id="modalImage" src="" class="img-fluid" alt="Image">
			</div>
		</div>
	</div>
</div>

<style>
.post-content {
	line-height: 1.6;
}

.post-title {
	border-bottom: 3px solid #667eea;
	padding-bottom: 10px;
}

.content-preview {
	max-height: 400px;
	overflow-y: auto;
	font-size: 0.95rem;
	line-height: 1.6;
}

.images-gallery .image-item {
	text-align: center;
}

.images-gallery .image-wrapper {
	position: relative;
	overflow: hidden;
	border-radius: 8px;
	cursor: pointer;
	transition: transform 0.3s ease;
}

.images-gallery .image-wrapper:hover {
	transform: scale(1.05);
}

.images-gallery img {
	aspect-ratio: 16/9;
	object-fit: cover;
	width: 100%;
}

.status-info .form-label {
	color: #495057;
	font-size: 0.9rem;
}

.badge.fs-6 {
	font-size: 0.9rem !important;
}

.post-meta {
	background: #f8f9fa;
	border-radius: 8px;
	padding: 15px;
}

.post-slug code {
	background: #e9ecef;
	padding: 2px 6px;
	border-radius: 4px;
	font-size: 0.85rem;
}
</style>

<script>
// Hiển thị modal xem ảnh
function showImageModal(imageSrc) {
	document.getElementById('modalImage').src = imageSrc;
	new bootstrap.Modal(document.getElementById('imageModal')).show();
}

// Toggle trạng thái xuất bản (có thể implement sau)
function togglePublishStatus(postId, status) {
	const action = status ? 'xuất bản' : 'ẩn';
	
	if (confirm(`Bạn có chắc chắn muốn ${action} bài viết này?`)) {
		// TODO: Implement AJAX call to update publish status
		console.log(`Toggle publish status for post ${postId} to ${status}`);
		
		// For now, redirect to edit page
		window.location.href = `<?php echo Uri::base(); ?>admin/posts/edit/${postId}`;
	}
}
</script>
