<?php
use Fuel\Core\Uri;
use Fuel\Core\Form;
use Fuel\Core\Input;
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h5 class="mb-1">Chỉnh sửa thảo luận</h5>
        <p class="text-muted mb-0">Cập nhật: <strong><?php echo e($post->title); ?></strong></p>
    </div>
    <div>
        <a href="<?php echo Uri::base(); ?>admin/discussion" class="btn btn-secondary me-2">
            <i class="bi bi-arrow-left me-2"></i>Quay lại danh sách
        </a>
        <a href="<?php echo Uri::base(); ?>admin/discussion/view/<?php echo $post->id; ?>" class="btn btn-outline-info">
            <i class="bi bi-eye me-2"></i>Xem chi tiết
        </a>
    </div>
    </div>

<div class="row">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">
                <h6 class="mb-0"><i class="bi bi-pencil-square me-2"></i>Chỉnh sửa nội dung</h6>
            </div>
            <div class="card-body">
                <?php echo Form::open(array('action' => Uri::base() . 'admin/discussion/edit/' . $post->id, 'method' => 'post', 'enctype' => 'multipart/form-data', 'class' => 'needs-validation', 'novalidate' => true)); ?>
                    <?php echo Form::hidden('submit_form', '1'); ?>

                    <div class="mb-3">
                        <label for="title" class="form-label fw-bold"><i class="bi bi-card-heading"></i> Tiêu đề <span class="text-danger">*</span></label>
                        <?php echo Form::input('title', Input::post('title', $post->title), array('class' => 'form-control','id' => 'title','placeholder' => 'Nhập tiêu đề...','required' => true,'maxlength' => 255)); ?>
                        <div class="invalid-feedback">Vui lòng nhập tiêu đề.</div>
                        <div class="form-text"><small id="titleCounter">0/255 ký tự</small></div>
                    </div>

                    <div class="mb-3">
                        <label for="category_ids" class="form-label fw-bold"><i class="bi bi-tags"></i> Danh mục <span class="text-danger">*</span></label>
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
                        <div class="invalid-feedback">Vui lòng chọn ít nhất một danh mục.</div>
                        <div class="form-text">
                            <small>Giữ Ctrl (hoặc Cmd trên Mac) để chọn nhiều danh mục.</small>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="content" class="form-label fw-bold"><i class="bi bi-file-text"></i> Nội dung <span class="text-danger">*</span></label>
                        <?php echo Form::textarea('content', Input::post('content', $post->content), array('class' => 'form-control','id' => 'editor','rows' => 15,'placeholder' => 'Nội dung...','required' => true)); ?>
                        <div class="invalid-feedback">Vui lòng nhập nội dung.</div>
                        <div class="form-text"><small id="contentCounter">0 ký tự</small></div>
                    </div>
                <?php echo Form::close(); ?>
            </div>
        </div>

        <?php if (isset($post->images) && count($post->images) > 0): ?>
        <div class="card mt-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h6 class="mb-0"><i class="bi bi-images me-2"></i>Quản lý hình ảnh <span class="badge bg-secondary ms-2"><?php echo count($post->images); ?></span></h6>
                <div class="image-controls">
                    <button type="button" class="btn btn-outline-primary btn-sm" onclick="selectAllImages()"><i class="bi bi-check-square me-1"></i>Chọn tất cả</button>
                    <button type="button" class="btn btn-outline-secondary btn-sm" onclick="deselectAllImages()"><i class="bi bi-square me-1"></i>Bỏ chọn</button>
                    <button type="button" class="btn btn-outline-danger btn-sm" onclick="deleteSelectedImages()" id="deleteSelectedBtn" disabled><i class="bi bi-trash me-1"></i>Xóa đã chọn (<span id="selectedCount">0</span>)</button>
                </div>
            </div>
            <div class="card-body">
                <div class="current-images">
                    <div class="row g-3">
                        <?php foreach ($post->images as $image): ?>
                        <div class="col-md-4 col-sm-6">
                            <div class="image-item position-relative" data-image-id="<?php echo $image->id; ?>">
                                <div class="image-checkbox">
                                    <input type="checkbox" class="form-check-input image-select-checkbox" id="image_<?php echo $image->id; ?>" value="<?php echo $image->id; ?>" onchange="updateSelectedCount()">
                                    <label for="image_<?php echo $image->id; ?>" class="checkbox-overlay"></label>
                                </div>
                                <img src="<?php echo Uri::base(); ?>uploads/<?php echo $image->file_path; ?>" alt="Current Image" class="img-fluid rounded image-thumbnail" onclick="toggleImageSelection(<?php echo $image->id; ?>)">
                                <div class="image-info mt-2">
                                    <small class="text-muted d-block"><i class="bi bi-file-earmark"></i> <?php echo $image->file_path; ?></small>
                                    <small class="text-muted">ID: <?php echo $image->id; ?></small>
                                </div>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>

                <div class="bulk-delete-info mt-3 p-3 bg-light rounded" id="bulkDeleteInfo" style="display: none;">
                    <div class="d-flex align-items-center justify-content-between">
                        <div><i class="bi bi-info-circle text-primary me-2"></i><span id="bulkDeleteText">Đã chọn 0 ảnh để xóa</span></div>
                        <div>
                            <button type="button" class="btn btn-sm btn-danger" onclick="confirmBulkDelete()"><i class="bi bi-trash me-1"></i>Xác nhận xóa</button>
                            <button type="button" class="btn btn-sm btn-secondary" onclick="cancelBulkDelete()">Hủy</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php endif; ?>
    </div>

    <div class="col-lg-4">
        <div class="card mb-3">
            <div class="card-body">
                <div class="mb-3">
                    <label for="views" class="form-label fw-bold"><i class="bi bi-eye"></i> Số lượt xem</label>
                    <?php echo Form::input('views', Input::post('views', $post->views), array('class' => 'form-control','id' => 'views','type' => 'number','min' => '0','placeholder' => 'Nhập số lượt xem...','form' => 'editForm')); ?>
                    <div class="form-text"><small>Để trống để giữ nguyên số lượt xem.</small></div>
                </div>

                <div class="d-grid gap-2">
                    <button type="submit" form="editForm" class="btn btn-primary"><i class="bi bi-check-circle me-2"></i>Cập nhật thảo luận</button>
                </div>
            </div>
        </div>

        <div class="card mt-3">
            <div class="card-header"><h6 class="mb-0"><i class="bi bi-info-circle me-2"></i>Thông tin</h6></div>
            <div class="card-body">
                <div class="info-details">
                    <p class="mb-2"><strong>ID:</strong> <code>#<?php echo $post->id; ?></code></p>
                    <p class="mb-2"><strong>Slug:</strong> <code><?php echo $post->slug; ?></code></p>
                    <p class="mb-0"><strong>Hình ảnh:</strong> <span class="badge bg-info"><?php echo isset($post->images) ? count($post->images) : 0; ?> ảnh</span></p>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.image-gallery { display: grid; grid-template-columns: repeat(auto-fill, minmax(80px, 1fr)); gap: 10px; }
