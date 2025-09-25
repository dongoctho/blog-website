<?php
use Fuel\Core\Form;
use Fuel\Core\Uri;
use Fuel\Core\Input;
?>

<div class="d-flex justify-content-between align-items-center mb-4">
	<div>
		<h5 class="mb-1">Chỉnh sửa danh mục</h5>
		<p class="text-muted mb-0">Cập nhật thông tin danh mục hiện có</p>
	</div>
	<div>
		<a href="<?php echo Uri::create('admin/categories'); ?>" class="btn btn-secondary">
			<i class="bi bi-arrow-left"></i> Quay lại
		</a>
	</div>
</div>

<div class="card">
	<div class="card-body">
		<?php echo Form::open(array('action' => Uri::create('admin/categories/edit/'.$category->id), 'method' => 'post', 'class' => 'needs-validation', 'novalidate' => true)); ?>
			<div class="mb-3">
				<label class="form-label fw-bold">Tên danh mục <span class="text-danger">*</span></label>
				<?php echo Form::input('name', Input::post('name', $category->name), array(
					'class' => 'form-control',
					'required' => true,
					'maxlength' => 100
				)); ?>
			</div>

			<div class="mb-3">
				<label class="form-label fw-bold">Danh mục cha</label>
				<?php 
					$parents = isset($parents) ? $parents : array();
					$options = array('' => '— Danh mục gốc —');
					foreach ($parents as $p) { $options[$p->id] = $p->name; }
					echo Form::select('parent_id', Input::post('parent_id', $category->parent_id), $options, array('class' => 'form-select'));
				?>
			</div>

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
	form.addEventListener('submit', function(e) {
		if (!form.checkValidity()) {
			e.preventDefault();
			e.stopPropagation();
		}
		form.classList.add('was-validated');
	});
});
</script>


