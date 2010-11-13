<?php defined('SYSPATH') OR die('No direct access allowed.');

return array
(
	'default' => array
	(
		'force_exec'	=> FALSE,
		'root'			=> DOCROOT,
		'dir'			=> DOCROOT.'media/cache',
		'gc'			=> TRUE,
		'filemtime'		=> TRUE,
		'compressor'	=> 'yui',
	),
	'closure' => array
	(
		'force_exec'	=> FALSE,
		'root'			=> DOCROOT,
		'dir'			=> DOCROOT.'media/cache',
		'gc'			=> TRUE,
		'filemtime'		=> TRUE,
		'compressor'	=> 'closure_service',
	),
);
