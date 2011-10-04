<?php defined('SYSPATH') OR die('Kohana bootstrap needs to be included before tests run');
/**
 * Abstract class that contains most of the tests for all
 * compression implementations.
 *
 * @package    Compress
 * @author     Aaron Zampaglione <azampagl@azampagl.com>
 * @copyright  (c) 2011 Aaron Zampaglione
 * @license    ISC
 */
abstract class Kohana_Compress_CompressTest extends PHPUnit_Framework_TestCase
{
	/**
	 * Tests true.
	 * 
	 * @test
	 * @dataProvider provider_data
	 * 
	 * @param  Compress  compress instance
	 * @param  array     input data
	 */
	public function test_true($instance, $input)
	{
		print_r($instance);
		print_r($input);
		$this->assertTrue(TRUE);
	}
}
