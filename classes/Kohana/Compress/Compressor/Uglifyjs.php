<?php defined('SYSPATH') or die('No direct access allowed.');
/**
 * UglifyJS compressor wrapper.
 *
 * THIS IS MERELY A WRAPPER!
 * Actual implementation belongs to the respected developer(s).
 * @see https://github.com/mishoo/UglifyJS2
 *
 * @package    Compress
 * @author     badsyntax
 * @license    ISC
 * @copyright  (c) 2011 - 2012 Aaron Zampaglione
 */
abstract class Kohana_Compress_Compressor_Uglifyjs extends Compress_Compressor {

	/**
	 * @see  parent
	 */
	public function compress(array $files, $out, array $args = NULL)
	{
		// Build our command
		$cmd = $this->_config['cmd'].' -o '.escapeshellarg($out).' ';
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

} // End Kohana_Compress_Compressor_Uglifyjs
