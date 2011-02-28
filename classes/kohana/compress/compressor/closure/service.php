<?php defined('SYSPATH') or die('No direct access allowed.');
/**
 * Closure service compressor implementation.
 *
 * @see http://code.google.com/closure/compiler/docs/gettingstarted_api.html
 *
 * @package    Compress
 * @author     azampagl
 * @license    ISC
 */
abstract class Kohana_Compress_Compressor_Closure_Service extends Compress_Compressor {

	/**
	 * @see  parent
	 */
	public function compress(array $files, $out, array $args = NULL)
	{
		if ($args['type'] == 'css')
			throw new Compress_Exception("Closure Compiler does not support stylesheets.");

		for ($i = 0; $i < count($files); $i++)
		{
			// If HTTP(S) wasn't included, it was a local file
			if (strpos($files[$i], 'http://') !== 0 AND strpos($files[$i], 'https://') !== 0)
			{
				$files[$i] = URL::base(TRUE, TRUE).'/'.$files[$i];
			}
		}

		// HTTP query
		$post = array(
			'code_url'			=> $files,
			'compilation_level'	=> $this->_config['compilation_level'],
			'output_format'		=> 'text',
			'output_info'		=> 'compiled_code',
		);

		$response = Request::factory($this->_config['url'])
			->method(HTTP_Request::POST)
			->post($post)
			->execute();

		file_put_contents($out, $response);
	}

} // End Kohana_Compress_Compressor_Closure_Service
