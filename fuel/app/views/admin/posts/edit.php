<?php
use Fuel\Core\Uri;
use Fuel\Core\Form;
use Fuel\Core\Input;
?>

<div class="d-flex justify-content-between align-items-center mb-4">
	<div>
		<h5 class="mb-1">Chỉnh sửa bài viết</h5>
		<p class="text-muted mb-0">Cập nhật nội dung bài viết: <strong><?php $post->title; ?></strong></p>
	</div>
	<div>
		<a href="<?php echo Uri::base(); ?>admin/posts" class="btn btn-secondary me-2">
			<i class="bi bi-arrow-left me-2"></i>Quay lại danh sách
		</a>
		<a href="<?php echo Uri::base(); ?>admin/posts/view/<?php echo $post->id; ?>" class="btn btn-outline-info">
			<i class="bi bi-eye me-2"></i>Xem chi tiết
		</a>
	</div>
</div>

<div class="row">
	<div class="col-lg-8">
		<div class="card">
			<div class="card-header">
				<h6 class="mb-0">
					<i class="bi bi-pencil-square me-2"></i>Chỉnh sửa nội dung
				</h6>
			</div>
			
			<div class="card-body">
				<?php echo Form::open(array('action' => Uri::base() . 'admin/posts/edit/' . $post->id, 'method' => 'post', 'enctype' => 'multipart/form-data', 'class' => 'needs-validation', 'novalidate' => true)); ?>
				
					<!-- Hidden field để identify form submission -->
					<?php echo Form::hidden('submit_form', '1'); ?>
				
					<div class="mb-3">
						<label for="title" class="form-label fw-bold">
							<i class="bi bi-card-heading"></i> Tiêu đề bài viết
							<span class="text-danger">*</span>
						</label>
						<?php echo Form::input('title', Input::post('title', $post->title), array(
							'class' => 'form-control',
							'id' => 'title',
							'placeholder' => 'Nhập tiêu đề bài viết...',
							'required' => true,
							'maxlength' => 255
						)); ?>
						<div class="invalid-feedback">
							Vui lòng nhập tiêu đề bài viết (tối thiểu 5 ký tự).
						</div>
						<div class="form-text">
							<small id="titleCounter">0/255 ký tự</small>
						</div>
					</div>
					
					<!-- Categories Field -->
					<div class="mb-3">
						<label for="category_ids" class="form-label fw-bold">
							<i class="bi bi-tags"></i> Danh mục
							<span class="text-danger">*</span>
						</label>
						<?php 
						// Lấy danh sách category IDs hiện tại của post
						$current_category_ids = array();
						foreach ($post->post_categories as $post_category) {
							$current_category_ids[] = $post_category->category_id;
						}
						?>
						<?php echo Form::select('category_ids[]', Input::post('category_ids', $current_category_ids), $categories, array(
							'class' => 'form-select',
							'id' => 'category_ids',
							'multiple' => true,
							'size' => 5,
							'required' => true
						)); ?>
						<div class="invalid-feedback">
							Vui lòng chọn ít nhất một danh mục cho bài viết.
						</div>
						<div class="form-text">
							<small>Giữ Ctrl (hoặc Cmd trên Mac) để chọn nhiều danh mục.</small>
						</div>
					</div>
					
					<!-- Content Field -->
					<div class="mb-3">
						<label for="content" class="form-label fw-bold">
							<i class="bi bi-file-text"></i> Nội dung bài viết
							<span class="text-danger">*</span>
						</label>
						<?php echo Form::textarea('content', Input::post('content', $post->content), array(
							'class' => 'form-control',
							'id' => 'editor',
							'rows' => 15,
							'placeholder' => 'Viết nội dung bài viết của bạn ở đây...',
							'required' => true
						)); ?>
						<div class="invalid-feedback">
							Vui lòng nhập nội dung bài viết (tối thiểu 10 ký tự).
						</div>
						<div class="form-text">
							<small id="contentCounter">0 ký tự</small>
						</div>
					</div>
					
				<?php echo Form::close(); ?>
			</div>
		</div>

		<!-- Hình ảnh hiện tại -->
		<?php if (isset($post->images) && count($post->images) > 0): ?>
		<div class="card mt-4">
			<div class="card-header d-flex justify-content-between align-items-center">
				<h6 class="mb-0">
					<i class="bi bi-images me-2"></i>Quản lý hình ảnh hiện tại
					<span class="badge bg-secondary ms-2"><?php echo count($post->images); ?></span>
				</h6>
				<div class="image-controls">
					<button type="button" class="btn btn-outline-primary btn-sm" onclick="selectAllImages()">
						<i class="bi bi-check-square me-1"></i>Chọn tất cả
					</button>
					<button type="button" class="btn btn-outline-secondary btn-sm" onclick="deselectAllImages()">
						<i class="bi bi-square me-1"></i>Bỏ chọn
					</button>
					<button type="button" class="btn btn-outline-danger btn-sm" onclick="deleteSelectedImages()" id="deleteSelectedBtn" disabled>
						<i class="bi bi-trash me-1"></i>Xóa đã chọn (<span id="selectedCount">0</span>)
					</button>
				</div>
			</div>
			<div class="card-body">
				<div class="current-images">
					<div class="row g-3">
						<?php foreach ($post->images as $image): ?>
						<div class="col-md-4 col-sm-6">
							<div class="image-item position-relative" data-image-id="<?php echo $image->id; ?>">
								<div class="image-checkbox">
									<input type="checkbox" 
										   class="form-check-input image-select-checkbox" 
										   id="image_<?php echo $image->id; ?>"
										   value="<?php echo $image->id; ?>"
										   onchange="updateSelectedCount()">
									<label for="image_<?php echo $image->id; ?>" class="checkbox-overlay"></label>
								</div>
								<img src="<?php echo Uri::base(); ?>uploads/<?php echo $image->file_path; ?>" 
									 alt="Current Image" 
									 class="img-fluid rounded image-thumbnail"
									 onclick="toggleImageSelection(<?php echo $image->id; ?>)">
								<div class="image-info mt-2">
									<small class="text-muted d-block">
										<i class="bi bi-file-earmark"></i>
										<?php echo $image->file_path; ?>
									</small>
									<small class="text-muted">
										ID: <?php echo $image->id; ?>
									</small>
								</div>
							</div>
						</div>
						<?php endforeach; ?>
					</div>
				</div>
				
				<!-- Bulk delete confirmation -->
				<div class="bulk-delete-info mt-3 p-3 bg-light rounded" id="bulkDeleteInfo" style="display: none;">
					<div class="d-flex align-items-center justify-content-between">
						<div>
							<i class="bi bi-info-circle text-primary me-2"></i>
							<span id="bulkDeleteText">Đã chọn 0 ảnh để xóa</span>
						</div>
						<div>
							<button type="button" class="btn btn-sm btn-danger" onclick="confirmBulkDelete()">
								<i class="bi bi-trash me-1"></i>Xác nhận xóa
							</button>
							<button type="button" class="btn btn-sm btn-secondary" onclick="cancelBulkDelete()">
								Hủy
							</button>
						</div>
					</div>
				</div>
			</div>
		</div>
		<?php endif; ?>
	</div>
	
	<!-- Sidebar -->
	<div class="col-lg-4">
		<!-- Publishing Options -->
		<div class="card mb-3">
			<div class="card-header">
				<h6 class="mb-0">
					<i class="bi bi-gear me-2"></i>Tùy chọn xuất bản
				</h6>
			</div>
			<div class="card-body">
				<div class="form-check mb-3">
					<?php echo Form::checkbox('is_published', 1, Input::post('is_published', $post->is_published), array(
						'class' => 'form-check-input',
						'id' => 'is_published',
						'form' => 'editForm'
					)); ?>
					<label class="form-check-label" for="is_published">
						<i class="bi bi-eye me-1"></i> Xuất bản bài viết
					</label>
					<div class="form-text">
						<small>Bỏ chọn để lưu dưới dạng bản nháp.</small>
					</div>
				</div>

				<div class="form-check mb-3">
					<?php echo Form::checkbox('is_published_date', 1, Input::post('is_published_date', !empty($post->publish_start_date)), array(
						'class' => 'form-check-input',
						'id' => 'is_published_date',
						'form' => 'editForm'
					)); ?>
					<label class="form-check-label" for="is_published_date">
						<i class="bi bi-calendar-check me-1"></i> Lên lịch xuất bản
					</label>
					<div class="form-text">
						<small>Đặt thời gian cụ thể cho việc xuất bản bài viết.</small>
					</div>
				</div>

				<div id="publishDatesBox" style="display:<?php echo !empty($post->publish_start_date) ? 'block' : 'none'; ?>">
					<div class="mb-3">
						<label class="form-label fw-bold">
						<i class="bi bi-calendar-plus"></i> Ngày bắt đầu xuất bản
						</label>
						<?php 
						// Format ngày tháng cho datetime-local input (Y-m-d\TH:i)
						$publish_start_formatted = '';
						if (!empty($post->publish_start_date)) {
							$publish_start_formatted = date('Y-m-d\TH:i', strtotime($post->publish_start_date));
						}
						echo Form::input('publish_start_date', Input::post('publish_start_date', $publish_start_formatted), array(
						'class' => 'form-control',
						'type' => 'datetime-local',
						'id' => 'publish_start_date',
						'form' => 'editForm'
						)); ?>
					</div>

					<div class="mb-3">
						<div class="d-flex justify-content-between align-items-center">
						<label class="form-label fw-bold mb-0">
							<i class="bi bi-calendar-x"></i> Ngày kết thúc xuất bản
						</label>
						<div class="form-check">
							<?php $no_end_date = empty($post->publish_end_date); ?>
							<input type="checkbox" class="form-check-input" id="no_end_date" form="editForm" <?php echo $no_end_date ? 'checked' : ''; ?>>
							<label for="no_end_date" class="form-check-label">Không có ngày kết thúc</label>
						</div>
						</div>
						<?php 
						// Format ngày kết thúc cho datetime-local input
						$publish_end_formatted = '';
						if (!empty($post->publish_end_date)) {
							$publish_end_formatted = date('Y-m-d\TH:i', strtotime($post->publish_end_date));
						}
						echo Form::input('publish_end_date', Input::post('publish_end_date', $publish_end_formatted), array(
						'class' => 'form-control',
						'type' => 'datetime-local',
						'id' => 'publish_end_date',
						'disabled' => $no_end_date,
						'form' => 'editForm'
						)); ?>
					</div>
				</div>
				
				<!-- Views Field -->
				<div class="mb-3">
					<label for="views" class="form-label fw-bold">
						<i class="bi bi-eye"></i> Số lượt xem
					</label>
					<?php echo Form::input('views', Input::post('views', $post->views), array(
						'class' => 'form-control',
						'id' => 'views',
						'type' => 'number',
						'min' => '0',
						'placeholder' => 'Nhập số lượt xem...',
						'form' => 'editForm'
					)); ?>
					<div class="form-text">
						<small>Để trống để giữ nguyên số lượt xem hiện tại.</small>
					</div>
				</div>

				<div class="post-info mb-3">
					<div class="row text-center">
						<div class="col-6">
							<div class="info-box">
								<i class="bi bi-calendar3 text-primary"></i>
								<p class="mb-0 small fw-bold">Ngày tạo</p>
								<p class="mb-0 small text-muted">
									<?php echo date('d/m/Y', strtotime($post->created_at)); ?>
								</p>
							</div>
						</div>
						<div class="col-6">
							<div class="info-box">
								<i class="bi bi-clock text-warning"></i>
								<p class="mb-0 small fw-bold">Cập nhật</p>
								<p class="mb-0 small text-muted">
									<?php echo date('d/m/Y', strtotime($post->updated_at)); ?>
								</p>
							</div>
						</div>
					</div>
				</div>
				
				<div class="d-grid gap-2">
					<button type="submit" form="editForm" class="btn btn-primary">
						<i class="bi bi-check-circle me-2"></i>Cập nhật bài viết
					</button>
					<button type="button" class="btn btn-outline-secondary" onclick="saveDraft()">
						<i class="bi bi-save me-2"></i>Lưu nháp
					</button>
				</div>
			</div>
		</div>
		
		<!-- Images Upload -->
		<div class="card">
			<div class="card-header">
				<h6 class="mb-0">
					<i class="bi bi-images me-2"></i>Thêm hình ảnh mới
				</h6>
			</div>
			<div class="card-body">
				<div class="upload-area text-center p-3 border-2 border-dashed rounded" id="uploadArea">
					<i class="bi bi-cloud-upload" style="font-size: 2rem; color: #6c757d; opacity: 0.7;"></i>
					<h6 class="mt-2">Kéo thả hình ảnh</h6>
					<p class="text-muted small">hoặc</p>
					<input type="file" 
						   name="images[]" 
						   id="images" 
						   class="form-control d-none" 
						   multiple 
						   accept="image/*"
						   form="editForm">
					<button type="button" class="btn btn-outline-primary btn-sm" onclick="document.getElementById('images').click()">
						<i class="bi bi-folder-plus me-1"></i> Chọn file
					</button>
					<div class="form-text mt-2">
						<small>JPG, PNG, GIF. Tối đa 5MB</small>
					</div>
				</div>
				
				<!-- Preview New Images -->
				<div class="image-preview mt-3" id="imagePreview" style="display: none;">
					<h6 class="small">Ảnh mới sẽ thêm:</h6>
					<div class="image-gallery" id="imageGallery"></div>
				</div>
			</div>
		</div>

		<!-- Post Info -->
		<div class="card mt-3">
			<div class="card-header">
				<h6 class="mb-0">
					<i class="bi bi-info-circle me-2"></i>Thông tin bài viết
				</h6>
			</div>
			<div class="card-body">
				<div class="info-details">
					<p class="mb-2">
						<strong>ID:</strong> <code>#<?php echo $post->id; ?></code>
					</p>
					<p class="mb-2">
						<strong>Slug:</strong> 
						<code><?php echo $post->slug; ?></code>
					</p>
					<p class="mb-2">
						<strong>Trạng thái:</strong>
						<?php if ($post->is_published): ?>
							<span class="badge bg-success">Đã xuất bản</span>
						<?php else: ?>
							<span class="badge bg-secondary">Bản nháp</span>
						<?php endif; ?>
					</p>
					<p class="mb-0">
						<strong>Hình ảnh:</strong>
						<span class="badge bg-info">
							<?php echo isset($post->images) ? count($post->images) : 0; ?> ảnh
						</span>
					</p>
				</div>
			</div>
		</div>
	</div>
