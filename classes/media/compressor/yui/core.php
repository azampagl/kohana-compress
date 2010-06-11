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
	 * Generate compressed stylesheet.
	 *
	 * @param   array    files to be compressed
	 * @param   string   desired out file (absolute path)
	 * @param   array    additional arguments
	 * @return  void
	 */
	public function compress(array $files, $out, array $args = NULL)
	{
		// Find our yui jar file
		$jar = Kohana::find_file(dirname($this->_config['jar']), basename($this->_config['jar'], '.jar'), 'jar');

		// Compact all our files
		$in = $this->_compact($files);

		// Build our command
		$cmd = $this->_config['java'].' -jar '.escapeshellarg($jar).' '.escapeshellarg($in).' -o '.escapeshellarg($out).' ';
		foreach ($args as $key => $value)
		{
			$cmd .= '--'.$key.' '.escapeshellarg($value).' ';
		}

		// Make sure the process runs in the background
		if (Kohana::$is_windows)
		{
			$cmd = 'start /B '.$cmd;
		}
		else
		{
			$cmd = $cmd.' > /dev/null 2>&1 &';
		}

		exec($cmd);

		unlink($in);
	}

} // End Media_Compressor_Yui_Core
