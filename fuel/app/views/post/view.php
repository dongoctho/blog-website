<?php
use Fuel\Core\Uri;
// Cấu hình QR donate mặc định (có thể thay bằng config hệ thống)
$donate_qr  = isset($donate_qr) ? $donate_qr : Uri::create('uploads/qrcode.jpg');
$bank_name  = isset($bank_name) ? $bank_name : 'BIDV';
$owner_name = isset($owner_name) ? $owner_name : 'TRAN DUC LUONG';

// Cập nhật Open Graph meta tags cho bài viết
$og_title = $post->title . ' - Blog CMS';
$og_description = mb_substr(strip_tags($post->content), 0, 160) . '...';
$og_image = !empty($post->images) ? reset($post->images)->get_url() : Uri::base() . 'uploads/logo.png';
$og_url = Uri::current();
?>
<!-- Navigation Pills -->
<div class="row mb-4">
	<div class="col-12">
		<ul class="nav nav-pills">
			<li class="nav-item">
				<a class="nav-link" href="<?php echo Uri::create('post'); ?>">
					<i class="bi bi-arrow-left"></i> Quay lại danh sách
				</a>
			</li>
		</ul>
	</div>
</div>

<!-- Post Content -->
<article class="post-article">
	<div class="row">
		<div class="col-lg-8 mx-auto">
			<!-- Post Header -->
			<header class="post-header mb-4">
				<h1 class="display-5 fw-bold mb-3"><?php echo e($post->title); ?></h1>
				
				<div class="post-meta d-flex flex-wrap align-items-center gap-3 mb-4">
					<div class="d-flex align-items-center">
						<i class="bi bi-person-circle text-primary me-2"></i>
						<span><?php echo e($post->user->username ?? 'Anonymous'); ?></span>
					</div>
					
					<div class="d-flex align-items-center">
						<i class="bi bi-calendar-date text-primary me-2"></i>
						<span><?php echo date('d/m/Y H:i', strtotime($post->created_at)); ?></span>
					</div>
					
					<div class="d-flex align-items-center">
						<i class="bi bi-tag text-primary me-2"></i>
						<span class="category-badge">
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
					</div>
					
					<div class="d-flex align-items-center">
						<i class="bi bi-eye text-primary me-2"></i>
						<span><?php echo number_format($post->views ?? 0); ?> lượt xem</span>
					</div>
					
					<?php if ($post->updated_at != $post->created_at): ?>
						<div class="d-flex align-items-center">
							<i class="bi bi-pencil-square text-primary me-2"></i>
							<small class="text-muted">
								Cập nhật: <?php echo date('d/m/Y H:i', strtotime($post->updated_at)); ?>
							</small>
						</div>
					<?php endif; ?>
				</div>
			</header>
			
			<!-- Post Images Gallery -->
			<?php if (!empty($post->images)): ?>
				<div class="post-images mb-4">
					<?php if (count($post->images) == 1): ?>
						<!-- Single Image -->
						<div class="single-image">
							<img src="<?php if ($img = reset($post->images)) echo $img->get_url(); ?>" 
								 alt="<?php echo e($post->title); ?>"
								 class="img-fluid rounded shadow-lg"
								 style="width: 100%; max-height: 400px; object-fit: cover;">
						</div>
					<?php else: ?>
						<!-- Multiple Images Gallery -->
						<div class="image-gallery-view">
							<div class="row g-2">
								<?php 
								$total_images = count($post->images);
								$max_display = 2;
								
								// Chuyển đổi ORM collection thành array để dễ truy cập bằng index
								$images_array = array_values($post->images);
								
								// Hiển thị ảnh đầu tiên
								if ($total_images >= 1): 
									$first_image = $images_array[0];
								?>
									<div class="col-md-6">
										<div class="image-item-view">
											<img src="<?php echo $first_image->get_url(); ?>" 
												 alt="Image 1"
												 class="img-fluid rounded shadow-sm"
												 style="width: 100%; height: 200px; object-fit: cover; cursor: pointer;"
												 data-bs-toggle="modal" 
												 data-bs-target="#imageModal"
												 data-image-src="<?php echo $first_image->get_url(); ?>">
										</div>
									</div>
								<?php endif; ?>
								
								<?php if ($total_images == 2): ?>
									<div class="col-md-6">
										<div class="image-item-view">
											<img src="<?php echo $images_array[1]->get_url(); ?>" 
												 alt="Image 2"
												 class="img-fluid rounded shadow-sm"
												 style="width: 100%; height: 200px; object-fit: cover; cursor: pointer;"
												 data-bs-toggle="modal" 
												 data-bs-target="#imageModal"
												 data-image-src="<?php echo $images_array[1]->get_url(); ?>">
										</div>
									</div>
								<?php elseif ($total_images > 2): ?>
									<div class="col-md-6">
										<div class="more-images d-flex align-items-center justify-content-center rounded bg-light shadow-sm" 
											 style="height: 200px; cursor: pointer;"
											 data-bs-toggle="modal" 
											 data-bs-target="#galleryModal">
											<div class="text-center">
												<i class="bi bi-plus-circle" style="font-size: 2rem; color: #6c757d;"></i>
												<p class="mb-0 mt-2 text-muted">
													+<?php echo $total_images - 1; ?> ảnh khác
												</p>
											</div>
										</div>
									</div>
								<?php endif; ?>
							</div>
						</div>
					<?php endif; ?>
				</div>
			<?php endif; ?>
			
			<!-- Post Content -->
			<div class="post-content">
				<div class="content-text">
					<?php echo htmlspecialchars_decode($post->content); ?>
				</div>
			</div>
			
			<!-- Post Footer Actions -->
			<footer class="post-footer mt-5 pt-4 border-top">
				<div class="d-flex justify-content-between align-items-center">
					<div class="share-buttons">
						<h6 class="mb-2">Chia sẻ bài viết:</h6>
						<div class="d-flex flex-wrap gap-2 align-items-center">
							<!-- Facebook Share Button -->
							<div class="fb-share-button" 
								 data-href="<?php echo Uri::current(); ?>" 
								 data-layout="button_count" 
								 data-size="small">
							</div>
							
							<!-- Copy Link Button -->
							<button class="btn btn-outline-success btn-sm" onclick="copyLink()">
								<i class="bi bi-link-45deg"></i> Copy Link
							</button>
						</div>
					</div>
					
					<div class="post-stats">
						<div class="d-flex align-items-center gap-3">
							<button class="btn btn-outline-danger btn-sm" onclick="toggleLike()">
								<i class="bi bi-heart"></i> <span id="likeCount">0</span>
							</button>
							<span class="text-muted">
								<i class="bi bi-eye"></i> <span id="viewCount"><?php echo $post->views; ?></span> lượt xem
							</span>
						</div>
					</div>
				</div>
			</footer>
		</div>
	</div>