</div>

<style>
.image-gallery {
	display: grid;
	grid-template-columns: repeat(auto-fill, minmax(80px, 1fr));
	gap: 10px;
}

.image-item {
	position: relative;
	border-radius: 8px;
	overflow: hidden;
	aspect-ratio: 1;
}

.image-item img {
	width: 100%;
	height: 100%;
	object-fit: cover;
}

.image-item .delete-btn {
	position: absolute;
	top: 5px;
	right: 5px;
	background: rgba(220, 53, 69, 0.8);
	color: white;
	border: none;
	border-radius: 50%;
	width: 24px;
	height: 24px;
	font-size: 12px;
	cursor: pointer;
	display: flex;
	align-items: center;
	justify-content: center;
}

.upload-area:hover {
	background-color: #f8f9fa;
}

.current-images .image-item img {
	aspect-ratio: 16/9;
	object-fit: cover;
}

.image-overlay {
	position: relative;
}

.info-box {
	padding: 10px;
	border-radius: 8px;
	background: #f8f9fa;
}

.info-box i {
	font-size: 1.2rem;
	margin-bottom: 5px;
}

.info-details {
	font-size: 0.9rem;
}

.info-details code {
	background: #e9ecef;
	padding: 2px 6px;
	border-radius: 4px;
	font-size: 0.8rem;
}

