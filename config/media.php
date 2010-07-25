<?php defined('SYSPATH') OR die('No direct access allowed.');

return array
(
	'default' => array
	(
		'root'			=> DOCROOT,
		'dir'			=> DOCROOT.'media/cache',
		'gc'			=> TRUE,
		'filemtime'		=> TRUE,
		'compressor'	=> 'yui',
	),
	'js' => array
	(
		'root'			=> DOCROOT,
		'dir'			=> DOCROOT.'media/cache',
		'gc'			=> TRUE,
		'filemtime'		=> TRUE,
		'compressor'	=> 'closure_service',
	),
);
