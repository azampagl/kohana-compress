<?php defined('SYSPATH') or die('No direct access allowed.');
/**
 * Base class for the all compressors.  Contains
 * the compact method.
 *
 * @package    Media
 * @category   Compressor
 * @author     azampagl
 */
abstract class Media_Compressor_Core {

	// Config
	protected $_config;

	/**
	 * Sets the config.
	 *
	 * @param   array   config
	 * @return  Media_Compressor_*
	 */
	public function __construct($config)
	{
		$this->_config = $config;
	}

	/**
	 * Generate compressed files.
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
	 * @return  string  temp file
	 */
	protected function _compact(array $files)
	{
		$name = tempnam(sys_get_temp_dir(), 'mediacache_'.strval(time()));

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

} // End Media_Compressor_Core
