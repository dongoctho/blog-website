<?php

namespace Fuel\Migrations;

class Add_views_to_posts
{
    public function up()
    {
        // Thêm các cột views vào bảng posts
        \DBUtil::add_fields('posts', array(
            'views' => array('null' => true, 'type' => 'bigint'),
        ));
    }

    public function down()
    {
        // Xóa các cột views
        \DBUtil::drop_fields('posts', array(
            'views',
        ));
    }
}
