<?php defined('SYSPATH') or die('No direct access allowed.');
/**
 * Base class for the all compressors.  Contains
 * compact method (not used).
 *
 * @package    Compress
 * @author     azampagl
 * @license    ISC
 */
abstract class Compress_Compressor_Core {

	// Config
	protected $_config;

	/**
	 * Sets the config.
	 *
	 * @param   array   config
	 * @return  Compress_Compressor_*
	 */
	public function __construct($config)
	{
		$this->_config = $config;
	}

	/**
	 * Generate compressed file.
	 *
	 * @param   array    files to be compressed
	 * @param   string   desired out file (absolute path)
	 * @param   array    additional arguments
	 * @return  void
	 */
	abstract public function compress(array $files, $out, array $args = NULL);

	/**
	 * Compacts all of the files into a temp one
	 * for later compression.
	 *
	 * @param   array   files
	 * @return  string
	 */
	protected function _compact(array $files)
	{
		$name = tempnam(sys_get_temp_dir(), 'compresscache_'.strval(time()));

		$tmp = fopen($name, "a");

		foreach ($files as $file)
		{
			$handle = fopen($file, "r");
			fwrite($tmp, fread($handle, filesize($file)));
			fclose($handle);
		}

		fclose($tmp);

		return $name;
	}

} // End Compress_Compressor_Core
