<?php defined('SYSPATH') or die('No direct script access.');

return array(
	'modules' => array(

		// This should be the path to this modules userguide pages, without the 'guide/'.
		//  E.g.: '/guide/modulename/' would be 'modulename'.
		'compress' => array(

			// Whether this modules userguide pages should be shown.
			'enabled' => TRUE,
			
			// The name that should show up on the userguide index page.
			'name' => 'Compress',

			// A short description of this module, shown on the index page.
			'description' => 'Dynamic static file compression.',
			
			// Copyright message, shown in the footer for this module.
			'copyright' => '&copy; 2011 - present Aaron Zampaglione',
		)	
	)
);