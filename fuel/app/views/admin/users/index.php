<?php
use Fuel\Core\Uri;
?>

<div class="d-flex justify-content-between align-items-center mb-4">
	<div>
		<h5 class="mb-1">Quản lý người dùng</h5>
		<p class="text-muted mb-0">Danh sách tất cả người dùng trong hệ thống</p>
	</div>
	<div>
		<a href="<?php echo Uri::create('admin/users/create'); ?>" class="btn btn-primary">
			<i class="bi bi-plus-circle"></i> Thêm người dùng
		</a>
	</div>
</div>

<?php if (isset($users) && count($users) > 0): ?>
	<div class="card">
		<div class="card-body p-0">
			<div class="table-responsive">
				<table class="table table-hover mb-0">
					<thead class="table-light">
						<tr>
							<th class="border-0">Tên người dùng</th>
							<th class="border-0">Email</th>
							<th class="border-0">Vai trò</th>
							<th class="border-0">Trạng thái</th>
							<th class="border-0">Ngày tạo</th>
							<th class="border-0 text-center">Thao tác</th>
						</tr>
					</thead>
					<tbody>
						<?php foreach ($users as $user): ?>
							<tr>
								<td>
									<div class="d-flex align-items-center">
										<?php if ($user->google_avatar): ?>
											<img src="<?php echo $user->google_avatar; ?>" alt="Avatar" class="rounded-circle me-2" width="32" height="32">
										<?php else: ?>
											<div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-2" style="width: 32px; height: 32px;">
												<?php echo strtoupper(substr($user->username, 0, 1)); ?>
											</div>
										<?php endif; ?>
										<div>
											<div class="fw-semibold"><?php echo htmlspecialchars($user->username); ?></div>
											<?php if ($user->is_google_account): ?>
												<small class="text-muted">
													<i class="bi bi-google text-danger"></i> Google Account
												</small>
											<?php endif; ?>
										</div>
									</div>
								</td>
								<td>
									<span class="text-muted"><?php echo htmlspecialchars($user->email); ?></span>
								</td>
								<td>
									<?php
									$role_name = 'User';
									if ($user->role_id == 1) $role_name = 'Admin';
									elseif ($user->role_id == 2) $role_name = 'Editor';
									?>
									<span class="badge bg-<?php echo $user->role_id == 1 ? 'danger' : ($user->role_id == 2 ? 'warning' : 'secondary'); ?>">
										<?php echo $role_name; ?>
									</span>
								</td>
								<td>
									<small class="text-muted"><?php echo date('d/m/Y H:i', strtotime($user->created_at)); ?></small>
								</td>
								<td>
									<div class="btn-group btn-group-sm">
										<a href="<?php echo Uri::create('admin/users/edit/' . $user->id); ?>" class="btn btn-outline-secondary" title="Chỉnh sửa">
											<i class="bi bi-pencil"></i>
										</a>
										<?php if ($user->id != 1): // Không cho xóa admin đầu tiên ?>
											<a href="<?php echo Uri::create('admin/users/delete/' . $user->id); ?>" class="btn btn-outline-danger btn-delete" title="Xóa người dùng">
												<i class="bi bi-trash"></i>
											</a>
										<?php endif; ?>
									</div>
								</td>
							</tr>
						<?php endforeach; ?>
					</tbody>
				</table>
			</div>
		</div>
	</div>

	<?php if (isset($pagination) && $pagination): ?>
		<div class="d-flex justify-content-center mt-4">
			<?php echo $pagination; ?>
		</div>
	<?php endif; ?>

<?php else: ?>
	<div class="text-center py-5">
		<div class="mb-3">
			<i class="bi bi-people text-muted" style="font-size: 3rem;"></i>
		</div>
		<h4 class="text-muted mt-3">Chưa có người dùng nào</h4>
		<p class="text-muted">Tạo người dùng đầu tiên để bắt đầu sử dụng hệ thống</p>
		<a href="<?php echo Uri::create('admin/users/create'); ?>" class="btn btn-primary">
			<i class="bi bi-plus-circle me-2"></i>Thêm người dùng
		</a>
	</div>
<?php endif; ?>

<script>
document.addEventListener('DOMContentLoaded', function() {
	// Xử lý xóa người dùng
	document.querySelectorAll('.btn-delete').forEach(function(btn) {
		btn.addEventListener('click', function(e) {
			e.preventDefault();
			if (confirm('Bạn có chắc chắn muốn xóa người dùng này? Hành động này không thể hoàn tác.')) {
				window.location.href = this.href;
			}
		});
	});
});
</script>