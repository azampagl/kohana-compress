<?php defined('SYSPATH') or die('No direct access allowed.');
/**
 * Closure service compressor implementation.
 *
 * @see http://code.google.com/closure/compiler/docs/gettingstarted_api.html
 *
 * @package    Media
 * @author     azampagl
 * @license    ISC
 */
abstract class Media_Compressor_Closure_Service_Core extends Media_Compressor {

	/**
	 * @see  parent
	 */
	public function compress(array $files, $out, array $args = NULL)
	{
		if ($args['type'] != 'js')
			throw new Media_Exception('Closure compiler only supports javascript files.');

		for ($i = 0; $i < count($files); $i++)
		{
			// If HTTP wasn't included, it was a local file
			if (strpos($files[$i], 'http://') === FALSE)
			{
				$files[$i] = URL::base(TRUE, TRUE).'/'.$files[$i];
			}
		}

		// HTTP query
		$query = array(
			'code_url'			=> $files,
			'compilation_level'	=> $this->_config['compilation_level'],
			'output_format'		=> 'text',
			'output_info'		=> 'compiled_code',
		);

		// Play nice with HTTP query arrays
		$post = preg_replace('/%5B(?:[0-9]|[1-9][0-9]+)%5D=/', '=', http_build_query($query));

		$response = Remote::get($this->_config['url'], array(
			CURLOPT_POST       => TRUE,
			CURLOPT_POSTFIELDS => $post,
		));

		file_put_contents($out, $response);
	}

} // End Media_Compressor_Closure_Service_Core
