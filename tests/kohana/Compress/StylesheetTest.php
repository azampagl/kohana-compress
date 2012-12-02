<?php defined('SYSPATH') OR die('Kohana bootstrap needs to be included before tests run');
/**
 * Base class for stylesheet compression tests.
 *
 * @package    Compress
 * @author     azampagl
 * @license    ISC
 * @copyright  (c) 2011 - Present Aaron Zampaglione <azampagl@azampagl.com>
 */
include_once(Kohana::find_file('tests/kohana/compress', 'CompressTest'));

abstract class Kohana_Compress_StylesheetTest extends Kohana_Compress_CompressTest
{
	/**
	 * Provides stylesheet test data.
	 *
	 * @return array
	 */
	public function provider_args()
	{
		return array(
			array(
				'input' => array(
					Kohana::find_file('tests', 'data/kohana/compress/css/test1', 'css'),
					Kohana::find_file('tests', 'data/kohana/compress/css/test2', 'css'),
				),
			),
			array(
				'input' => array(
					Kohana::find_file('tests', 'data/kohana/compress/css/test1', 'css'),
					Kohana::find_file('tests', 'data/kohana/compress/css/test2', 'css'),
				),
				'output'  => DOCROOT.'kohana-compress-test-out.css',
			),
		);
	}
	
	/**
	 * @see parent
	 */
	public function provider_method()
	{
		return 'styles';
	}
}
