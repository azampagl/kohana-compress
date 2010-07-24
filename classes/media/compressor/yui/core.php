<?php defined('SYSPATH') or die('No direct access allowed.');
/**
 * Yui compressor implementation.
 *
 * @package    Media
 * @category   Yui Compressor
 * @author     azampagl
 */
abstract class Media_Compressor_Yui_Core extends Media_Compressor {

	/**
	 * @see  parent
	 */
	public function compress(array $files, $out, array $args = NULL)
	{
		// Find our yui jar file
		$jar = Kohana::find_file(dirname($this->_config['jar']), basename($this->_config['jar'], '.jar'), 'jar');

		// Build our command
		$cmd = $this->_config['java'].' -jar '.escapeshellarg($jar).' -o '.escapeshellarg($out).' ';
		foreach ($args as $key => $value)
		{
			$cmd .= '--'.$key.' '.escapeshellarg($value).' ';
		}

		// Make sure the process runs in the background
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

} // End Media_Compressor_Yui_Core
