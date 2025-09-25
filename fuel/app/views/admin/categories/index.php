<?php
use Fuel\Core\Uri;
use Fuel\Core\DB;
?>

<div class="d-flex justify-content-between align-items-center mb-4">
	<div>
		<h5 class="mb-1">Quản lý danh mục</h5>
		<p class="text-muted mb-0">Tổng cộng <?php echo isset($total_categories) ? (int)$total_categories : count($categories); ?> danh mục</p>
	</div>
	<div>
		<a href="<?php echo Uri::create('admin/categories/create'); ?>" class="btn btn-primary">
			<i class="bi bi-plus-circle me-2"></i>Thêm danh mục
		</a>
	</div>
</div>

<div class="card">
	<div class="card-body">
		<?php if (!empty($categories)): ?>
			<div class="table-responsive">
				<table class="table table-hover">
					<thead>
						<tr>
							<th>ID</th>
							<th>Tên danh mục</th>
							<th>Danh mục cha</th>
							<th>Số bài viết</th>
							<th>Ngày tạo</th>
							<th>Thao tác</th>
						</tr>
					</thead>
					<tbody>
						<?php foreach ($categories as $category): ?>
							<tr>
								<td>
									<span class="badge bg-secondary">#<?php echo $category->id; ?></span>
								</td>
								<td>
									<div class="d-flex align-items-center">
										<i class="bi bi-tag me-2 text-info"></i>
										<span class="fw-medium"><?php echo e($category->name); ?></span>
									</div>
								</td>
								<td>
									<?php if ($category->parent_id): ?>
										<span class="badge bg-light text-dark">
											<?php 
												$parent = Model_Category::find($category->parent_id);
												echo $parent ? e($parent->name) : 'Unknown';
											?>
										</span>
									<?php else: ?>
										<span class="text-muted">
											<i class="bi bi-dash"></i> Danh mục gốc
										</span>
									<?php endif; ?>
								</td>
								<td>
									<span class="badge bg-primary">
						<?php 
							// Đếm bài viết theo bảng post_categories
							$count_row = DB::select(DB::expr('COUNT(DISTINCT post_id) as cnt'))
								->from('post_categories')
								->where('category_id', $category->id)
								->execute()
								->current();
							$post_count = $count_row ? (int)$count_row['cnt'] : 0;
							echo $post_count;
						?> bài viết
									</span>
								</td>
								<td>
									<small class="text-muted">
										<i class="bi bi-calendar3 me-1"></i>
										<?php echo date('d/m/Y', strtotime($category->created_at)); ?>
									</small>
								</td>
								<td>
									<div class="btn-group btn-group-sm">
										<a href="<?php echo Uri::create('admin/categories/edit/' . $category->id); ?>" class="btn btn-outline-secondary" title="Chỉnh sửa">
											<i class="bi bi-pencil"></i>
										</a>
										<a href="<?php echo Uri::create('admin/categories/delete/' . $category->id); ?>" class="btn btn-outline-danger btn-delete" title="Xóa danh mục">
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
				<i class="bi bi-tags" style="font-size: 4rem; color: #dee2e6;"></i>
				<h4 class="text-muted mt-3">Chưa có danh mục nào</h4>
				<p class="text-muted">Tạo danh mục đầu tiên để phân loại bài viết</p>
				<a href="<?php echo Uri::create('admin/categories/create'); ?>" class="btn btn-primary">
					<i class="bi bi-plus-circle me-2"></i>Thêm danh mục
				</a>
			</div>
		<?php endif; ?>
	</div>
</div>