/* Image Selection Styles */
.image-item {
	cursor: pointer;
	transition: all 0.3s ease;
	border: 3px solid transparent;
}

.image-item.selected {
	border-color: #667eea;
	box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);
	transform: translateY(-2px);
}

.image-checkbox {
	position: absolute;
	top: 10px;
	left: 10px;
	z-index: 10;
}

.image-checkbox .form-check-input {
	width: 20px;
	height: 20px;
	border: 2px solid white;
	box-shadow: 0 2px 8px rgba(0,0,0,0.3);
}

.image-checkbox .form-check-input:checked {
	background-color: #667eea;
	border-color: #667eea;
}

.checkbox-overlay {
	position: absolute;
	top: -5px;
	left: -5px;
	width: 30px;
	height: 30px;
	cursor: pointer;
}

.image-thumbnail {
	cursor: pointer;
	transition: all 0.3s ease;
}

.image-thumbnail:hover {
	opacity: 0.8;
}

.image-controls .btn {
	margin-left: 5px;
}

.bulk-delete-info {
	border: 1px solid #dee2e6;
}

/* Responsive adjustments */
@media (max-width: 768px) {
	.image-controls {
		flex-direction: column;
		align-items: stretch;
	}
	
	.image-controls .btn {
		margin: 2px 0;
		margin-left: 0;
	}
}
</style>

