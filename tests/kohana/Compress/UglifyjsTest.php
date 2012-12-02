<?php defined('SYSPATH') OR die('Kohana bootstrap needs to be included before tests run');
/**
 * Tests CSSMin compression.
 *
 * @package    Compress
 * @author     azampagl
 * @license    ISC
 * @copyright  (c) 2011 - Present Aaron Zampaglione <azampagl@azampagl.com>
 */
include_once(Kohana::find_file('tests/kohana/compress', 'JavascriptTest'));

class Kohana_Compress_UglifyjsTest extends Kohana_Compress_JavascriptTest
{
	/**
	 * @see parent
	 */
	public function provider_instances()
	{
		return array(
			Compress::instance('test_ugilfyjs_1', array(
				'root'			=> DOCROOT,
				'dir'			=> DOCROOT,
				'gc'			=> TRUE,
				'filemtime'		=> TRUE,
				'compressor'	=> 'uglifyjs',
				)
			),
			Compress::instance('test_ugilfyjs_2', array(
				'root'			=> DOCROOT,
				'dir'			=> DOCROOT,
				'gc'			=> TRUE,
				'filemtime'		=> FALSE,
				'compressor'	=> 'uglifyjs',
				)
			),
			Compress::instance('test_ugilfyjs_3', array(
				'root'			=> DOCROOT,
				'dir'			=> DOCROOT,
				'gc'			=> FALSE,
				'filemtime'		=> TRUE,
				'compressor'	=> 'uglifyjs',
				)
			),
			Compress::instance('test_ugilfyjs_4', array(
				'root'			=> DOCROOT,
				'dir'			=> DOCROOT,
				'gc'			=> FALSE,
				'filemtime'		=> FALSE,
				'compressor'	=> 'uglifyjs',
				)
			),
		);
	}
}
