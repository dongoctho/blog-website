<?php

namespace Fuel\Tasks;

class Publish
{
    public static function run()
    {
        $now = date('Y-m-d H:i:s');

        $publish_posts = \Model_Post::find('all', [
            'where' => [
                ['publish_start_date', '<=', $now],
                ['is_published', '=', 0],
            ],
        ]);

        $unpublish_posts = \Model_Post::find('all', [
            'where' => [
                ['publish_end_date', '<=', $now],
                ['is_published', '=', 1],
            ],
        ]);

        $count_publish = 0;
        $count_unpublish = 0;

        foreach ($publish_posts as $post) {
            $post->is_published = 1;
            $post->save();
            $count_publish++;
        }

        foreach ($unpublish_posts as $post) {
            $post->is_published = 0;
            $post->save();
            $count_unpublish++;
        }

        echo "[" . $now . "] Đã publish {$count_publish} bài viết.\n";
        echo "[" . $now . "] Đã unpublish {$count_unpublish} bài viết.\n";
    }
}