<!-- Custom JavaScript -->
<script>
document.addEventListener('DOMContentLoaded', function() {
	// Add form id to the form element
	const form = document.querySelector('.needs-validation');
	form.id = 'editForm';
	
	// Character counters
	const titleInput = document.getElementById('title');
	const contentInput = document.getElementById('content');
	const titleCounter = document.getElementById('titleCounter');
	const contentCounter = document.getElementById('contentCounter');

	const toggle = document.getElementById('is_published_date');
	const box = document.getElementById('publishDatesBox');
	const noEnd = document.getElementById('no_end_date');
	const endInput = document.getElementById('publish_end_date');

	function syncBox() { box.style.display = toggle.checked ? '' : 'none'; }
	function syncEnd() { 
		endInput.disabled = noEnd.checked; 
		if (noEnd.checked) endInput.value = '';
	}
	if (toggle) {
		syncBox();
		toggle.addEventListener('change', syncBox);
	}
	if (noEnd) {
		syncEnd();
		noEnd.addEventListener('change', syncEnd);
	}
	
	// Initialize counters
	updateTitleCounter();
	updateContentCounter();
	
	titleInput.addEventListener('input', updateTitleCounter);
	contentInput.addEventListener('input', updateContentCounter);
	
	function updateTitleCounter() {
		titleCounter.textContent = titleInput.value.length + '/255 ký tự';
	}
	
	function updateContentCounter() {
		contentCounter.textContent = contentInput.value.length + ' ký tự';
	}
	
	// Image upload handling
	const uploadArea = document.getElementById('uploadArea');
	const imageInput = document.getElementById('images');
	const imagePreview = document.getElementById('imagePreview');
	const imageGallery = document.getElementById('imageGallery');
	
	// Drag and drop
	uploadArea.addEventListener('dragover', function(e) {
		e.preventDefault();
		this.style.borderColor = '#667eea';
		this.style.backgroundColor = '#f8f9fa';
	});
	
	uploadArea.addEventListener('dragleave', function(e) {
		e.preventDefault();
		this.style.borderColor = '';
		this.style.backgroundColor = '';
	});
	
	uploadArea.addEventListener('drop', function(e) {
		e.preventDefault();
		this.style.borderColor = '';
		this.style.backgroundColor = '';
		
		const files = e.dataTransfer.files;
		imageInput.files = files;
		handleFiles(files);
	});
	
	imageInput.addEventListener('change', function() {
		handleFiles(this.files);
	});
	
	function handleFiles(files) {
		imageGallery.innerHTML = '';
		
		if (files.length > 0) {
			imagePreview.style.display = 'block';
			
			Array.from(files).forEach(function(file, index) {
				if (file.type.startsWith('image/')) {
					const reader = new FileReader();
					reader.onload = function(e) {
						const imageItem = document.createElement('div');
						imageItem.className = 'image-item';
						imageItem.innerHTML = `
							<img src="${e.target.result}" alt="Preview">
							<button type="button" class="delete-btn" onclick="removeNewImage(${index})">
								<i class="bi bi-x"></i>
							</button>
						`;
						imageGallery.appendChild(imageItem);
					};
					reader.readAsDataURL(file);
				}
			});
		} else {
			imagePreview.style.display = 'none';
		}
	}
	
	// Form validation
	form.addEventListener('submit', function(e) {
		if (!form.checkValidity()) {
			e.preventDefault();
			e.stopPropagation();
		}
		form.classList.add('was-validated');
	});
});