.image-item { position: relative; border-radius: 8px; overflow: hidden; aspect-ratio: 1; }
.image-item img { width: 100%; height: 100%; object-fit: cover; }
.image-thumbnail { cursor:pointer; transition: all .3s ease; }
.image-item.selected { border:3px solid #667eea; box-shadow: 0 4px 15px rgba(102,126,234,.3); transform: translateY(-2px); }
.image-checkbox { position:absolute; top:10px; left:10px; z-index:10; }
.checkbox-overlay { position:absolute; top:-5px; left:-5px; width:30px; height:30px; cursor:pointer; }
.bulk-delete-info { border:1px solid #dee2e6; }
.ck-content { height: 300px; }
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.querySelector('.needs-validation');
    form.id = 'editForm';

    const titleInput = document.getElementById('title');
    const contentInput = document.getElementById('editor');
    const titleCounter = document.getElementById('titleCounter');
    const contentCounter = document.getElementById('contentCounter');
    const uploadArea = document.getElementById('uploadArea');
    const imageInput = document.getElementById('images');
    const imagePreview = document.getElementById('imagePreview');
    const imageGallery = document.getElementById('imageGallery');

    function updateTitleCounter() { titleCounter.textContent = titleInput.value.length + '/255 ký tự'; }
    function updateContentCounter() { contentCounter.textContent = contentInput.value.length + ' ký tự'; }
    updateTitleCounter(); updateContentCounter();
    titleInput.addEventListener('input', updateTitleCounter);
    contentInput.addEventListener('input', updateContentCounter);

    if (uploadArea) {
        uploadArea.addEventListener('dragover', function(e){ e.preventDefault(); this.style.borderColor='#667eea'; this.style.backgroundColor='#f8f9fa'; });
        uploadArea.addEventListener('dragleave', function(e){ e.preventDefault(); this.style.borderColor=''; this.style.backgroundColor=''; });
        uploadArea.addEventListener('drop', function(e){ e.preventDefault(); this.style.borderColor=''; this.style.backgroundColor=''; const files=e.dataTransfer.files; imageInput.files=files; handleFiles(files); });
    }
    if (imageInput) { imageInput.addEventListener('change', function(){ handleFiles(this.files); }); }

    function handleFiles(files){
        if (!imageGallery || !imagePreview) return;
        imageGallery.innerHTML='';
        if (files.length>0){
            imagePreview.style.display='block';
            Array.from(files).forEach(function(file, index){
                if (file.type.startsWith('image/')){
                    const reader=new FileReader();
                    reader.onload=function(e){
                        const imageItem=document.createElement('div');
                        imageItem.className='image-item';
                        imageItem.innerHTML=`<img src="${e.target.result}" alt="Preview"><button type="button" class="delete-btn" onclick="removeNewImage(${index})"><i class=\"bi bi-x\"></i></button>`;
                        imageGallery.appendChild(imageItem);
                    };
                    reader.readAsDataURL(file);
                }
            });
        } else {
            imagePreview.style.display='none';
        }
    }

    form.addEventListener('submit', function(e){ if(!form.checkValidity()){ e.preventDefault(); e.stopPropagation(); } form.classList.add('was-validated'); });
});

