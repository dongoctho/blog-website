<?php
use Fuel\Core\Arr;
use Fuel\Core\Uri;
use Fuel\Core\Form;
use Fuel\Core\Input;
?>

<div class="d-flex justify-content-between align-items-center mb-4">
	<div>
		<h5 class="mb-1">Tạo bài viết mới</h5>
		<p class="text-muted mb-0">Viết và xuất bản bài viết mới</p>
	</div>
	<div>
		<a href="<?php echo Uri::base(); ?>admin/posts" class="btn btn-secondary">
			<i class="bi bi-arrow-left me-2"></i>Quay lại danh sách
		</a>
	</div>
</div>

<div class="row">
	<div class="col-lg-8">
		<div class="card">
			<div class="card-header">
				<h6 class="mb-0">
					<i class="bi bi-pencil-square me-2"></i>Nội dung bài viết
				</h6>
			</div>
			
			<div class="card-body">
				<?php echo Form::open(array('action' => Uri::base() . 'admin/posts/create', 'method' => 'post', 'enctype' => 'multipart/form-data', 'class' => 'needs-validation', 'novalidate' => true)); ?>
				
					<!-- Hidden field để identify form submission -->
					<?php echo Form::hidden('submit_form', '1'); ?>
				
					<div class="mb-3">
						<label for="title" class="form-label fw-bold">
							<i class="bi bi-card-heading"></i> Tiêu đề bài viết
							<span class="text-danger">*</span>
						</label>
						<?php echo Form::input('title', Input::post('title'), array(
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
						<?php echo Form::select('category_ids[]', Input::post('category_ids', array()), $categories, array(
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
						<?php echo Form::textarea('content', Input::post('content'), array(
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
					<?php echo Form::checkbox('is_published', 1, Input::post('is_published', false), array(
						'class' => 'form-check-input',
						'id' => 'is_published',
						'form' => 'postForm'
					)); ?>
					<label class="form-check-label" for="is_published">
						<i class="bi bi-eye me-1"></i> Xuất bản ngay
					</label>
					<div class="form-text">
						<small>Nếu không chọn, bài viết sẽ được lưu dưới dạng bản nháp.</small>
					</div>
				</div>

				<div class="form-check mb-3">
					<?php echo Form::checkbox('is_published_date', 1, Input::post('is_published_date', false), array(
						'class' => 'form-check-input',
						'id' => 'is_published_date',
						'form' => 'postForm'
					)); ?>
					<label class="form-check-label" for="is_published_date">
						<i class="bi bi-calendar-check me-1"></i> Lên lịch xuất bản
					</label>
					<div class="form-text">
						<small>Đặt thời gian cụ thể cho việc xuất bản bài viết.</small>
					</div>
				</div>

				<div id="publishDatesBox" style="display:none">
					<div class="mb-3">
						<label class="form-label fw-bold">
						<i class="bi bi-calendar-plus"></i> Ngày bắt đầu xuất bản
						</label>
						<?php echo Form::input('publish_start_date', Input::post('publish_start_date'), array(
						'class' => 'form-control',
						'type' => 'datetime-local',
						'id' => 'publish_start_date',
						'form' => 'postForm'
						)); ?>
					</div>

					<div class="mb-3">
						<div class="d-flex justify-content-between align-items-center">
						<label class="form-label fw-bold mb-0">
							<i class="bi bi-calendar-x"></i> Ngày kết thúc xuất bản
						</label>
						<div class="form-check">
							<input type="checkbox" class="form-check-input" id="no_end_date" form="postForm" checked>
							<label for="no_end_date" class="form-check-label">Không có ngày kết thúc</label>
						</div>
						</div>
						<?php echo Form::input('publish_end_date', Input::post('publish_end_date'), array(
						'class' => 'form-control',
						'type' => 'datetime-local',
						'id' => 'publish_end_date',
						'disabled' => true,
						'form' => 'postForm'
						)); ?>
					</div>
				</div>
				
				<div class="d-grid gap-2">
					<button type="submit" form="postForm" class="btn btn-primary">
						<i class="bi bi-check-circle me-2"></i>Tạo bài viết
					</button>
				</div>
			</div>
		</div>
		
		<!-- Images Upload -->
		<div class="card">
			<div class="card-header">
				<h6 class="mb-0">
					<i class="bi bi-images me-2"></i>Hình ảnh
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
						   form="postForm">
					<button type="button" class="btn btn-outline-primary btn-sm" onclick="document.getElementById('images').click()">
						<i class="bi bi-folder-plus me-1"></i> Chọn file
					</button>
					<div class="form-text mt-2">
						<small>JPG, PNG, GIF. Tối đa 5MB</small>
					</div>
				</div>
				
				<!-- Preview Images -->
				<div class="image-preview mt-3" id="imagePreview" style="display: none;">
					<h6 class="small">Xem trước:</h6>
					<div class="image-gallery" id="imageGallery"></div>
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

.ck-content { 
	height: 300px; 
}
</style>

<!-- Custom JavaScript -->
<script>
document.addEventListener('DOMContentLoaded', function() {
	// Add form id to the form element
	const form = document.querySelector('.needs-validation');
	form.id = 'postForm';
	
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
	
	titleInput.addEventListener('input', function() {
		titleCounter.textContent = this.value.length + '/255 ký tự';
	});
	
	contentInput.addEventListener('input', function() {
		contentCounter.textContent = this.value.length + ' ký tự';
	});
	
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
							<button type="button" class="delete-btn" onclick="removeImage(${index})">
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

function removeImage(index) {
	console.log('Remove image at index:', index);
}

function saveDraft(isAutoSave = false) {
	const form = document.getElementById('postForm');
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
