<?php defined('SYSPATH') OR die('Kohana bootstrap needs to be included before tests run');
/**
 * Tests YUI stylesheet compression.
 *
 * @package    Compress
 * @author     azampagl
 * @license    ISC
 * @copyright  (c) 2011 - Present Aaron Zampaglione <azampagl@azampagl.com>
 */
include_once(Kohana::find_file('tests/kohana/compress', 'StylesheetTest'));

class Kohana_Compress_YUIStylesheetTest extends Kohana_Compress_StylesheetTest
{
	/**
	 * @see parent
	 */
	public function provider_instances()
	{
		return array(
			Compress::instance('test_yui_stylesheet_1', array(
				'root'			=> DOCROOT,
				'dir'			=> DOCROOT,
				'gc'			=> TRUE,
				'filemtime'		=> TRUE,
				'compressor'	=> 'yui',
				)
			),
			Compress::instance('test_yui_stylesheet_2', array(
				'root'			=> DOCROOT,
				'dir'			=> DOCROOT,
				'gc'			=> TRUE,
				'filemtime'		=> FALSE,
				'compressor'	=> 'yui',
				)
			),
			Compress::instance('test_yui_stylesheet_3', array(
				'root'			=> DOCROOT,
				'dir'			=> DOCROOT,
				'gc'			=> FALSE,
				'filemtime'		=> TRUE,
				'compressor'	=> 'yui',
				)
			),
			Compress::instance('test_yui_stylesheet_4', array(
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
