<?php defined('SYSPATH') or die('No direct access allowed.');
/**
 * Yui compressor wrapper.
 *
 * THIS IS MERELY A WRAPPER!
 * Actual implementation belongs to the respected developer(s).
 * @see http://developer.yahoo.com/yui/compressor/
 *
 * @package    Compress
 * @author     azampagl
 * @license    ISC
 */
abstract class Kohana_Compress_Compressor_Yui extends Compress_Compressor {

	/**
	 * @see  parent
	 */
	public function compress(array $files, $out, array $args = NULL)
	{
		// Find our jar file
		$jar = Kohana::find_file(dirname($this->_config['jar']), basename($this->_config['jar'], '.jar'), 'jar');

		// Build our command
		$cmd = $this->_config['java'].' -jar '.escapeshellarg($jar).' -o '.escapeshellarg($out).' ';
		foreach ($args as $key => $value)
		{
			$cmd .= '--'.$key.' '.escapeshellarg($value).' ';
		}

		// Check what environment so that we can pipe
		if (Kohana::$is_windows)
		{
			$cmd = 'type '.implode(' ', array_map('realpath', $files)).' | '.$cmd;
		}
		else
		{
			$cmd = 'cat '.implode(' ', array_map('realpath', $files)).' | '.$cmd.'> /dev/null 2>&1';
		}

		exec($cmd);
	}

} // End Kohana_Compress_Compressor_Yui
