<?php defined('SYSPATH') OR die('Kohana bootstrap needs to be included before tests run');
/**
 * Tests YUI javascript compression.
 *
 * @package    Compress
 * @author     azampagl
 * @license    ISC
 * @copyright  (c) 2011 - Present Aaron Zampaglione <azampagl@azampagl.com>
 */
include_once(Kohana::find_file('tests/kohana/compress', 'JavascriptTest'));

class Kohana_Compress_YUIJavascriptTest extends Kohana_Compress_JavascriptTest
{
	/**
	 * @see parent
	 */
	public function provider_instances()
	{
		return array(
			Compress::instance('test_yui_javascript_1', array(
				'root'			=> DOCROOT,
				'dir'			=> DOCROOT,
				'gc'			=> TRUE,
				'filemtime'		=> TRUE,
				'compressor'	=> 'yui',
				)
			),
			Compress::instance('test_yui_javascript_2', array(
				'root'			=> DOCROOT,
				'dir'			=> DOCROOT,
				'gc'			=> TRUE,
				'filemtime'		=> FALSE,
				'compressor'	=> 'yui',
				)
			),
			Compress::instance('test_yui_javascript_3', array(
				'root'			=> DOCROOT,
				'dir'			=> DOCROOT,
				'gc'			=> FALSE,
				'filemtime'		=> TRUE,
				'compressor'	=> 'yui',
				)
			),
			Compress::instance('test_yui_javascript_4', array(
				'root'			=> DOCROOT,
				'dir'			=> DOCROOT,
				'gc'			=> FALSE,
				'filemtime'		=> FALSE,
				'compressor'	=> 'yui',
				)
			),
		);
	}
}
