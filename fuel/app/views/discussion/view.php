<?php
use Fuel\Core\Uri;
use Fuel\Core\Input;
?>

<div class="row mb-4">
    <div class="col-12">
        <a class="btn btn-light btn-sm" href="<?php echo Uri::create('discussion'); ?>"><i class="bi bi-arrow-left"></i> Quay lại danh sách</a>
    </div>
</div>

<div class="row">
    <div class="col-lg-8 mx-auto">
        <div class="card mb-4">
            <div class="card-body">
                <h2 class="mb-3"><?php echo $post->title; ?></h2>
                <div class="text-muted mb-3 d-flex gap-3 flex-wrap">
                    <span><i class="bi bi-person-circle"></i> <?php echo e($post->user->username ?? 'N/A'); ?></span>
                    <span><i class="bi bi-calendar"></i> <?php echo date('d/m/Y H:i', strtotime($post->created_at)); ?></span>
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
                </div>
                <div class="content border rounded p-3 bg-light">
                    <?php echo htmlspecialchars_decode($post->content); ?>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0"><i class="bi bi-chat-dots"></i> Bình luận</h5>
                <span class="badge bg-primary"><?php echo is_array($comments) ? count($comments) : 0; ?> gốc</span>
            </div>
            <div class="card-body">
                <?php if (Auth::check()): ?>
                <form id="commentForm" method="POST" action="<?php echo Uri::create('comment/add/' . $post->id); ?>" class="mb-4" novalidate>
                    <input type="hidden" name="parent_id" id="parent_id" value="0">
                    <div class="mb-3">
                        <label for="content" class="form-label"><i class="bi bi-pencil-square"></i> Viết bình luận</label>
                        <textarea class="form-control" id="content" name="content" rows="5" placeholder="Nhập nội dung, hỗ trợ @tag người dùng..." required></textarea>
                    </div>
                    <div class="d-flex justify-content-between align-items-center">
                        <small class="text-muted">
                            <i class="bi bi-person-circle"></i>
                            Đăng nhập: <strong><?php echo e(Auth::get('username')); ?></strong>
                            <span id="replyingTo" class="ms-2" style="display:none;"></span>
                        </small>
                        <div>
                            <button type="button" class="btn btn-outline-secondary btn-sm me-2" id="cancelReplyBtn" style="display:none;">Hủy trả lời</button>
                            <button type="submit" class="btn btn-primary"><i class="bi bi-send"></i> Gửi</button>
                        </div>
                    </div>
                </form>
                <?php else: ?>
                <div class="text-center py-3">
                    <p class="text-muted mb-2">Bạn cần đăng nhập để bình luận</p>
                    <a href="<?php echo Uri::create('login'); ?>" class="btn btn-primary btn-sm"><i class="bi bi-box-arrow-in-right"></i> Đăng nhập</a>
                </div>
                <?php endif; ?>

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
                            // Đếm theo loại & xác định biểu cảm hiện tại của người dùng (để hiển thị icon chính)
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
                            // Icon chính theo biểu cảm của user
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
                                    <button class="btn btn-light btn-sm icon-size" onclick="reactComment(<?= (int)$c->id ?>, 'like')"><i class="bi bi-hand-thumbs-up-fill"></i></button>
                                    <button class="btn btn-light btn-sm icon-size" onclick="reactComment(<?= (int)$c->id ?>, 'love')">❤️</button>
                                    <button class="btn btn-light btn-sm icon-size" onclick="reactComment(<?= (int)$c->id ?>, 'haha')">😆</button>
                                    <button class="btn btn-light btn-sm icon-size" onclick="reactComment(<?= (int)$c->id ?>, 'wow')">😮</button>
                                    <button class="btn btn-light btn-sm icon-size" onclick="reactComment(<?= (int)$c->id ?>, 'sad')">😢</button>
                                    <button class="btn btn-light btn-sm icon-size" onclick="reactComment(<?= (int)$c->id ?>, 'angry')">😡</button>
                                </div>
                            </div>
                            <?php
                            echo '<button class="btn btn-link btn-sm p-0" onclick="startReply('.(int)$c->id.', \''.addslashes($c->user->username ?? '').'\')">Trả lời</button>';
                            echo '</div>';
                            
                            // Hiển thị nút xem replies nếu có
                            if ($hasReplies) {
                                echo '<div class="mt-2">';
                                echo '<button class="btn btn-link btn-sm p-0 text-primary" onclick="toggleReplies('.(int)$c->id.')" id="toggle-'.(int)$c->id.'">';
                                echo '<i class="bi bi-chevron-down"></i> Xem '.$replyCount.' trả lời';
                                echo '</button>';
                                echo '</div>';
                                
                                // Container cho replies (ẩn mặc định)
                                echo '<div class="replies-container" id="replies-'.(int)$c->id.'" style="display: none; margin-top: 12px;">';
                                $renderTree($node['children'], $level+1);
                                echo '</div>';
                            }
                            
                            echo '</div>';
                            echo '</div>';
                            echo '</li>';
                        }
                        echo '</ul>';
                    };
                    $renderTree($comments, 0);
                    ?>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.ck-content { 
    height: 300px; 
}

