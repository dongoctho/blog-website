<?php
use Fuel\Core\Form;
use Fuel\Core\Uri;
use Fuel\Core\Input;
?>

<div class="d-flex justify-content-between align-items-center mb-4">
	<div>
		<h5 class="mb-1">Chỉnh sửa người dùng</h5>
		<p class="text-muted mb-0">Cập nhật thông tin tài khoản người dùng</p>
	</div>
	<div>
		<a href="<?php echo Uri::create('admin/users'); ?>" class="btn btn-secondary">
			<i class="bi bi-arrow-left"></i> Quay lại
		</a>
	</div>
</div>

<div class="card">
	<div class="card-body">
		<?php echo Form::open(array('action' => Uri::create('admin/users/edit/'.$user->id), 'method' => 'post', 'class' => 'needs-validation', 'novalidate' => true)); ?>
			<div class="row">
				<div class="col-md-6">
					<div class="mb-3">
						<label class="form-label fw-bold">Họ và tên <span class="text-danger">*</span></label>
						<?php echo Form::input('username', Input::post('username', $user->username), array(
							'class' => 'form-control',
							'required' => true,
							'maxlength' => 50,
							'placeholder' => 'Nhập họ và tên'
						)); ?>
						<div class="form-text">Họ và tên (tối đa 50 ký tự)</div>
					</div>
				</div>
				<div class="col-md-6">
					<div class="mb-3">
						<label class="form-label fw-bold">Email <span class="text-danger">*</span></label>
						<?php echo Form::input('email', Input::post('email', $user->email), array(
							'type' => 'email',
							'class' => 'form-control',
							'required' => true,
							'maxlength' => 100,
							'placeholder' => 'Nhập địa chỉ email'
						)); ?>
					</div>
				</div>
			</div>

			<div class="row">
				<div class="col-md-6">
					<div class="mb-3">
						<label class="form-label fw-bold">Mật khẩu mới</label>
						<?php echo Form::input('password', '', array(
							'type' => 'password',
							'class' => 'form-control',
							'minlength' => 6,
							'placeholder' => 'Để trống nếu không đổi mật khẩu'
						)); ?>
						<div class="form-text">Tối thiểu 6 ký tự (để trống nếu không đổi)</div>
					</div>
				</div>
				<div class="col-md-6">
					<div class="mb-3">
						<label class="form-label fw-bold">Xác nhận mật khẩu mới</label>
						<?php echo Form::input('password_confirm', '', array(
							'type' => 'password',
							'class' => 'form-control',
							'minlength' => 6,
							'placeholder' => 'Nhập lại mật khẩu mới'
						)); ?>
					</div>
				</div>
			</div>

			<?php if ($user->role_id != 1): ?>
			<div class="row">
				<div class="col-md-6">
					<div class="mb-3">
						<label class="form-label fw-bold">Vai trò <span class="text-danger">*</span></label>
						<?php 
							$roles = array(
								1 => 'Quản trị viên',
								2 => 'Biên tập viên', 
								3 => 'Người dùng'
							);
							echo Form::select('role_id', Input::post('role_id', $user->role_id), $roles, array('class' => 'form-select'));
						?>
					</div>
				</div>
			</div>
			<?php endif; ?>

			<?php if ($user->is_google_account): ?>
				<div class="alert alert-info">
					<i class="bi bi-info-circle me-2"></i>
					<strong>Lưu ý:</strong> Đây là tài khoản Google. Một số thông tin có thể không thể chỉnh sửa.
				</div>
			<?php endif; ?>

			<div class="text-end">
				<button type="submit" class="btn btn-primary">
					<i class="bi bi-check-circle"></i> Cập nhật
				</button>
			</div>
		<?php echo Form::close(); ?>
	</div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
	var form = document.querySelector('.needs-validation');
	var password = document.querySelector('input[name="password"]');
	var passwordConfirm = document.querySelector('input[name="password_confirm"]');
	
	// Kiểm tra mật khẩu khớp nhau (chỉ khi có nhập mật khẩu)
	function validatePassword() {
		if (password.value && password.value !== passwordConfirm.value) {
			passwordConfirm.setCustomValidity('Mật khẩu không khớp');
		} else {
			passwordConfirm.setCustomValidity('');
		}
	}
	
	password.addEventListener('change', validatePassword);
	passwordConfirm.addEventListener('keyup', validatePassword);
	
	form.addEventListener('submit', function(e) {
		validatePassword();
		if (!form.checkValidity()) {
			e.preventDefault();
			e.stopPropagation();
		}
		form.classList.add('was-validated');
	});
});
</script>
