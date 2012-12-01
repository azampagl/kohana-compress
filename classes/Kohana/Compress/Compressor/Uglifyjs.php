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
 * @copyright  (c) 2011 - Present Aaron Zampaglione <azampagl@azampagl.com>
 */
abstract class Kohana_Compress_Compressor_Uglifyjs extends Compress_Compressor {

	/**
	 * @see  parent
	 */
	public function compress(array $files, $out, array $args = NULL)
	{
		// Build our command.
		$cmd = $this->_config['cmd'].' ';
		foreach ($this->_config['options'] as $key => $value)
		{
			$cmd .= '--'.$key.' '.$value.' ';
		}
		$cmd .= '-o '.escapeshellarg($out);

		// Check what environment so that we can pipe.
		if (Kohana::$is_windows)
		{
			$cmd = 'type '.implode(' ', array_map('realpath', $files)).' | '.$cmd;
		}
		else
		{
			$cmd = 'cat '.implode(' ', array_map('realpath', $files)).' | '.$cmd;

			// Put the location of node and uglifyjs in our path variable.
			putenv('PATH='.getenv('PATH').':'.$this->_config['node_dir'].':'.$this->_config['uglifyjs_dir']);
		}

		exec($cmd);
	}

} // End Kohana_Compress_Compressor_Uglifyjs
