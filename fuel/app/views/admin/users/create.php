<?php
use Fuel\Core\Form;
use Fuel\Core\Uri;
use Fuel\Core\Input;
use Fuel\Core\Session;
?>

<div class="d-flex justify-content-between align-items-center mb-4">
	<div>
		<h5 class="mb-1">Thêm người dùng</h5>
		<p class="text-muted mb-0">Tạo tài khoản người dùng mới trong hệ thống</p>
	</div>
	<div>
		<a href="<?php echo Uri::create('admin/users'); ?>" class="btn btn-secondary">
			<i class="bi bi-arrow-left"></i> Quay lại
		</a>
	</div>
</div>

<div class="card">
	<div class="card-body">
		<?php if (Session::get_flash('error')): ?>
			<div class="alert alert-danger">
				<?php echo Session::get_flash('error'); ?>
			</div>
		<?php endif; ?>
		
		<?php if (Session::get_flash('success')): ?>
			<div class="alert alert-success">
				<?php echo Session::get_flash('success'); ?>
			</div>
		<?php endif; ?>
		
		<?php echo Form::open(array('action' => Uri::create('admin/users/create'), 'method' => 'post', 'class' => 'needs-validation', 'novalidate' => true)); ?>
			<div class="row">
				<div class="col-md-6">
					<div class="mb-3">
						<label class="form-label fw-bold">Họ và tên <span class="text-danger">*</span></label>
						<?php echo Form::input('username', Input::post('username'), array(
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
						<?php echo Form::input('email', Input::post('email'), array(
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
						<label class="form-label fw-bold">Mật khẩu <span class="text-danger">*</span></label>
						<?php echo Form::input('password', '', array(
							'type' => 'password',
							'class' => 'form-control',
							'required' => true,
							'minlength' => 6,
							'placeholder' => 'Nhập mật khẩu'
						)); ?>
						<div class="form-text">Tối thiểu 6 ký tự</div>
					</div>
				</div>
				<div class="col-md-6">
					<div class="mb-3">
						<label class="form-label fw-bold">Xác nhận mật khẩu <span class="text-danger">*</span></label>
						<?php echo Form::input('password_confirm', '', array(
							'type' => 'password',
							'class' => 'form-control',
							'required' => true,
							'minlength' => 6,
							'placeholder' => 'Nhập lại mật khẩu'
						)); ?>
					</div>
				</div>
			</div>

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
							echo Form::select('role_id', Input::post('role_id', 3), $roles, array('class' => 'form-select'));
						?>
					</div>
				</div>
			</div>

			<div class="text-end">
				<button type="submit" class="btn btn-primary">
					<i class="bi bi-check-circle"></i> Tạo người dùng
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
	
	// Kiểm tra mật khẩu khớp nhau
	function validatePassword() {
		if (password.value !== passwordConfirm.value) {
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
