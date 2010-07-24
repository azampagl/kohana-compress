<?php defined('SYSPATH') or die('No direct access allowed.');
/**
 * Closure service compressor implementation.
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
	}

} // End Media_Compressor_Closure_Service_Core
