<?php defined('SYSPATH') or die('No direct access allowed.');
/**
 * Closure application compressor implementation.
 *
 * @see http://code.google.com/closure/compiler/docs/gettingstarted_app.html
 *
 * @package    Media
 * @author     azampagl
 * @license    ISC
 */
abstract class Media_Compressor_Closure_Application_Core extends Media_Compressor {

	/**
	 * @see  parent
	 */
	public function compress(array $files, $out, array $args = NULL)
	{
		if ($args['type'] != 'js')
			throw new Media_Exception('Closure compiler only supports javascript files.');

		// Find our jar file
		$jar = Kohana::find_file(dirname($this->_config['jar']), basename($this->_config['jar'], '.jar'), 'jar');

		// Build our command
		$cmd = $this->_config['java'].' -jar '.escapeshellarg($jar);
		$cmd .= ' --js_output_file '.escapeshellarg($out).' ';
		$cmd .= ' --compilation_level '.escapeshellarg($this->_config['compilation_level']).' ';

		$files = array_map('realpath', $files);

		foreach ($files as $file)
		{
			$cmd .= '--js '.escapeshellarg($file).' ';
		}

		exec($cmd);
	}

} // End Media_Compressor_Closure_Application_Core
