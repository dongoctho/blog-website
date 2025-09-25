<?php
use Fuel\Core\Uri;
use Fuel\Core\Str;
?>

<div class="row mb-4">
    <div class="col-12 d-flex justify-content-between align-items-center">
        <div>
            <h3 class="mb-1">Thảo luận</h3>
            <p class="text-muted mb-0">Tổng cộng <?php echo $total; ?> chủ đề</p>
        </div>
        <div>
            <?php if (Auth::check()): ?>
            <a href="<?php echo Uri::create('admin/discussion/create'); ?>" target="_blank" class="btn btn-primary">
                <i class="bi bi-plus-circle me-2"></i>Tạo thảo luận
            </a>
            <?php endif; ?>
        </div>
    </div>
</div>

<div class="card">
    <div class="list-group list-group-flush">
        <?php if (!empty($posts)): ?>
            <?php foreach ($posts as $post): ?>
                <a class="list-group-item list-group-item-action py-3" href="<?php echo Uri::create('discussion/view/'.$post->slug); ?>">
                    <div class="d-flex w-100 justify-content-between">
                        <h5 class="mb-1"><?php echo e($post->title); ?></h5>
                        <small class="text-muted"><i class="bi bi-clock"></i> <?php echo date('d/m/Y H:i', strtotime($post->created_at)); ?></small>
                    </div>
                    <p class="mb-1 text-muted"><?php echo e(Str::truncate(strip_tags($post->content), 160)); ?></p>
                    <small class="text-muted d-flex align-items-center gap-3">
                        <span><i class="bi bi-person-circle"></i> <?php echo e($post->user->username ?? 'N/A'); ?></span>
                        <span><i class="bi bi-tag"></i> 
                            <?php 
                            $categories = $post->get_categories();
                            if (!empty($categories)) {
                                echo e($categories[0]->name);
                                if (count($categories) > 1) {
                                    echo ' +' . (count($categories) - 1);
                                }
                            } else {
                                echo 'Không có danh mục';
                            }
                            ?>
                        </span>
                        <span><i class="bi bi-eye"></i> <?php echo number_format($post->views ?? 0); ?> lượt xem</span>
                    </small>
                </a>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="list-group-item text-center py-5">
                <i class="bi bi-chat-right-text" style="font-size: 3rem; color: #dee2e6;"></i>
                <h5 class="text-muted mt-3">Chưa có thảo luận nào</h5>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php if (isset($pagination) && $pagination): ?>
    <div class="mt-3">
        <?php echo htmlspecialchars_decode($pagination); ?>
    </div>
<?php endif; ?>


