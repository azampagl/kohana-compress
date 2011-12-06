<?php defined('SYSPATH') OR die('Kohana bootstrap needs to be included before tests run');
/**
 * Base class for stylesheet compression tests.
 *
 * @package    Compress
 * @author     Aaron Zampaglione <azampagl@azampagl.com>
 * @copyright  (c) 2011 Aaron Zampaglione
 * @license    ISC
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
					Kohana::find_file('tests', 'data/kohana/compress/css/jquerycustom', 'css'),
					Kohana::find_file('tests', 'data/kohana/compress/css/jqueryall', 'css'),
				),
			),
			array(
				'input' => array(
					Kohana::find_file('tests', 'data/kohana/compress/css/jquerycustom', 'css'),
					Kohana::find_file('tests', 'data/kohana/compress/css/jqueryall', 'css'),
				),
				'output'  => DOCROOT.'out.css',
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
