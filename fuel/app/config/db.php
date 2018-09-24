<?php

return array(
    	'default' => array(
		'type'        => 'mysqli',
		'connection'  => array(
			'dsn'        => '',
			'hostname'   => '127.0.0.1',
            'port'       => '3306',
			'username'   => 'cas_admin',
			'password'   => 'cas_admin',
			'database'   => 'cas_game',
			'persistent' => false,
			'compress'   => false,
		),
		'identifier'   => '`',
		'table_prefix' => '',
		'charset'      => 'utf8',
		'collation'    => false,
		'enable_cache' => true,
		'profiling'    => false,
		'readonly'     => false,
	),
);
