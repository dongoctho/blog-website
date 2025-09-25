<?php

namespace Fuel\Migrations;

class Add_publish_dates_to_posts
{
    public function up()
    {
        // Thêm hai cột ngày xuất bản, cho phép null để thể hiện không giới hạn
        \DBUtil::add_fields('posts', array(
            'publish_start_date' => array('null' => true, 'type' => 'datetime'),
            'publish_end_date' => array('null' => true, 'type' => 'datetime'),
        ));
    }

    public function down()
    {
        \DBUtil::drop_fields('posts', array('publish_start_date', 'publish_end_date'));
    }
}


