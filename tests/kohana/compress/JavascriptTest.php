<?php defined('SYSPATH') OR die('Kohana bootstrap needs to be included before tests run');
/**
 * Base case for javascript compression tests.
 *
 * @package    Compress
 * @author     Aaron Zampaglione <azampagl@azampagl.com>
 * @copyright  (c) 2011 Aaron Zampaglione
 * @license    ISC
 */
include_once(Kohana::find_file('tests/kohana/compress', 'CompressTest'));

abstract class Kohana_Compress_JavascriptTest extends Kohana_Compress_CompressTest
{
	/**
	 * Provides "data" which is an instance and the args to send. 
	 * 
	 * @return array
	 */
	public function provider_data()
	{
		$data = array();
		
		$instances = $this->provider_instances();
		$args = $this->provider_args();
		
		foreach ($instances as $instance)
		{
			foreach ($args as $arg)
			{
				$data[] = array(
					$instance,
					$arg
				);
			}
		}
		
		return data;
	}
	
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
					Kohana::find_file('tests', 'data/kohana/compress/js/jquery', 'js'),
					Kohana::find_file('tests', 'data/kohana/compress/js/jqueryui', 'js'),
				),
			),
			array(
				'input' => array(
					Kohana::find_file('tests', 'data/kohana/compress/js/jquery', 'js'),
					Kohana::find_file('tests', 'data/kohana/compress/js/jqueryui', 'js'),
				),
				'output'  => 'out.js',
			),
		);
	}
}
