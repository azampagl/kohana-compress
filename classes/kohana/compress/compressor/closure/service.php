<?php defined('SYSPATH') or die('No direct access allowed.');
/**
 * Closure service compressor wrapper.
 * 
 * THIS IS MERELY A WRAPPER!
 * Actual implementation belongs to the respected developer(s).
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
		
		// Play nice with http
		$post = preg_replace('/%5B(?:[0-9]|[1-9][0-9]+)%5D=/', '=', http_build_query($post));
		
		$response = Request::factory('http://closure-compiler.appspot.com/compile')
			->headers('content-type', 'application/x-www-form-urlencoded')
			->method(HTTP_Request::POST)
			->body($post)
			->execute();

		file_put_contents($out, $response);
	}

} // End Kohana_Compress_Compressor_Closure_Service
