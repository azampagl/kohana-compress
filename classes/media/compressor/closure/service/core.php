<?php defined('SYSPATH') or die('No direct access allowed.');
/**
 * Closure service compressor implementation.
 *
 * @see http://code.google.com/closure/compiler/docs/gettingstarted_api.html
 * 
 * @package    Media
 * @category   Closure Service Compressor
 * @author     azampagl
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
			if ( ! strpos($files[$i], 'http://'))
			{
				$files[$i] = URL::base(TRUE, TRUE).'/'.$files[$i]; 
			}
		}
		
		$query = array(
			'code_url'			=> $files,
			'compilation_level'	=> $this->_config['compilation_level'],
			'output_format'		=> 'text',
			'output_info'		=> 'compiled_code',
		);
		
		$response = Remote::get($url, array(
			CURLOPT_POST       => TRUE,
			CURLOPT_POSTFIELDS => http_build_query($query),
		));
		
		file_put_contents($out, $response);
	}

} // End Media_Compressor_Closure_Service_Core