function removeNewImage(index) {
	console.log('Remove new image at index:', index);
	// Reset file input to remove the selected images
	document.getElementById('images').value = '';
	document.getElementById('imagePreview').style.display = 'none';
	document.getElementById('imageGallery').innerHTML = '';
}

// Image selection functions
function selectAllImages() {
	const checkboxes = document.querySelectorAll('.image-select-checkbox');
	checkboxes.forEach(checkbox => {
		checkbox.checked = true;
		const imageItem = checkbox.closest('.image-item');
		imageItem.classList.add('selected');
	});
	updateSelectedCount();
}

function deselectAllImages() {
	const checkboxes = document.querySelectorAll('.image-select-checkbox');
	checkboxes.forEach(checkbox => {
		checkbox.checked = false;
		const imageItem = checkbox.closest('.image-item');
		imageItem.classList.remove('selected');
	});
	updateSelectedCount();
}

function toggleImageSelection(imageId) {
	const checkbox = document.getElementById(`image_${imageId}`);
	checkbox.checked = !checkbox.checked;
	
	const imageItem = checkbox.closest('.image-item');
	if (checkbox.checked) {
		imageItem.classList.add('selected');
	} else {
		imageItem.classList.remove('selected');
	}
	updateSelectedCount();
}

function updateSelectedCount() {
	const selectedCheckboxes = document.querySelectorAll('.image-select-checkbox:checked');
	const count = selectedCheckboxes.length;
	
	document.getElementById('selectedCount').textContent = count;
	document.getElementById('deleteSelectedBtn').disabled = count === 0;
	
	const bulkDeleteInfo = document.getElementById('bulkDeleteInfo');
	const bulkDeleteText = document.getElementById('bulkDeleteText');
	
	if (count > 0) {
		bulkDeleteInfo.style.display = 'block';
		bulkDeleteText.textContent = `Đã chọn ${count} ảnh để xóa`;
	} else {
		bulkDeleteInfo.style.display = 'none';
	}
}

