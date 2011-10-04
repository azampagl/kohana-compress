<?php defined('SYSPATH') OR die('Kohana bootstrap needs to be included before tests run');
/**
 * Tests Closure App compression.
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
			Compress::instance('default')
		);
	}
}
