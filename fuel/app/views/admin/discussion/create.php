<?php
use Fuel\Core\Arr;
use Fuel\Core\Uri;
use Fuel\Core\Form;
use Fuel\Core\Input;
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h5 class="mb-1">Tạo thảo luận mới</h5>
        <p class="text-muted mb-0">Viết và xuất bản thảo luận</p>
    </div>
    <div>
        <a href="<?php echo Uri::base(); ?>admin/discussion" class="btn btn-secondary">
            <i class="bi bi-arrow-left me-2"></i>Quay lại danh sách
        </a>
    </div>
</div>

<div class="row">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">
                <h6 class="mb-0">
                    <i class="bi bi-pencil-square me-2"></i>Nội dung thảo luận
                </h6>
            </div>
            
            <div class="card-body">
                <?php echo Form::open(array('action' => Uri::base() . 'admin/discussion/create', 'method' => 'post', 'enctype' => 'multipart/form-data', 'class' => 'needs-validation', 'novalidate' => true)); ?>
                    <?php echo Form::hidden('submit_form', '1'); ?>

                    <div class="mb-3">
                        <label for="title" class="form-label fw-bold">
                            <i class="bi bi-card-heading"></i> Tiêu đề thảo luận
                            <span class="text-danger">*</span>
                        </label>
                        <?php echo Form::input('title', Input::post('title'), array(
                            'class' => 'form-control',
                            'id' => 'title',
                            'placeholder' => 'Nhập tiêu đề thảo luận...',
                            'required' => true,
                            'maxlength' => 255
                        )); ?>
                        <div class="invalid-feedback">Vui lòng nhập tiêu đề (tối thiểu 5 ký tự).</div>
                        <div class="form-text"><small id="titleCounter">0/255 ký tự</small></div>
                    </div>

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
                        <div class="invalid-feedback">Vui lòng chọn ít nhất một danh mục.</div>
                        <div class="form-text">
                            <small>Giữ Ctrl (hoặc Cmd trên Mac) để chọn nhiều danh mục.</small>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="content" class="form-label fw-bold">
                            <i class="bi bi-file-text"></i> Nội dung thảo luận
                            <span class="text-danger">*</span>
                        </label>
                        <?php echo Form::textarea('content', Input::post('content'), array(
                            'class' => 'form-control',
                            'id' => 'editor',
                            'rows' => 15,
                            'placeholder' => 'Viết nội dung thảo luận...',
                            'required' => true
                        )); ?>
                        <div class="form-text"><small id="contentCounter">0 ký tự</small></div>
                    </div>
                <?php echo Form::close(); ?>
            </div>
        </div>
    </div>
    
    <div class="col-lg-4">
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
                    <input type="file" name="images[]" id="images" class="form-control d-none" multiple accept="image/*" form="discussionForm">
                    <button type="button" class="btn btn-outline-primary btn-sm" onclick="document.getElementById('images').click()">
                        <i class="bi bi-folder-plus me-1"></i> Chọn file
                    </button>
                    <div class="form-text mt-2"><small>JPG, PNG, GIF. Tối đa 5MB</small></div>
                </div>
                <div class="image-preview mt-3" id="imagePreview" style="display: none;">
                    <h6 class="small">Xem trước:</h6>
                    <div class="image-gallery" id="imageGallery"></div>
                </div>
            </div>
        </div>

        <div class="d-grid gap-2 mt-3">
            <button type="submit" form="discussionForm" class="btn btn-primary">
                <i class="bi bi-check-circle me-2"></i>Tạo thảo luận
            </button>
        </div>
    </div>
</div>

<style>
.image-gallery { display: grid; grid-template-columns: repeat(auto-fill, minmax(80px, 1fr)); gap: 10px; }
.image-item { position: relative; border-radius: 8px; overflow: hidden; aspect-ratio: 1; }
.image-item img { width: 100%; height: 100%; object-fit: cover; }
.image-item .delete-btn { position: absolute; top: 5px; right: 5px; background: rgba(220,53,69,.8); color: #fff; border: none; border-radius: 50%; width:24px; height:24px; font-size:12px; cursor:pointer; display:flex; align-items:center; justify-content:center; }
.upload-area:hover { background-color:#f8f9fa; }
.ck-content { height: 300px; }
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.querySelector('.needs-validation');
    form.id = 'discussionForm';

    const titleInput = document.getElementById('title');
    const contentInput = document.getElementById('editor');
    const titleCounter = document.getElementById('titleCounter');
    const contentCounter = document.getElementById('contentCounter');

    titleInput.addEventListener('input', function() { titleCounter.textContent = this.value.length + '/255 ký tự'; });
    contentInput.addEventListener('input', function() { contentCounter.textContent = this.value.length + ' ký tự'; });

    const uploadArea = document.getElementById('uploadArea');
    const imageInput = document.getElementById('images');
    const imagePreview = document.getElementById('imagePreview');
    const imageGallery = document.getElementById('imageGallery');

    uploadArea.addEventListener('dragover', function(e) { e.preventDefault(); this.style.borderColor = '#667eea'; this.style.backgroundColor = '#f8f9fa'; });
    uploadArea.addEventListener('dragleave', function(e) { e.preventDefault(); this.style.borderColor = ''; this.style.backgroundColor = ''; });
    uploadArea.addEventListener('drop', function(e) { e.preventDefault(); this.style.borderColor = ''; this.style.backgroundColor = ''; handleFiles(e.dataTransfer.files); });
    imageInput.addEventListener('change', function() { handleFiles(this.files); });

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

    form.addEventListener('submit', function(e) {
        if (!form.checkValidity()) { e.preventDefault(); e.stopPropagation(); }
        form.classList.add('was-validated');
    });
});

function removeImage(index) { console.log('Remove image at index:', index); }

CKEDITOR.ClassicEditor.create(document.getElementById("editor"), { removePlugins: ['AIAssistant','CKBox','CKFinder','EasyImage','MultiLevelList','RealTimeCollaborativeComments','RealTimeCollaborativeTrackChanges','RealTimeCollaborativeRevisionHistory','PresenceList','Comments','TrackChanges','TrackChangesData','RevisionHistory','Pagination','WProofreader','MathType','SlashCommand','Template','DocumentOutline','FormatPainter','TableOfContents','PasteFromOfficeEnhanced','CaseChange'] });
</script>