function deleteSelectedImages() {
	const selectedCheckboxes = document.querySelectorAll('.image-select-checkbox:checked');
	if (selectedCheckboxes.length === 0) {
		alert('Vui lòng chọn ít nhất một ảnh để xóa.');
		return;
	}
	
	const count = selectedCheckboxes.length;
	if (confirm(`Bạn có chắc chắn muốn xóa ${count} ảnh đã chọn?`)) {
		// Add hidden inputs to mark for deletion
		const form = document.getElementById('editForm');
		
		selectedCheckboxes.forEach(checkbox => {
			const hiddenInput = document.createElement('input');
			hiddenInput.type = 'hidden';
			hiddenInput.name = 'delete_images[]';
			hiddenInput.value = checkbox.value;
			form.appendChild(hiddenInput);
			
			// Hide the image visually
			const imageItem = checkbox.closest('.image-item');
			imageItem.style.opacity = '0.3';
			imageItem.style.pointerEvents = 'none';
		});
		
		// Hide bulk delete info
		document.getElementById('bulkDeleteInfo').style.display = 'none';
		
		// Show success message
		alert(`Đã đánh dấu ${count} ảnh để xóa. Nhấn "Cập nhật bài viết" để hoàn tất.`);
	}
}

function confirmBulkDelete() {
	deleteSelectedImages();
}

