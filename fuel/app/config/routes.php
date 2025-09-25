<?php
/**
 * Fuel is a fast, lightweight, community driven PHP 5.4+ framework.
 *
 * @package    Fuel
 * @version    1.8.2
 * @author     Fuel Development Team
 * @license    MIT License
 * @copyright  2010 - 2019 Fuel Development Team
 * @link       https://fuelphp.com
 */

return array(
	/**
	 * -------------------------------------------------------------------------
	 *  Default route
	 * -------------------------------------------------------------------------
	 *
	 */

	'_root_' => 'post/index',

	/**
	 * -------------------------------------------------------------------------
	 *  Page not found
	 * -------------------------------------------------------------------------
	 *
	 */

	'_404_' => 'welcome/404',

	/**
	 * -------------------------------------------------------------------------
	 *  Authentication Routes - Routes cho đăng nhập/đăng xuất
	 * -------------------------------------------------------------------------
	 */
	
	// Login and logout routes
	'login' => 'auth/login',
	'auth/login' => 'auth/login',
	'logout' => 'auth/logout',
	'auth/logout' => 'auth/logout',
	'register' => 'auth/register',
	'auth/register' => 'auth/register',
	'auth/google' => 'auth/google',
	'auth/google/callback' => 'auth/google_callback',

	/**
	 * -------------------------------------------------------------------------
	 *  Admin Routes - Routes cho admin panel
	 * -------------------------------------------------------------------------
	 */
	
	// Admin dashboard and main routes
	'admin' => 'admin/index',
	'admin/dashboard' => 'admin/index',

	// Admin Posts Management
	'admin/posts' => 'admin/posts',
	'admin/posts/create' => 'admin/posts_create',
	'admin/posts/view/(:num)' => 'admin/posts_view/$1',
	'admin/posts/edit/(:num)' => 'admin/posts_edit/$1',
	'admin/posts/delete/(:num)' => 'admin/posts_delete/$1',

	// Admin Discussion Management
	'admin/discussion' => 'admin/discussion',
	'admin/discussion/create' => 'admin/discussion_create',
	'admin/discussion/view/(:num)' => 'admin/discussion_view/$1',
	'admin/discussion/edit/(:num)' => 'admin/discussion_edit/$1',
	'admin/discussion/delete/(:num)' => 'admin/discussion_delete/$1',

	// Admin Categories Management
	'admin/categories' => 'admin/categories',
	'admin/categories/create' => 'admin/categories_create',
	'admin/categories/edit/(:num)' => 'admin/categories_edit/$1',
	'admin/categories/delete/(:num)' => 'admin/categories_delete/$1',
	
	// Admin Users Management
	'admin/users' => 'admin/users',
	'admin/users/create' => 'admin/users_create',
	'admin/users/edit/(:num)' => 'admin/users_edit/$1',
	'admin/users/delete/(:num)' => 'admin/users_delete/$1',

	/**
	 * -------------------------------------------------------------------------
	 *  Blog Post Routes - Routes cho hệ thống blog
	 * -------------------------------------------------------------------------
	 */

	// Blog home and pagination
	'blog' => 'post/index',
	'blog/page/(:num)' => 'post/index/$1',
	'posts' => 'post/index', 
	'posts/index' => 'post/index',
	'posts/index/(:num)' => 'post/index/$1',
	'posts/page/(:num)' => 'post/index/$1',
	'post/page/(:num)' => 'post/index/$1',
	
	'post/edit/(:num)' => 'post/edit/$1', 
	'post/delete/(:num)' => 'post/delete/$1',
	'post/view/(:num)' => 'post/view/$1',
	'post/add_comment/(:num)' => 'comment/add/$1',
	'post/edit_comment/(:num)' => 'comment/edit/$1',
	
	'post/view/(:segment)' => 'post/view/$1',
	'post/(:segment)' => 'post/view/$1',

	// Front Discussion Routes
	'discussion' => 'discussion/index',
	'discussion/page/(:num)' => 'discussion/index/$1',
	'discussion/view/(:segment)' => 'discussion/view/$1',
	
	'sitemap.xml' => 'post/sitemap',
	'rss.xml' => 'post/rss',
);
