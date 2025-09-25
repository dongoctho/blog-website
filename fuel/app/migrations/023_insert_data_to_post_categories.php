<?php

namespace Fuel\Migrations;

class Insert_data_to_post_categories
{
    public function up()
    {
        $posts = \DB::select('id')->from('posts')->execute()->as_array();
        $categories = \DB::select('id')->from('categories')->execute()->as_array();
        $categoryIds = array_column($categories, 'id');

        if (empty($posts) || empty($categoryIds)) {
            return;
        }

        \DB::start_transaction();
        try {
            $insert = \DB::insert('post_categories')
                        ->columns(['post_id','category_id','created_at','updated_at']);

            foreach ($posts as $p) {
                $randCategoryId = $categoryIds[array_rand($categoryIds)];
                $insert->values([
                    $p['id'],
                    $randCategoryId,
                    date('Y-m-d H:i:s'),
                    date('Y-m-d H:i:s'),
                ]);
            }

            $insert->execute();
            \DB::commit_transaction();
        } catch (\Exception $e) {
            \DB::rollback_transaction();
            throw $e;
        }
    }

    public function down()
    {
        // Xóa toàn bộ data trong post_categories (cẩn thận rollback)
        \DB::delete('post_categories')->execute();
    }
}
