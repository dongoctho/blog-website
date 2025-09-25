<?php

namespace Fuel\Migrations;

class Add_google_auth_to_users
{
    public function up()
    {
        // Thêm các cột Google auth vào bảng users
        \DBUtil::add_fields('users', array(
            'google_id' => array('constraint' => 255, 'null' => true, 'type' => 'varchar'),
            'google_avatar' => array('constraint' => 500, 'null' => true, 'type' => 'varchar'),
            'is_google_account' => array('null' => false, 'type' => 'boolean', 'default' => 0),
            'google_access_token' => array('null' => true, 'type' => 'text'),
            'google_refresh_token' => array('null' => true, 'type' => 'text'),
        ));
        
        // Thêm index cho google_id để tìm kiếm nhanh
        \DB::query('ALTER TABLE users ADD INDEX idx_google_id (google_id)')->execute();
        
        // Cho phép password null cho Google accounts
        \DB::query('ALTER TABLE users MODIFY password VARCHAR(255) NULL')->execute();
    }

    public function down()
    {
        // Xóa index
        \DB::query('ALTER TABLE users DROP INDEX idx_google_id')->execute();
        
        // Xóa các cột Google auth
        \DBUtil::drop_fields('users', array(
            'google_id',
            'google_avatar', 
            'is_google_account',
            'google_access_token',
            'google_refresh_token'
        ));
        
        // Trả lại password NOT NULL
        \DB::query('ALTER TABLE users MODIFY password VARCHAR(255) NOT NULL')->execute();
    }
}
