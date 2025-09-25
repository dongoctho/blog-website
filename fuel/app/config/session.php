<?php
/**
 * Session configuration to fix cookie size limit
 */
return array(
	'driver' => 'cookie',
	'match_ip' => false,
	'match_ua' => true,
	'cookie_domain' => '',
	'cookie_path' => '/',
	'expire_on_close' => false,
	'expiration_time' => 7200,
	'rotation_time' => 300,
	'flash_id' => 'flash',
	'flash_auto_expire' => true,
	'post_cookie_name' => '',
);
