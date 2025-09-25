<?php
use Fuel\Core\Uri;
use Fuel\Core\Str;
?>

<div class="d-flex justify-content-between align-items-center mb-4">
	<div>
		<h5 class="mb-1">Quản lý thảo luận</h5>
		<p class="text-muted mb-0">Tổng cộng <?php echo $total_posts; ?> thảo luận</p>
	</div>
	<div>
		<a href="<?php echo Uri::base(); ?>admin/discussion/create" class="btn btn-primary">
			<i class="bi bi-plus-circle me-2"></i>Tạo thảo luận
		</a>
	</div>
</div>

<div class="card">
	<div class="card-body">
		<?php if (!empty($posts)): ?>
			<div class="table-responsive">
				<table class="table table-hover">
					<thead>
						<tr>
							<th style="width: 40%">Tiêu đề</th>
							<th>Tác giả</th>
							<th>Danh mục</th>
							<th>Ngày tạo</th>
							<th>Thao tác</th>
						</tr>
					</thead>
					<tbody>
						<?php foreach ($posts as $post): ?>
							<tr>
								<td>
									<div class="d-flex align-items-start">
										<div class="flex-grow-1">
											<h6 class="mb-1"><?php echo e($post->title); ?></h6>
											<small class="text-muted">
												<?php echo e(Str::truncate($post->get_excerpt(), 100)); ?>
											</small>
										</div>
									</div>
								</td>
								<td>
									<div class="d-flex align-items-center">
										<i class="bi bi-person-circle me-2 text-muted"></i>
										<span><?php echo e($post->user->username ?? 'Unknown'); ?></span>
									</div>
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
									<small class="text-muted">
										<i class="bi bi-calendar3 me-1"></i>
										<?php echo date('d/m/Y H:i', strtotime($post->created_at)); ?>
									</small>
								</td>
								<td>
									<div class="btn-group btn-group-sm">
										<a href="<?php echo Uri::base(); ?>admin/discussion/view/<?php echo $post->id; ?>" 
										   class="btn btn-outline-primary" 
										   title="Xem chi tiết">
											<i class="bi bi-eye"></i>
										</a>
										<a href="<?php echo Uri::base(); ?>admin/discussion/edit/<?php echo $post->id; ?>" 
										   class="btn btn-outline-secondary" 
										   title="Chỉnh sửa">
											<i class="bi bi-pencil"></i>
										</a>
										<a href="<?php echo Uri::base(); ?>admin/discussion/delete/<?php echo $post->id; ?>" 
										   class="btn btn-outline-danger btn-delete" 
										   title="Xóa thảo luận">
											<i class="bi bi-trash"></i>
										</a>
									</div>
								</td>
							</tr>
						<?php endforeach; ?>
					</tbody>
				</table>
			</div>

			<?php if (isset($pagination)): ?>
				<?php echo htmlspecialchars_decode($pagination); ?>
			<?php endif; ?>
		<?php else: ?>
			<div class="text-center py-5">
				<i class="bi bi-chat-square-text" style="font-size: 4rem; color: #dee2e6;"></i>
				<h4 class="text-muted mt-3">Chưa có thảo luận nào</h4>
				<p class="text-muted">Hãy tạo thảo luận đầu tiên của bạn</p>
				<a href="<?php echo Uri::base(); ?>admin/discussion/create" class="btn btn-primary">
					<i class="bi bi-plus-circle me-2"></i>Tạo thảo luận
				</a>
			</div>
		<?php endif; ?>
	</div>
</div>


