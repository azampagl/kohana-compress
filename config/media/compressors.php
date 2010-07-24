<?php defined('SYSPATH') OR die('No direct access allowed.');

return array
(
	'yui'					=> array
	(
		'java'				=> 'java',
		'jar'				=> 'vendor/yui/yuicompressor-2.4.2.jar',
	),
	'closure_application'	=> array
	(
		'java'				=> 'java',
		'jar'				=> 'vendor/closure/closure-compiler-latest.jar',
		'compilation_level'	=> 'ADVANCED_OPTIMIZATIONS',
	),
	'closure_service'		=> array
	(
	),
);
