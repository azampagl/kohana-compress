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
		'jar'				=> 'vendor/closure/closure-compiler-20100917.jar',
		'compilation_level'	=> 'SIMPLE_OPTIMIZATIONS',
	),
	'closure_service'		=> array
	(
		'url'				=> 'http://closure-compiler.appspot.com/compile',
		'compilation_level'	=> 'SIMPLE_OPTIMIZATIONS',
	),
);
