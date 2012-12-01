<?php defined('SYSPATH') or die('No direct access allowed.');
/**
 * Base class for the all compressors.  Contains
 * compact method (not used).
 *
 * @package    Compress
 * @author     azampagl
 * @license    ISC
 * @copyright  (c) 2011 - Present Aaron Zampaglione <azampagl@azampagl.com>
 */
abstract class Kohana_Compress_Compressor {

	/**
	 * Configuration for $this instance.
	 *
	 * @var array
	 */
	protected $_config;

	/**
	 * Sets the config.
	 *
	 * @param  array
	 *   Config
	 * @return Compress_Compressor_*
	 */
	public function __construct($config)
	{
		$this->_config = $config;
	}

	/**
	 * Generate compressed file.
	 *
	 * @param  array
	 *   Files to be compressed.
	 * @param  string
	 *   Desired out file (absolute path).
	 * @param  array
	 *   Additional arguments.
	 */
	abstract public function compress(array $files, $out, array $args = NULL);

	/**
	 * Compacts all of the files.  Places
	 * contents into tmp file if requested.
	 *
	 * @param  array
	 *   Files to be compacted.
	 * @param  boolean
	 *   Should a tmp file be used?
	 * @return string
	 *   The name of the tmp file to store compacted files
	 *   or the contents of the compacted files.
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