/* Styling cho bình luận giống Facebook */
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
.reaction-panel { display: none; position: absolute; left: 0; transform: translateY(-110%); background: #fff; padding: 6px 8px; border-radius: 20px; border: 1px solid #e4e6ea; white-space: nowrap; z-index: 2; top: 90px;}
.reaction-panel .btn { margin: 0 2px; }
.reaction-wrapper:hover .reaction-panel { display: inline-block; }
/* Tăng kích thước icon */
.reaction-panel .btn.icon-size { font-size: 22px; padding: 6px 8px; }
.reaction-panel .btn.icon-size i { font-size: 22px; }
.reaction-main { font-size: 18px; }
.reaction-main i { font-size: 18px; }
/* Tăng cỡ icon chính trong reaction-wrapper */
.reaction-main [id^="cr-main-"] { font-size: 19px; line-height: 1; display: inline-block; }
/* Màu hồng cho icon Like để đồng nhất theme */
.reaction-panel .btn .bi-hand-thumbs-up-fill { color: #e83e8c; }
.reaction-main .bi-hand-thumbs-up-fill { color: #e83e8c; }
</style>

<!-- CKEditor Script -->
<script src="https://cdn.ckeditor.com/ckeditor5/39.0.1/classic/ckeditor.js"></script>

<script>
// Styling & behavior cho reaction panel (hover để mở)
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
        // đóng tất cả khi hover ra ngoài
        Array.from(openPanels).forEach(closeReactionPanel);
    }
});
document.addEventListener('DOMContentLoaded', function(){
    // Khởi tạo CKEditor cho bình luận
    if (document.getElementById('content')) {
        ClassicEditor
            .create(document.querySelector('#content'), {
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
                placeholder: 'Nhập nội dung, hỗ trợ @tag người dùng...'
            })
            .then(editor => {
                window.discussionEditor = editor;
            })
            .catch(error => {
                console.error('Lỗi khởi tạo CKEditor:', error);
            });
    }
    
    const parentInput = document.getElementById('parent_id');
    const replyingTo = document.getElementById('replyingTo');
    const cancelBtn = document.getElementById('cancelReplyBtn');
    const content = document.getElementById('content');

    window.startReply = function(commentId, username){
        parentInput.value = commentId;
        replyingTo.style.display = '';
        replyingTo.textContent = '(Trả lời ' + username + ')';
        cancelBtn.style.display = '';
        
        // Focus vào CKEditor
        if (window.discussionEditor) {
            window.discussionEditor.editing.view.focus();
        } else {
            content.focus();
        }
        
        if (username) {
            const currentContent = window.discussionEditor ? window.discussionEditor.getData() : content.value;
            if (!currentContent.startsWith('@'+username)) {
                const newContent = '@' + username + ' ' + currentContent;
                if (window.discussionEditor) {
                    window.discussionEditor.setData(newContent);
                } else {
                    content.value = newContent;
                }
            }
        }
    };

    cancelBtn.addEventListener('click', function(){
        parentInput.value = 0;
        replyingTo.style.display = 'none';
        cancelBtn.style.display = 'none';
    });
    
    // Comment form handling
    const commentForm = document.getElementById('commentForm');
    if (commentForm) {
        console.log('Comment form found:', commentForm);
        commentForm.addEventListener('submit', function(e) {
            console.log('Form submit event triggered');
            e.preventDefault();
            
            // Lấy nội dung từ CKEditor
            let content = '';
            if (window.discussionEditor) {
                content = window.discussionEditor.getData().trim();
                // Cập nhật giá trị textarea với nội dung từ CKEditor
                document.getElementById('content').value = content;
            } else {
                content = document.getElementById('content').value.trim();
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
});

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


