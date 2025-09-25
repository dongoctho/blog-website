<?php

use Fuel\Core\Controller_Template;
use Fuel\Core\Input;
use Fuel\Core\Response;
use Fuel\Core\Session;
use Fuel\Core\Validation;
use Fuel\Core\Log;

class Controller_Comment extends Controller_Template
{
	/**
	 * Thêm bình luận mới
	 */
	public function action_add($post_id = null)
	{
		// Kiểm tra đăng nhập
		if (!Auth::check()) {
			if ($this->is_ajax()) {
				return $this->response(array(
					'success' => false,
					'message' => 'Bạn cần đăng nhập để bình luận'
				));
			}
			Session::set_flash('error', 'Bạn cần đăng nhập để bình luận');
			Response::redirect('login');
		}

		// Kiểm tra post_id
		if (!$post_id) {
			if ($this->is_ajax()) {
				return $this->response(array(
					'success' => false,
					'message' => 'Không tìm thấy bài viết'
				));
			}
			Response::redirect('post');
		}

		// Kiểm tra post có tồn tại không
		$post = Model_Post::find($post_id);
		if (!$post) {
			if ($this->is_ajax()) {
				return $this->response(array(
					'success' => false,
					'message' => 'Bài viết không tồn tại'
				));
			}
			Session::set_flash('error', 'Bài viết không tồn tại');
			Response::redirect('post');
		}

		if (Input::method() == 'POST') {
			$val = Validation::forge('comment');
			
			// Validation rules
			$val->add_field('content', 'Nội dung bình luận', 'required|min_length[1]|max_length[1000]');

            if ($val->run()) {
				try {
					// Tạo comment mới
					$comment = Model_Comment::forge();
                    $comment->post_id = $post_id;
					$comment->user_id = Auth::get('id');
                    // Hỗ trợ reply: nhận parent_id nếu hợp lệ, cùng post
                    $parent_id = (int) Input::post('parent_id', 0);
                    if ($parent_id > 0) {
                        $parent = Model_Comment::find($parent_id);
                        if ($parent && (int)$parent->post_id === (int)$post_id) {
                            $comment->parent_id = $parent_id;
                        } else {
                            $comment->parent_id = 0;
                        }
                    } else {
                        $comment->parent_id = 0;
                    }
					$comment->content = Input::post('content');
					$comment->created_at = date('Y-m-d H:i:s');
					$comment->updated_at = date('Y-m-d H:i:s');

                    if ($comment->save()) {
						Log::info("Comment created successfully by user " . Auth::get('id') . " on post " . $post_id);
                        
                        // Xác định URL redirect theo loại bài viết (post thường vs discussion)
                        $redirectUrl = 'post/view/' . $post_id;
                        if ((int)$post->type === 2) {
                            $redirectUrl = 'discussion/view/' . $post->slug;
                        }

						if ($this->is_ajax()) {
							return $this->response(array(
								'success' => true,
								'message' => 'Bình luận đã được gửi thành công!'
							));
						}
						
						Session::set_flash('success', 'Bình luận đã được gửi thành công!');
                        Response::redirect($redirectUrl);
					} else {
						throw new Exception('Không thể lưu bình luận');
					}
				} catch (Exception $e) {
					Log::error('Error creating comment: ' . $e->getMessage());
					
					if ($this->is_ajax()) {
						return $this->response(array(
							'success' => false,
							'message' => 'Có lỗi xảy ra: ' . $e->getMessage()
						));
					}
					
					Session::set_flash('error', 'Có lỗi xảy ra: ' . $e->getMessage());
				}
			} else {
				$errors = array();
				foreach ($val->error() as $error) {
					$errors[] = $error->get_message();
				}
				
				if ($this->is_ajax()) {
					return $this->response(array(
						'success' => false,
						'message' => implode(', ', $errors)
					));
				}
				
				Session::set_flash('error', $errors);
			}
		}

        // Redirect fallback theo type
        if (isset($post) && (int)$post->type === 2) {
            Response::redirect('discussion/view/' . $post->slug);
        }
        Response::redirect('post/view/' . $post_id);
	}

