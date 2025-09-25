<?php
use Fuel\Core\Uri;
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h5 class="mb-1">Chi tiết thảo luận</h5>
        <p class="text-muted mb-0">Xem thông tin thảo luận</p>
    </div>
    <div>
        <a href="<?php echo Uri::base(); ?>admin/discussion" class="btn btn-secondary me-2"><i class="bi bi-arrow-left me-2"></i>Quay lại danh sách</a>
        <a href="<?php echo Uri::base(); ?>admin/discussion/edit/<?php echo $post->id; ?>" class="btn btn-primary"><i class="bi bi-pencil me-2"></i>Chỉnh sửa</a>
    </div>
</div>

<div class="row">
    <div class="col-lg-8">
        <div class="card mb-4">
            <div class="card-header"><h6 class="mb-0"><i class="bi bi-file-text me-2"></i>Nội dung</h6></div>
            <div class="card-body">
                <h2 class="text-primary mb-3"><?php echo $post->title; ?></h2>
                <div class="post-meta mb-4">
                    <div class="row">
                        <div class="col-md-6">
                            <p class="mb-1"><i class="bi bi-person-circle text-muted"></i> <strong>Tác giả:</strong> <span class="text-primary"><?php echo isset($post->user) ? $post->user->username : 'N/A'; ?></span></p>
                            <p class="mb-1"><i class="bi bi-tags text-muted"></i> <strong>Danh mục:</strong> 
                                <?php 
                                $categories = $post->get_categories();
                                if (!empty($categories)) {
                                    foreach ($categories as $category) {
                                        echo '<span class="badge bg-primary me-1">' . e($category->name) . '</span>';
                                    }
                                } else {
                                    echo '<span class="badge bg-secondary">N/A</span>';
                                }
                                ?>
                            </p>
                        </div>
                        <div class="col-md-6">
                            <p class="mb-1"><i class="bi bi-calendar text-muted"></i> <strong>Ngày tạo:</strong> <?php echo date('d/m/Y H:i', strtotime($post->created_at)); ?></p>
                            <p class="mb-1"><i class="bi bi-clock text-muted"></i> <strong>Cập nhật:</strong> <?php echo date('d/m/Y H:i', strtotime($post->updated_at)); ?></p>
                            <p class="mb-1"><i class="bi bi-eye text-muted"></i> <strong>Số lượt xem:</strong> <span class="badge bg-info"><?php echo number_format($post->views ?? 0); ?></span></p>
                        </div>
                    </div>
                </div>

                <div class="post-body">
                    <h6 class="mb-3"><i class="bi bi-file-earmark-text"></i> Nội dung:</h6>
                    <div class="content-preview border rounded p-3 bg-light">
                        <?php echo htmlspecialchars_decode($post->content); ?>
                    </div>
                </div>
            </div>
        </div>

        <?php if (isset($post->images) && count($post->images) > 0): ?>
        <div class="card">
            <div class="card-header"><h6 class="mb-0"><i class="bi bi-images me-2"></i>Hình ảnh đính kèm <span class="badge bg-secondary ms-2"><?php echo count($post->images); ?></span></h6></div>
            <div class="card-body">
                <div class="row g-3">
                    <?php foreach ($post->images as $image): ?>
                    <div class="col-md-4 col-sm-6">
                        <div class="image-item">
                            <img src="<?php echo Uri::base(); ?>uploads/<?php echo $image->file_path; ?>" class="img-fluid rounded" alt="Image">
                            <div class="image-info mt-2">
                                <small class="text-muted d-block"><i class="bi bi-file-earmark"></i> <?php echo $image->file_path; ?></small>
                                <small class="text-muted"><i class="bi bi-calendar-event"></i> <?php echo date('d/m/Y H:i', strtotime($image->uploaded_at)); ?></small>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
        <?php endif; ?>
    </div>

    <div class="col-lg-4">
        <div class="card">
            <div class="card-header"><h6 class="mb-0"><i class="bi bi-info-circle me-2"></i>Thông tin</h6></div>
            <div class="card-body">
                <p class="mb-2"><strong>ID:</strong> <code>#<?php echo $post->id; ?></code></p>
                <p class="mb-2"><strong>Slug:</strong> <code><?php echo $post->slug; ?></code></p>
                <p class="mb-0"><strong>Trạng thái:</strong> <span class="badge bg-success">Đã xuất bản</span></p>
            </div>
        </div>

        <div class="card mt-3">
            <div class="card-header"><h6 class="mb-0"><i class="bi bi-lightning me-2"></i>Thao tác nhanh</h6></div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <a href="<?php echo Uri::base(); ?>admin/discussion/edit/<?php echo $post->id; ?>" class="btn btn-primary"><i class="bi bi-pencil me-2"></i>Chỉnh sửa</a>
                    <a href="<?php echo Uri::base(); ?>admin/discussion/delete/<?php echo $post->id; ?>" class="btn btn-outline-danger btn-delete"><i class="bi bi-trash me-2"></i>Xóa thảo luận</a>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.content-preview { max-height: 400px; overflow-y: auto; font-size: .95rem; line-height:1.6; }
.image-item img { aspect-ratio: 16/9; object-fit: cover; width: 100%; }
</style>


