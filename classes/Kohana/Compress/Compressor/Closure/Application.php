<?php defined('SYSPATH') or die('No direct access allowed.');
/**
 * Closure application compressor wrapper.
 * 
 * THIS IS MERELY A WRAPPER!
 * Actual implementation belongs to the respected developer(s).
 * @see http://code.google.com/closure/compiler/docs/gettingstarted_app.html
 *
 * @package    Compress
 * @author     azampagl
 * @license    ISC
 */
abstract class Kohana_Compress_Compressor_Closure_Application extends Compress_Compressor {

	/**
	 * @see  parent
	 */
	public function compress(array $files, $out, array $args = NULL)
	{
		if ($args['type'] == 'css')
			throw new Compress_Exception("Closure Compiler does not support stylesheets.");

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

} // End Kohana_Compress_Compressor_Closure_Application