	/**
	 * Xóa bình luận
	 */
	public function action_delete($comment_id = null)
	{
		// Kiểm tra đăng nhập
		if (!Auth::check()) {
			if ($this->is_ajax()) {
				return $this->response(array(
					'success' => false,
					'message' => 'Bạn cần đăng nhập'
				));
			}
			Response::redirect('login');
		}

		// Kiểm tra comment_id
		if (!$comment_id) {
			if ($this->is_ajax()) {
				return $this->response(array(
					'success' => false,
					'message' => 'Không tìm thấy bình luận'
				));
			}
			Response::redirect_back();
		}

		// Tìm comment
		$comment = Model_Comment::find($comment_id);
		if (!$comment) {
			if ($this->is_ajax()) {
				return $this->response(array(
					'success' => false,
					'message' => 'Bình luận không tồn tại'
				));
			}
			Session::set_flash('error', 'Bình luận không tồn tại');
			Response::redirect_back();
		}

		// Kiểm tra quyền xóa (chỉ người tạo hoặc admin)
		if ($comment->user_id != Auth::get('id') && Auth::get('role_id') != 1) {
			if ($this->is_ajax()) {
				return $this->response(array(
					'success' => false,
					'message' => 'Bạn không có quyền xóa bình luận này'
				));
			}
			Session::set_flash('error', 'Bạn không có quyền xóa bình luận này');
			Response::redirect_back();
		}

		if (Input::method() == 'POST') {
			try {
				$post_id = $comment->post_id;
				
				if ($comment->delete()) {
					Log::info("Comment {$comment_id} deleted by user " . Auth::get('id'));
					
					if ($this->is_ajax()) {
						return $this->response(array(
							'success' => true,
							'message' => 'Bình luận đã được xóa'
						));
					}
					
					Session::set_flash('success', 'Bình luận đã được xóa');
					Response::redirect('post/view/' . $post_id);
				} else {
					throw new Exception('Không thể xóa bình luận');
				}
			} catch (Exception $e) {
				Log::error('Error deleting comment: ' . $e->getMessage());
				
				if ($this->is_ajax()) {
					return $this->response(array(
						'success' => false,
						'message' => 'Có lỗi xảy ra: ' . $e->getMessage()
					));
				}
				
				Session::set_flash('error', 'Có lỗi xảy ra: ' . $e->getMessage());
			}
		}

		Response::redirect_back();
	}

	/**
	 * Thả biểu cảm cho bình luận (giống Like). Nhận reaction_type (vd: like, love...)
	 */
	public function action_react($comment_id = null)
	{
		if (!Auth::check()) {
			return $this->response(array(
				'success' => false,
				'message' => 'Bạn cần đăng nhập'
			));
		}

		if (!$comment_id) {
			return $this->response(array(
				'success' => false,
				'message' => 'Thiếu comment_id'
			));
		}

		$comment = Model_Comment::find($comment_id);
		if (!$comment) {
			return $this->response(array(
				'success' => false,
				'message' => 'Bình luận không tồn tại'
			));
		}
		$reactionType = trim((string) Input::post('reaction_type', 'like'));

		$userId = (int) Auth::get('id');

		try {
			$existing = Model_CommentReaction::query()
				->where('comment_id', $comment_id)
				->where('user_id', $userId)
				->get_one();

			if ($existing) {
				if ($existing->reaction_type === $reactionType) {
					// Bỏ biểu cảm nếu bấm lại cùng loại
					$existing->delete();
					$state = 'removed';
				} else {
					// Đổi loại biểu cảm
					$existing->reaction_type = $reactionType;
					$existing->updated_at = date('Y-m-d H:i:s');
					$existing->save();
					$state = 'updated';
				}
			} else {
				// Tạo mới
				$rec = Model_CommentReaction::forge(array(
					'user_id' => $userId,
					'comment_id' => (int) $comment_id,
					'reaction_type' => $reactionType,
					'created_at' => date('Y-m-d H:i:s'),
					'updated_at' => date('Y-m-d H:i:s'),
				));
				$rec->save();
				$state = 'added';
			}

			// Lấy lại thống kê nhanh
			$all = Model_CommentReaction::query()
				->where('comment_id', $comment_id)
				->get();
			$counts = array();
			foreach ($all as $rec) {
				$t = $rec->reaction_type ?: 'like';
				$counts[$t] = isset($counts[$t]) ? ($counts[$t] + 1) : 1;
			}
			$total = 0;
			foreach ($counts as $v) { $total += (int)$v; }

			return $this->response(array(
				'success' => true,
				'state' => $state,
				'count' => (int) $total,
				'counts' => $counts,
			));
		} catch (Exception $e) {
			return $this->response(array(
				'success' => false,
				'message' => 'Không thể cập nhật biểu cảm: '.$e->getMessage(),
			));
		}
	}

	/**
	 * Helper method để kiểm tra AJAX request
	 */
	private function is_ajax()
	{
		return Input::server('HTTP_X_REQUESTED_WITH') === 'XMLHttpRequest';
	}

	/**
	 * Helper method để trả về JSON response
	 */
	private function response($data)
	{
		return Response::forge(json_encode($data), 200, array(
			'Content-Type' => 'application/json'
		));
	}

}
