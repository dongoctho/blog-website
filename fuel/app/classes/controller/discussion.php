<?php

use Fuel\Core\Controller_Template;
use Fuel\Core\View;
use Fuel\Core\Response;
use Fuel\Core\Uri;
use Fuel\Core\Pagination;
use Fuel\Core\Input;

class Controller_Discussion extends Controller_Template
{
    public $template = 'template';

    // Danh sách thảo luận (type=2), hiển thị dạng list dọc, không ảnh
    public function action_index($page = 1)
    {
        $per_page = 10;
        $page = (int) $page;
        if ($page < 1) { $page = 1; }
        $offset = ($page - 1) * $per_page;

        $total = Model_Post::query()->where('type', 2)->where('is_published', 1)->count();
        $posts = Model_Post::query()
            ->related('user')
            ->related('post_categories')
            ->where('type', 2)
            ->where('is_published', 1)
            ->order_by('created_at', 'desc')
            ->limit($per_page)
            ->offset($offset)
            ->get();

        $pagination = null;
        if ($total > $per_page) {
            $pagination = Pagination::forge('discussion_pagination', array(
                'total_items' => $total,
                'per_page' => $per_page,
                'uri_segment' => 3,
                'pagination_url' => Uri::create('discussion/page'),
            ));
        }

        $this->template->title = 'Thảo luận';
        $this->template->content = View::forge('discussion/index', array(
            'posts' => $posts,
            'pagination' => $pagination,
            'total' => $total,
        ));
    }

    // Xem chi tiết thảo luận + comment lồng nhau
    public function action_view($slug = null)
    {
        if (!$slug) { return Response::redirect('discussion'); }

        $post = Model_Post::query()
            ->related('user')
            ->related('post_categories')
            ->where('slug', $slug)
            ->where('type', 2)
            ->where('is_published', 1)
            ->get_one();

        if (!$post) { return Response::redirect('discussion'); }

        // Lấy toàn bộ comments và build cây cha-con
        $comment_rows = Model_Comment::query()
            ->related('user')
            ->related('reactions')
            ->where('post_id', $post->id)
            ->order_by('created_at', 'asc')
            ->get();

        $comments = $this->build_comment_tree($comment_rows);

        // Tăng số lượt xem
		$post->views = ($post->views ?? 0) + 1;
		$post->save();

        $this->template->title = $post->title . ' - Thảo luận';
        $this->template->content = View::forge('discussion/view', array(
            'post' => $post,
            'comments' => $comments,
        ));
    }

    // Xây cây comment cha-con: parent_id=0 là gốc
    private function build_comment_tree($comment_rows)
    {
        $byParent = array();
        foreach ($comment_rows as $c) {
            $pid = (int)($c->parent_id ?? 0);
            if (!isset($byParent[$pid])) $byParent[$pid] = array();
            $byParent[$pid][] = $c;
        }

        $build = function($parentId) use (&$build, &$byParent) {
            $branch = array();
            if (isset($byParent[$parentId])) {
                foreach ($byParent[$parentId] as $node) {
                    $item = array('comment' => $node, 'children' => $build((int)$node->id));
                    $branch[] = $item;
                }
            }
            return $branch;
        };

        return $build(0);
    }
}


