<?php defined('SYSPATH') OR die('Kohana bootstrap needs to be included before tests run');
/**
 * Base class for javascript compression tests.
 *
 * @package    Compress
 * @author     azampagl
 * @license    ISC
 * @copyright  (c) 2011 - Present Aaron Zampaglione <azampagl@azampagl.com>
 */
include_once(Kohana::find_file('tests/kohana/compress', 'CompressTest'));

abstract class Kohana_Compress_JavascriptTest extends Kohana_Compress_CompressTest
{
	/**
	 * Provides javascript test data.
	 *
	 * @return array
	 */
	public function provider_args()
	{
		return array(
			array(
				'input' => array(
					Kohana::find_file('tests', 'data/kohana/compress/js/test1', 'js'),
					Kohana::find_file('tests', 'data/kohana/compress/js/test2', 'js'),
				),
				'output'  => 'kohana-compress-test-out.js',
			),
			array(
				'input' => array(
					Kohana::find_file('tests', 'data/kohana/compress/js/test1', 'js'),
					Kohana::find_file('tests', 'data/kohana/compress/js/test2', 'js'),
				),
			),			
		);
	}
	
	/**
	 * @see parent
	 */
	public function provider_method()
	{
		return 'scripts';
	}
}
