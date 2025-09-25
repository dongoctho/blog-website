<?php

return array(
    'remember_me' => array(
        'enabled' => true,
        'cookie_name' => 'rmcookie',
        'expiration' => 86400*31
    ),
    'role' => array(
        1 => array('name' => 'Admin', 'roles' => array('admin')),
        2 => array('name' => 'Editer', 'roles' => array('editer')),
        3 => array('name' => 'Client', 'roles' => array('client')),
    ),
);