</article>

<!-- Donate fixed sidebar on the right for large screens -->
<div class="donate-fixed d-none d-lg-block">
	<div class="card shadow">
		<div class="card-header bg-white">
			<h6 class="mb-0"><i class="bi bi-qr-code-scan"></i> Ủng hộ tác giả</h6>
		</div>
		<div class="card-body d-flex flex-column align-items-center">
			<div class="ratio ratio-1x1" style="max-width: 260px; width: 100%;">
				<img src="<?php echo $donate_qr; ?>" alt="Donate QR" class="rounded border" style="object-fit: contain;">
			</div>
			<div class="text-center mt-3">
				<div class="fw-semibold"><?php echo e($bank_name); ?></div>
				<div class="text-muted small"><strong><?php echo e($owner_name); ?></strong></div>
			</div>
			<small class="text-muted mt-3 text-center">Quét mã để gửi lời cảm ơn tới tác giả. Xin cảm ơn!</small>
		</div>
	</div>
</div>

<!-- Styles for fixed donate card -->
<style>
.ck-content { 
    height: 300px; 
}

.donate-fixed {
	position: fixed;
	top: 120px;
	right: 320px;
	width: 175px;
	z-index: 1030;
}
@media (max-width: 1199.98px) {
	.donate-fixed { display: none !important; }
}

.comments li {
    margin-bottom: 16px;
    padding-bottom: 16px;
    border-bottom: 1px solid #e4e6ea;
}

.comments li:last-child {
    border-bottom: none;
    margin-bottom: 0;
    padding-bottom: 0;
}

