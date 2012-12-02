<?php defined('SYSPATH') OR die('Kohana bootstrap needs to be included before tests run');
/**
 * Abstract class that contains most of the tests for all
 * compression implementations.
 *
 * @package    Compress
 * @author     azampagl
 * @license    ISC
 * @copyright  (c) 2011 - Present Aaron Zampaglione <azampagl@azampagl.com>
 */
abstract class Kohana_Compress_CompressTest extends Unittest_TestCase
{
	/**
	* Provides the args sent to the respective methods.
	*
	* @return array
	*/
	abstract public function provider_args();
	
	/**
	 * Provides "data" which is an instance and the args to send. 
	 * 
	 * @return array
	 */
	public function provider_data()
	{
		$data = array();
		
		$instances = $this->provider_instances();
		$method = $this->provider_method();
		$args = $this->provider_args();
		
		foreach ($instances as $instance)
		{
			foreach ($args as $arg)
			{
				$data[] = array(
					$instance,
					$method,
					$arg
				);
			}
		}
		
		return $data;
	}
	
	/**
	 * Returns compress instances.
	 * 
	 * @return array
	 */
	abstract public function provider_instances();
	
	/**
	* Returns the compress method.
	*
	* @return string
	*/
	abstract public function provider_method();
	
	/**
	 * Tests compression.
	 * 
	 * @test
	 * @dataProvider provider_data
	 * 
	 * @param  Compress  compress instance
	 * @param  string    method
	 * @param  array     parameters
	 */
	public function test_compress($instance, $method, $args)
	{
		// Clear out the cache...
		Kohana::cache(Compress::CACHE_KEY, array());

		$result = NULL;
		if (Arr::get($args, 'output'))
		{
			$instance->$method($args['input'], $args['output']);
			$result = Arr::get($args, 'output');
		}
		else
		{
			$result = $instance->$method($args['input']);
			$result = DOCROOT.$result;
		}

		$input_size = 0.0;
		foreach ($args['input'] as $input)
		{
			clearstatcache(TRUE, $input);
			$input_size += filesize($input);
		}

		clearstatcache(TRUE, $result);
		$output_size = filesize($result);
		// Let's assert that the size of the output file is smaller than the original.
		$this->assertTrue($output_size > 0 AND $output_size <= $input_size);

		// Remove the output file.
		unlink($result);
	}
}
