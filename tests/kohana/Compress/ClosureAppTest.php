<?php defined('SYSPATH') OR die('Kohana bootstrap needs to be included before tests run');
/**
 * Tests Closure App compression.
 * 
 * For some reason, HTTP requests don't work properly in phpunit,
 * so we can't test the service.
 *
 * @package    Compress
 * @author     Aaron Zampaglione <azampagl@azampagl.com>
 * @copyright  (c) 2011 Aaron Zampaglione
 * @license    ISC
 */
include_once(Kohana::find_file('tests/kohana/compress', 'JavascriptTest'));

class Kohana_Compress_ClosureAppTest extends Kohana_Compress_JavascriptTest
{
	/**
	 * @see parent
	 */
	public function provider_instances()
	{
		return array(
			Compress::instance('test_closure_app_1', array(
				'root'			=> DOCROOT,
				'dir'			=> DOCROOT,
				'gc'			=> TRUE,
				'filemtime'		=> TRUE,
				'compressor'	=> 'closure_application',
				)
			),
		);
	}
}