function cancelBulkDelete() {
	deselectAllImages();
}

function removeCurrentImage(imageId) {
	if (confirm('Bạn có chắc chắn muốn xóa ảnh này?')) {
		// Use the new selection system
		const checkbox = document.getElementById(`image_${imageId}`);
		if (checkbox) {
			checkbox.checked = true;
			toggleImageSelection(imageId);
			deleteSelectedImages();
		}
	}
}

function saveDraft(isAutoSave = false) {
	const form = document.getElementById('editForm');
	const formData = new FormData(form);
	formData.set('is_published', '0');
	
	if (!isAutoSave) {
		const saveBtn = event.target;
		const originalText = saveBtn.innerHTML;
		saveBtn.innerHTML = '<i class="bi bi-hourglass-split"></i> Đang lưu...';
		saveBtn.disabled = true;
		
		setTimeout(function() {
			saveBtn.innerHTML = originalText;
			saveBtn.disabled = false;
		}, 2000);
	}
	
	console.log('Saving draft...', isAutoSave ? '(auto)' : '(manual)');
}

// Auto-save every 2 minutes
setInterval(function() {
	const titleInput = document.getElementById('title');
	const contentInput = document.getElementById('content');
	
	if (titleInput.value.trim() || contentInput.value.trim()) {
		saveDraft(true);
	}
}, 120000); // 2 minutes