function removeNewImage(index){ document.getElementById('images').value=''; document.getElementById('imagePreview').style.display='none'; document.getElementById('imageGallery').innerHTML=''; }
function selectAllImages(){ document.querySelectorAll('.image-select-checkbox').forEach(cb=>{ cb.checked=true; cb.closest('.image-item').classList.add('selected'); }); updateSelectedCount(); }
function deselectAllImages(){ document.querySelectorAll('.image-select-checkbox').forEach(cb=>{ cb.checked=false; cb.closest('.image-item').classList.remove('selected'); }); updateSelectedCount(); }
function toggleImageSelection(id){ const cb=document.getElementById(`image_${id}`); cb.checked=!cb.checked; const item=cb.closest('.image-item'); cb.checked?item.classList.add('selected'):item.classList.remove('selected'); updateSelectedCount(); }
function updateSelectedCount(){ const count=document.querySelectorAll('.image-select-checkbox:checked').length; document.getElementById('selectedCount').textContent=count; document.getElementById('deleteSelectedBtn').disabled=(count===0); const info=document.getElementById('bulkDeleteInfo'); if (count>0){ info.style.display='block'; document.getElementById('bulkDeleteText').textContent=`Đã chọn ${count} ảnh để xóa`; } else { info.style.display='none'; } }
function deleteSelectedImages(){ const selected=document.querySelectorAll('.image-select-checkbox:checked'); if(selected.length===0){ alert('Vui lòng chọn ít nhất một ảnh để xóa.'); return; } const form=document.getElementById('editForm'); selected.forEach(cb=>{ const hidden=document.createElement('input'); hidden.type='hidden'; hidden.name='delete_images[]'; hidden.value=cb.value; form.appendChild(hidden); const item=cb.closest('.image-item'); item.style.opacity='0.3'; item.style.pointerEvents='none'; }); alert(`Đã đánh dấu ${selected.length} ảnh để xóa. Nhấn "Cập nhật thảo luận" để hoàn tất.`); }
function confirmBulkDelete(){ deleteSelectedImages(); }
function cancelBulkDelete(){ deselectAllImages(); }

CKEDITOR.ClassicEditor.create(document.getElementById("editor"), { removePlugins: ['AIAssistant','CKBox','CKFinder','EasyImage','MultiLevelList','RealTimeCollaborativeComments','RealTimeCollaborativeTrackChanges','RealTimeCollaborativeRevisionHistory','PresenceList','Comments','TrackChanges','TrackChangesData','RevisionHistory','Pagination','WProofreader','MathType','SlashCommand','Template','DocumentOutline','FormatPainter','TableOfContents','PasteFromOfficeEnhanced','CaseChange'] });
</script>


