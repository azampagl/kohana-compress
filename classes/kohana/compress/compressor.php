<?php defined('SYSPATH') or die('No direct access allowed.');
/**
 * Base class for the all compressors.  Contains
 * compact method (not used).
 *
 * @package    Compress
 * @author     azampagl
 * @license    ISC
 */
abstract class Kohana_Compress_Compressor {

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
	 * Compacts all of the files.  Places
	 * contents into tmp file if requested.
	 *
	 * @param   array   files
	 * @param   boolean tmp file?
	 * @return  mixed
	 */
	protected function _compact(array $files, $tmp = FALSE)
	{
		$contents = "";

		foreach ($files as $file)
		{
			$contents .= file_get_contents($file);
		}

		if ($tmp)
		{
			$name = tempnam(sys_get_temp_dir(), strval(time()));

			file_put_contents($name, $contents);

			return $name;
		}

		return $contents;
	}

} // End Kohana_Compress_Compressor