CKEDITOR.ClassicEditor.create(document.getElementById("editor"), {
	// https://ckeditor.com/docs/ckeditor5/latest/getting-started/setup/toolbar/toolbar.html#extended-toolbar-configuration-format
	toolbar: {
		items: [
			'exportPDF','exportWord', '|',
			'findAndReplace', 'selectAll', '|',
			'heading', '|',
			'bold', 'italic', 'strikethrough', 'underline', 'code', 'subscript', 'superscript', 'removeFormat', '|',
			'bulletedList', 'numberedList', 'todoList', '|',
			'outdent', 'indent', '|',
			'undo', 'redo',
			'-',
			'fontSize', 'fontFamily', 'fontColor', 'fontBackgroundColor', 'highlight', '|',
			'alignment', '|',
			'link', 'uploadImage', 'blockQuote', 'insertTable', 'mediaEmbed', 'codeBlock', 'htmlEmbed', '|',
			'specialCharacters', 'horizontalLine', 'pageBreak', '|',
			'textPartLanguage', '|',
			'sourceEditing'
		],
		shouldNotGroupWhenFull: true
	},
	// Changing the language of the interface requires loading the language file using the <script> tag.
	// language: 'es',
	list: {
		properties: {
			styles: true,
			startIndex: true,
			reversed: true
		}
	},
	// https://ckeditor.com/docs/ckeditor5/latest/features/headings.html#configuration
	heading: {
		options: [
			{ model: 'paragraph', title: 'Paragraph', class: 'ck-heading_paragraph' },
			{ model: 'heading1', view: 'h1', title: 'Heading 1', class: 'ck-heading_heading1' },
			{ model: 'heading2', view: 'h2', title: 'Heading 2', class: 'ck-heading_heading2' },
			{ model: 'heading3', view: 'h3', title: 'Heading 3', class: 'ck-heading_heading3' },
			{ model: 'heading4', view: 'h4', title: 'Heading 4', class: 'ck-heading_heading4' },
			{ model: 'heading5', view: 'h5', title: 'Heading 5', class: 'ck-heading_heading5' },
			{ model: 'heading6', view: 'h6', title: 'Heading 6', class: 'ck-heading_heading6' }
		]
	},
	// https://ckeditor.com/docs/ckeditor5/latest/features/editor-placeholder.html#using-the-editor-configuration
	placeholder: 'Welcome to CKEditor 5!',
	// https://ckeditor.com/docs/ckeditor5/latest/features/font.html#configuring-the-font-family-feature
	fontFamily: {
		options: [
			'default',
			'Arial, Helvetica, sans-serif',
			'Courier New, Courier, monospace',
			'Georgia, serif',
			'Lucida Sans Unicode, Lucida Grande, sans-serif',
			'Tahoma, Geneva, sans-serif',
			'Times New Roman, Times, serif',
			'Trebuchet MS, Helvetica, sans-serif',
			'Verdana, Geneva, sans-serif'
		],
		supportAllValues: true
	},
	// https://ckeditor.com/docs/ckeditor5/latest/features/font.html#configuring-the-font-size-feature
	fontSize: {
		options: [ 10, 12, 14, 'default', 18, 20, 22 ],
		supportAllValues: true
	},
	// Be careful with the setting below. It instructs CKEditor to accept ALL HTML markup.
	// https://ckeditor.com/docs/ckeditor5/latest/features/general-html-support.html#enabling-all-html-features
	htmlSupport: {
		allow: [
			{
				name: /.*/,
				attributes: true,
				classes: true,
				styles: true
			}
		]
	},
	// Be careful with enabling previews
	// https://ckeditor.com/docs/ckeditor5/latest/features/html-embed.html#content-previews
	htmlEmbed: {
		showPreviews: false
	},
	// https://ckeditor.com/docs/ckeditor5/latest/features/link.html#custom-link-attributes-decorators
	link: {
		decorators: {
			addTargetToExternalLinks: true,
			defaultProtocol: 'https://',
			toggleDownloadable: {
				mode: 'manual',
				label: 'Downloadable',
				attributes: {
					download: 'file'
				}
			}
		}
	},
	// https://ckeditor.com/docs/ckeditor5/latest/features/mentions.html#configuration
	mention: {
		feeds: [
			{
				marker: '@',
				feed: [
					'@apple', '@bears', '@brownie', '@cake', '@cake', '@candy', '@canes', '@chocolate', '@cookie', '@cotton', '@cream',
					'@cupcake', '@danish', '@donut', '@dragée', '@fruitcake', '@gingerbread', '@gummi', '@ice', '@jelly-o',
					'@liquorice', '@macaroon', '@marzipan', '@oat', '@pie', '@plum', '@pudding', '@sesame', '@snaps', '@soufflé',
					'@sugar', '@sweet', '@topping', '@wafer'
				],
				minimumCharacters: 1
			}
		]
	},
	// The "superbuild" contains more premium features that require additional configuration, disable them below.
	// Do not turn them on unless you read the documentation and know how to configure them and setup the editor.
	removePlugins: [
		// These two are commercial, but you can try them out without registering to a trial.
		// 'ExportPdf',
		// 'ExportWord',
		'AIAssistant',
		'CKBox',
		'CKFinder',
		'EasyImage',
		// This sample uses the Base64UploadAdapter to handle image uploads as it requires no configuration.
		// https://ckeditor.com/docs/ckeditor5/latest/features/images/image-upload/base64-upload-adapter.html
		// Storing images as Base64 is usually a very bad idea.
		// Replace it on production website with other solutions:
		// https://ckeditor.com/docs/ckeditor5/latest/features/images/image-upload/image-upload.html
		// 'Base64UploadAdapter',
		'MultiLevelList',
		'RealTimeCollaborativeComments',
		'RealTimeCollaborativeTrackChanges',
		'RealTimeCollaborativeRevisionHistory',
		'PresenceList',
		'Comments',
		'TrackChanges',
		'TrackChangesData',
		'RevisionHistory',
		'Pagination',
		'WProofreader',
		// Careful, with the Mathtype plugin CKEditor will not load when loading this sample
		// from a local file system (file://) - load this site via HTTP server if you enable MathType.
		'MathType',
		// The following features require additional license.
		'SlashCommand',
		'Template',
		'DocumentOutline',
		'FormatPainter',
		'TableOfContents',
		'PasteFromOfficeEnhanced',
		'CaseChange'
	]
});
</script>