.comments .avatar-circle {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: bold;
    font-size: 16px;
    background: linear-gradient(45deg, #007bff, #0056b3);
}

.comments .comment-header {
    margin-bottom: 8px;
}

.comments .comment-content {
    line-height: 1.4;
    margin-bottom: 8px;
}

.comments .comment-actions {
    display: flex;
    align-items: center;
    gap: 16px;
}

.comments .btn-link {
    text-decoration: none;
    color: #65676b;
    font-size: 13px;
    font-weight: 600;
    padding: 0;
    border: none;
    background: none;
}

.comments .btn-link:hover {
    color: #1877f2;
    text-decoration: none;
}

.comments .btn-link i {
    margin-right: 4px;
}

/* Reaction panel styles */
.reaction-wrapper { position: relative; display: inline-block; }
.reaction-panel { display: none; position: absolute; left: 0; transform: translateY(-110%); background: #fff; padding: 6px 8px; border-radius: 20px; border: 1px solid #e4e6ea; white-space: nowrap; z-index: 2; top: 80px;}
.reaction-panel .btn { margin: 0 2px; }
.reaction-wrapper:hover .reaction-panel { display: inline-block; }
/* Tăng kích thước icon */
.reaction-panel .btn { font-size: 22px; padding: 6px 8px; }
.reaction-panel .btn i { font-size: 22px; }
.reaction-main { font-size: 18px; }
.reaction-main i { font-size: 18px; }
/* Tăng cỡ icon chính trong reaction-wrapper */
.reaction-main [id^="cr-main-"] { font-size: 19px; line-height: 1; display: inline-block; }
/* Màu hồng cho icon Like để đồng nhất theme */
.reaction-panel .btn .bi-hand-thumbs-up-fill { color: #e83e8c; }
.reaction-main .bi-hand-thumbs-up-fill { color: #e83e8c; }
</style>

<!-- Comments Section -->
<div class="comments-section mt-5">
	<div class="row">
		<div class="col-lg-8 mx-auto">
			<div class="card">
				<div class="card-header">
					<h5 class="mb-0">
						<i class="bi bi-chat-dots"></i> 
						Bình luận 
						<span class="badge bg-primary ms-2" id="commentCount">0</span>
					</h5>
				</div>
				<div class="card-body">
					<?php if (Auth::check()): ?>
						<!-- Comment Form -->
						<form id="commentForm" method="POST" action="<?php echo Uri::create('comment/add/' . $post->id); ?>" novalidate>
							<input type="hidden" name="parent_id" id="parent_id" value="0">
							<div class="mb-3">
								<label for="commentContent" class="form-label">
									<i class="bi bi-pencil-square"></i> Viết bình luận của bạn:
								</label>
								<textarea class="form-control" id="commentContent" name="content" rows="5" 
										  placeholder="Chia sẻ suy nghĩ của bạn về bài viết này..." required></textarea>
							</div>
							<div class="d-flex justify-content-between align-items-center">
								<small class="text-muted">
									<i class="bi bi-person-circle"></i> 
									Đăng nhập với tên: <strong><?php echo e(Auth::get('username')); ?></strong>
									<span id="replyingTo" class="ms-2" style="display:none;"></span>
								</small>
								<div>
									<button type="button" class="btn btn-outline-secondary btn-sm me-2" id="cancelReplyBtn" style="display:none;">Hủy trả lời</button>
									<button type="submit" class="btn btn-primary">
										<i class="bi bi-send"></i> Gửi bình luận
									</button>
								</div>
							</div>
						</form>
					<?php else: ?>
						<!-- Login Prompt -->
						<div class="text-center py-4">
							<i class="bi bi-person-circle" style="font-size: 3rem; color: #6c757d; opacity: 0.5;"></i>
							<h6 class="mt-3 text-muted">Đăng nhập để bình luận</h6>
							<p class="text-muted mb-3">Bạn cần đăng nhập để có thể tham gia thảo luận</p>
							<a href="<?php echo Uri::create('login'); ?>" class="btn btn-primary">
								<i class="bi bi-box-arrow-in-right"></i> Đăng nhập
							</a>
						</div>
					<?php endif; ?>
					
					<!-- Comments List -->
					<div id="commentsList" class="mt-4">
						<?php if (!empty($comments)): ?>
							<div class="comments">
								<?php
								// Hàm hiển thị comment cha-con đệ quy
								$renderTree = function($nodes, $level = 0) use (&$renderTree) {
									if (empty($nodes)) return;
									echo '<ul class="list-unstyled" style="margin-left:'.(max(0, $level-0)*20).'px">';
									foreach ($nodes as $node) {
										$c = $node['comment'];
										$hasReplies = !empty($node['children']);
										$replyCount = $hasReplies ? count($node['children']) : 0;
										
                                        ?>
                                        <li class="mb-3">
                                            <div class="d-flex">
                                                <div class="flex-shrink-0 me-3">
                                                    <div class="avatar-circle bg-primary text-white d-flex align-items-center justify-content-center" style="width: 40px; height: 40px; border-radius: 50%; font-weight: bold;">
                                                        <?php echo strtoupper(substr($c->user->username ?? 'A', 0, 1)); ?>
                                                    </div>
                                                </div>
                                                <div class="flex-grow-1">
                                                    <div class="comment-header mb-2">
                                                        <div class="d-flex align-items-center mb-1">
                                                            <strong class="me-2"><?php echo e($c->user->username ?? 'Anonymous'); ?></strong>
                                                            <small class="text-muted"><?php echo date('d/m/Y H:i', strtotime($c->created_at)); ?></small>
                                                        </div>
                                                    </div>
                                                    <div class="comment-content mb-2">
                                                        <?php echo htmlspecialchars_decode($c->content); ?>
                                                    </div>
                                                    <div class="comment-actions">
                                        <?php
                                        // Đếm theo loại & xác định biểu cảm hiện tại của người dùng
                                        $rc_like = 0; $rc_love = 0; $rc_haha = 0; $rc_wow = 0; $rc_sad = 0; $rc_angry = 0; $user_react = '';
                                        if (!empty($c->reactions)) {
                                            foreach ($c->reactions as $r) {
                                                $t = $r->reaction_type ?: 'like';
                                                if ($t === 'like') $rc_like++;
                                                elseif ($t === 'love') $rc_love++;
                                                elseif ($t === 'haha') $rc_haha++;
                                                elseif ($t === 'wow') $rc_wow++;
                                                elseif ($t === 'sad') $rc_sad++;
                                                elseif ($t === 'angry') $rc_angry++;
                                                if (Auth::check() && (int)$r->user_id === (int)Auth::get('id')) { $user_react = $t; }
                                            }
                                        }
                                        $total_react = $rc_like + $rc_love + $rc_haha + $rc_wow + $rc_sad + $rc_angry;
                                        $mainIcon = '<i class="bi bi-hand-thumbs-up-fill"></i>';
                                        if ($user_react === 'love') $mainIcon = '❤️';
                                        elseif ($user_react === 'haha') $mainIcon = '😆';
                                        elseif ($user_react === 'wow') $mainIcon = '😮';
                                        elseif ($user_react === 'sad') $mainIcon = '😢';
                                        elseif ($user_react === 'angry') $mainIcon = '😡';
                                        ?>
                                        <div class="reaction-wrapper" data-id="<?= (int)$c->id ?>">
                                            <button class="btn btn-link btn-sm p-0 me-2 reaction-main" onclick="openReactionPanel(<?= (int)$c->id ?>)" onmouseover="openReactionPanel(<?= (int)$c->id ?>)">
                                                <span id="cr-main-<?= (int)$c->id ?>"><?= $mainIcon ?></span>
                                                <span id="cr-count-<?= (int)$c->id ?>" class="text-muted small"><?php echo $total_react; ?></span>
                                            </button>
                                            <div class="reaction-panel shadow-sm" id="cr-panel-<?= (int)$c->id ?>">
                                                <button class="btn btn-light btn-sm" onclick="reactComment(<?= (int)$c->id ?>, 'like')"><i class="bi bi-hand-thumbs-up-fill"></i></button>
                                                <button class="btn btn-light btn-sm" onclick="reactComment(<?= (int)$c->id ?>, 'love')">❤️</button>
                                                <button class="btn btn-light btn-sm" onclick="reactComment(<?= (int)$c->id ?>, 'haha')">😆</button>
                                                <button class="btn btn-light btn-sm" onclick="reactComment(<?= (int)$c->id ?>, 'wow')">😮</button>
                                                <button class="btn btn-light btn-sm" onclick="reactComment(<?= (int)$c->id ?>, 'sad')">😢</button>
                                                <button class="btn btn-light btn-sm" onclick="reactComment(<?= (int)$c->id ?>, 'angry')">😡</button>
                                            </div>
                                        </div>
                                        <button class="btn btn-link btn-sm p-0" onclick="replyComment(<?= (int)$c->id ?>, '<?= addslashes($c->user->username ?? '') ?>')">Trả lời</button>
                                        <?php if (Auth::check() && Auth::get('id') == $c->user_id): ?>
                                            <button class="btn btn-link btn-sm p-0 text-danger ms-3" onclick="deleteComment(<?= (int)$c->id ?>)">
                                                <i class="bi bi-trash"></i> Xóa
                                            </button>
                                        <?php endif; ?>
                                        </div>
                                        <?php if ($hasReplies): ?>
                                            <div class="mt-2">
                                                <button class="btn btn-link btn-sm p-0 text-primary" onclick="toggleReplies(<?= (int)$c->id ?>)" id="toggle-<?= (int)$c->id ?>">
                                                    <i class="bi bi-chevron-down"></i> Xem <?= $replyCount ?> trả lời
                                                </button>
                                            </div>
                                            <div class="replies-container" id="replies-<?= (int)$c->id ?>" style="display: none; margin-top: 12px;">
                                                <?php $renderTree($node['children'], $level+1); ?>
                                            </div>
                                        <?php endif; ?>
                                        </div>
                                    </div>
                                </li>
                                <?php
                                }
                                echo '</ul>';
                                };
                                $renderTree($comments, 0);
                                ?>
							</div>
						<?php else: ?>
							<div class="text-center py-4">
								<i class="bi bi-chat-dots" style="font-size: 2rem; color: #6c757d; opacity: 0.5;"></i>
								<p class="text-muted mt-2 mb-0">Chưa có bình luận nào. Hãy là người đầu tiên!</p>
							</div>
						<?php endif; ?>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<!-- Image Modal for Single View -->
<div class="modal fade" id="imageModal" tabindex="-1" aria-labelledby="imageModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-lg modal-dialog-centered">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="imageModalLabel">Xem ảnh</h5>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="modal-body text-center">
				<img id="modalImage" src="" alt="" class="img-fluid">
			</div>
		</div>
	</div>
</div>

<!-- Gallery Modal for Multiple Images -->
<div class="modal fade" id="galleryModal" tabindex="-1" aria-labelledby="galleryModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-xl modal-dialog-centered">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="galleryModalLabel">Thư viện ảnh - <?php echo e($post->title); ?></h5>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<div class="modal-body">
				<div class="row g-3">
					<?php if (!empty($post->images)): ?>
						<?php foreach ($post->images as $index => $image): ?>
							<div class="col-lg-4 col-md-6">
								<img src="<?php echo $image->get_url(); ?>" 
									 alt="Image <?php echo $index + 1; ?>"
									 class="img-fluid rounded shadow-sm"
									 style="width: 100%; height: 200px; object-fit: cover;">
							</div>
						<?php endforeach; ?>
					<?php endif; ?>
				</div>
			</div>
		</div>
	</div>
</div>

<!-- CKEditor Script -->
<script src="https://cdn.ckeditor.com/ckeditor5/39.0.1/classic/ckeditor.js"></script>

<!-- Custom JavaScript -->
<script>
document.addEventListener('DOMContentLoaded', function() {
	// Khởi tạo CKEditor cho bình luận
	if (document.getElementById('commentContent')) {
		ClassicEditor
			.create(document.querySelector('#commentContent'), {
				toolbar: {
					items: [
						'heading', '|',
						'bold', 'italic', '|',
						'bulletedList', 'numberedList', '|',
						'link', 'blockQuote', '|',
						'undo', 'redo'
					]
				},
				language: 'vi',
				placeholder: 'Chia sẻ suy nghĩ của bạn về bài viết này...'
			})
			.then(editor => {
				window.commentEditor = editor;
			})
			.catch(error => {
				console.error('Lỗi khởi tạo CKEditor:', error);
			});
	}
	
	// Cập nhật Open Graph meta tags cho Facebook sharing
	updateOpenGraphTags();
	
	function updateOpenGraphTags() {
		const ogTitle = document.querySelector('meta[property="og:title"]');
		const ogDescription = document.querySelector('meta[property="og:description"]');
		const ogImage = document.querySelector('meta[property="og:image"]');
		const ogUrl = document.querySelector('meta[property="og:url"]');
		
		if (ogTitle) ogTitle.setAttribute('content', '<?php echo addslashes($og_title); ?>');
		if (ogDescription) ogDescription.setAttribute('content', '<?php echo addslashes($og_description); ?>');
		if (ogImage) ogImage.setAttribute('content', '<?php echo addslashes($og_image); ?>');
		if (ogUrl) ogUrl.setAttribute('content', '<?php echo addslashes($og_url); ?>');
	}
	// Image modal handling
	const imageModal = document.getElementById('imageModal');
	const modalImage = document.getElementById('modalImage');
	
	imageModal.addEventListener('show.bs.modal', function(event) {
		const button = event.relatedTarget;
		const imageSrc = button.getAttribute('data-image-src');
		modalImage.src = imageSrc;
	});
});

function copyLink() {
	navigator.clipboard.writeText(window.location.href).then(function() {
		// Show success message
		const btn = event.target.closest('button');
		const originalText = btn.innerHTML;
		btn.innerHTML = '<i class="bi bi-check"></i> Đã copy!';
		btn.classList.remove('btn-outline-success');
		btn.classList.add('btn-success');
		
		setTimeout(function() {
			btn.innerHTML = originalText;
			btn.classList.remove('btn-success');
			btn.classList.add('btn-outline-success');
		}, 2000);
	});
}

// Reaction hover/open/close helpers
const openPanels = new Set();
function openReactionPanel(id) {
    const panel = document.getElementById('cr-panel-' + id);
    if (!panel) return;
    panel.style.display = 'inline-block';
    openPanels.add(id);
}
function closeReactionPanel(id) {
    const panel = document.getElementById('cr-panel-' + id);
    if (!panel) return;
    panel.style.display = 'none';
    openPanels.delete(id);
}
document.addEventListener('mouseover', function(e){
    const wrap = e.target.closest('.reaction-wrapper');
    if (!wrap) {
        Array.from(openPanels).forEach(closeReactionPanel);
    }
});

function toggleLike() {
	const likeBtn = event.target.closest('button');
	const likeCount = document.getElementById('likeCount');
	const icon = likeBtn.querySelector('i');
	
	if (icon.classList.contains('bi-heart')) {
		icon.classList.remove('bi-heart');
		icon.classList.add('bi-heart-fill');
		likeBtn.classList.remove('btn-outline-danger');
		likeBtn.classList.add('btn-danger');
		likeCount.textContent = parseInt(likeCount.textContent) + 1;
	} else {
		icon.classList.remove('bi-heart-fill');
		icon.classList.add('bi-heart');
		likeBtn.classList.remove('btn-danger');
		likeBtn.classList.add('btn-outline-danger');
		likeCount.textContent = parseInt(likeCount.textContent) - 1;
	}
}

// Comment functionality
document.addEventListener('DOMContentLoaded', function() {
	// Update comment count
	const commentCount = document.getElementById('commentCount');
	const commentsList = document.getElementById('commentsList');
	const commentItems = commentsList.querySelectorAll('.comment-item');
	commentCount.textContent = commentItems.length;
	
	// Comment form handling
	const commentForm = document.getElementById('commentForm');
	if (commentForm) {
		console.log('Comment form found:', commentForm);
		commentForm.addEventListener('submit', function(e) {
			console.log('Form submit event triggered');
			e.preventDefault();
			
			// Lấy nội dung từ CKEditor
			let content = '';
			if (window.commentEditor) {
				content = window.commentEditor.getData().trim();
				// Cập nhật giá trị textarea với nội dung từ CKEditor
				document.getElementById('commentContent').value = content;
			} else {
				content = document.getElementById('commentContent').value.trim();
			}
			
			console.log('Content:', content);
			if (!content) {
				alert('Vui lòng nhập nội dung bình luận!');
				return;
			}
			
			// Show loading state
			const submitBtn = commentForm.querySelector('button[type="submit"]');
			const originalText = submitBtn.innerHTML;
			submitBtn.innerHTML = '<i class="bi bi-hourglass-split"></i> Đang gửi...';
			submitBtn.disabled = true;
			
			// Submit form
			const formData = new FormData(commentForm);
			console.log('Form action:', commentForm.action);
			console.log('Form data:', formData);
			
			fetch(commentForm.action, {
				method: 'POST',
				body: formData,
				headers: {
					'X-Requested-With': 'XMLHttpRequest'
				}
			})
			.then(response => {
				console.log('Response status:', response.status);
				return response.json();
			})
			.then(data => {
				console.log('Response data:', data);
				if (data.success) {
					// Reload page to show new comment
					window.location.reload();
				} else {
					alert('Có lỗi xảy ra: ' + (data.message || 'Không thể gửi bình luận'));
					submitBtn.innerHTML = originalText;
					submitBtn.disabled = false;
				}
			})
			.catch(error => {
				console.error('Error:', error);
				alert('Có lỗi xảy ra khi gửi bình luận');
				submitBtn.innerHTML = originalText;
				submitBtn.disabled = false;
			});
		});
	} else {
		console.error('Comment form not found!');
	}
	
	// Cancel reply functionality
	const cancelBtn = document.getElementById('cancelReplyBtn');
	if (cancelBtn) {
		cancelBtn.addEventListener('click', function() {
			const parentInput = document.getElementById('parent_id');
			const replyingTo = document.getElementById('replyingTo');
			
			parentInput.value = 0;
			replyingTo.style.display = 'none';
			cancelBtn.style.display = 'none';
		});
	}
});

function deleteComment(commentId) {
	if (confirm('Bạn có chắc chắn muốn xóa bình luận này?')) {
		fetch('<?php echo Uri::create('comment/delete/'); ?>' + commentId, {
			method: 'POST',
			headers: {
				'X-Requested-With': 'XMLHttpRequest'
			}
		})
		.then(response => response.json())
		.then(data => {
			if (data.success) {
				window.location.reload();
			} else {
				alert('Có lỗi xảy ra: ' + (data.message || 'Không thể xóa bình luận'));
			}
		})
		.catch(error => {
			console.error('Error:', error);
			alert('Có lỗi xảy ra khi xóa bình luận');
		});
	}
}

// Gửi biểu cảm cho comment
async function reactComment(commentId, type) {
    try {
        const formData = new FormData();
        formData.append('reaction_type', type || 'like');
        const res = await fetch('<?php echo Uri::create('comment/react/'); ?>' + commentId, {
            method: 'POST',
            headers: { 'X-Requested-With': 'XMLHttpRequest' },
            body: formData
        });
        const data = await res.json();
        if (data.success) {
            const el = document.getElementById('cr-count-' + commentId);
            if (el) el.textContent = data.count;
            const main = document.getElementById('cr-main-' + commentId);
            if (main) {
                let icon = '<i class="bi bi-hand-thumbs-up"></i>';
                if (type === 'love') icon = '❤️';
                else if (type === 'haha') icon = '😆';
                else if (type === 'wow') icon = '😮';
                else if (type === 'sad') icon = '😢';
                else if (type === 'angry') icon = '😡';
                main.innerHTML = icon;
            }
        } else {
            alert(data.message || 'Không thể cập nhật biểu cảm');
        }
    } catch (e) {
        console.error(e);
        alert('Lỗi kết nối máy chủ');
    }
}

// Hàm reply comment
function replyComment(commentId, username) {
	const parentInput = document.getElementById('parent_id');
	const replyingTo = document.getElementById('replyingTo');
	const cancelBtn = document.getElementById('cancelReplyBtn');
	const content = document.getElementById('commentContent');
	
	// Set parent_id
	parentInput.value = commentId;
	replyingTo.style.display = '';
	replyingTo.textContent = '(Trả lời ' + username + ')';
	cancelBtn.style.display = '';
	
	// Focus vào CKEditor
	if (window.commentEditor) {
		window.commentEditor.editing.view.focus();
	} else {
		content.focus();
	}
	
	// Thêm @username vào nội dung nếu chưa có
	if (username) {
		const currentContent = window.commentEditor ? window.commentEditor.getData() : content.value;
		if (!currentContent.startsWith('@'+username)) {
			const newContent = '@' + username + ' ' + currentContent;
			if (window.commentEditor) {
				window.commentEditor.setData(newContent);
			} else {
				content.value = newContent;
			}
		}
	}
}

// Hàm toggle hiển thị replies
function toggleReplies(commentId) {
	const repliesContainer = document.getElementById('replies-' + commentId);
	const toggleButton = document.getElementById('toggle-' + commentId);
	const icon = toggleButton.querySelector('i');
	
	if (repliesContainer.style.display === 'none') {
		repliesContainer.style.display = 'block';
		icon.className = 'bi bi-chevron-up';
		toggleButton.innerHTML = '<i class="bi bi-chevron-up"></i> Ẩn trả lời';
	} else {
		repliesContainer.style.display = 'none';
		icon.className = 'bi bi-chevron-down';
		toggleButton.innerHTML = '<i class="bi bi-chevron-down"></i> Xem trả lời';
	}
}
</script>