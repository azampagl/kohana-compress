<?php defined('SYSPATH') or die('No direct access allowed.');
/**
 * cssmin wrapper.
 *
 * THIS IS MERELY A WRAPPER!
 * Actual implementation belongs to the respected developer(s).
 * @see http://code.google.com/p/cssmin/
 *
 * @package    Compress
 * @author     azampagl
 * @license    ISC
 */
abstract class Kohana_Compress_Compressor_Cssmin extends Compress_Compressor {

	/**
	 * @see  parent
	 */
	public function compress(array $files, $out, array $args = NULL)
	{
		if ($args['type'] == 'js')
			throw new Compress_Exception("cssmin does not support javascripts.");

		include_once Kohana::find_file(dirname($this->_config['exe']), basename($this->_config['exe'], '.php'), 'php');

		$contents = $this->_compact($files);
		$contents = CssMin::minify($contents, $this->_config['options']);
		file_put_contents($out, $contents);
	}

} // End Kohana_Compress_Compressor_Cssmin
