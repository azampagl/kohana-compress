<?php defined('SYSPATH') OR die('Kohana bootstrap needs to be included before tests run');
/**
 * Tests Closure App compression.
 * 
 * For some reason, HTTP requests don't work properly in phpunit,
 * so we can't test the service.
 *
 * @package    Compress
 * @author     azampagl
 * @license    ISC
 * @copyright  (c) 2011 - Present Aaron Zampaglione <azampagl@azampagl.com>
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
			Compress::instance('test_closure_app_2', array(
				'root'			=> DOCROOT,
				'dir'			=> DOCROOT,
				'gc'			=> TRUE,
				'filemtime'		=> FALSE,
				'compressor'	=> 'closure_application',
				)
			),
			Compress::instance('test_closure_app_3', array(
				'root'			=> DOCROOT,
				'dir'			=> DOCROOT,
				'gc'			=> FALSE,
				'filemtime'		=> TRUE,
				'compressor'	=> 'closure_application',
				)
			),
			Compress::instance('test_closure_app_4', array(
				'root'			=> DOCROOT,
				'dir'			=> DOCROOT,
				'gc'			=> FALSE,
				'filemtime'		=> TRUE,
				'compressor'	=> 'closure_application',
				)
			),
		);
	}
}
