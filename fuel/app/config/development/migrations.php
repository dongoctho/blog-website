<?php
return array (
  'version' => 
  array (
    'app' => 
    array (
      'default' => 
      array (
        0 => '001_create_roles',
        1 => '002_create_users',
        2 => '003_create_categories',
        3 => '004_create_posts',
        4 => '005_create_images',
        5 => '006_create_post_images',
        6 => '012_insert_sample_data',
        7 => '013_add_publish_dates_to_posts',
        8 => '014_create_comments',
        9 => '015_add_google_auth_to_users',
        10 => '016_add_views_to_posts',
        11 => '017_create_wallets',
        12 => '018_create_transactions',
        13 => '019_create_qa_logs',
        14 => '020_add_type_to_posts',
        15 => '021_delete_category_id_in_posts',
        16 => '022_create_post_categories',
        17 => '023_insert_data_to_post_categories',
        18 => '025_create_reactions',
        19 => '026_create_comment_reactions',
      ),
    ),
    'module' => 
    array (
    ),
    'package' => 
    array (
      'auth' => 
      array (
        0 => '001_auth_create_usertables',
        1 => '002_auth_create_grouptables',
        2 => '003_auth_create_roletables',
        3 => '004_auth_create_permissiontables',
        4 => '005_auth_create_authdefaults',
        5 => '006_auth_add_authactions',
        6 => '007_auth_add_permissionsfilter',
        7 => '008_auth_create_providers',
        8 => '009_auth_create_oauth2tables',
        9 => '010_auth_fix_jointables',
        10 => '011_auth_group_optional',
      ),
    ),
  ),
  'folder' => 'migrations/',
  'table' => 'migration',
  'flush_cache' => false,
);
