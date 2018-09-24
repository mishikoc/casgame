<?php
return array(
	'_root_'  => 'cas/index',  // The default route
	'_404_'   => 'cas/404',    // The main 404 route
	'game'    => 'user_panel/game',
	'hello(/:name)?' => array('cas/hello', 'name' => 'hello'),
);

Router::add('game', 'user_panel/game');