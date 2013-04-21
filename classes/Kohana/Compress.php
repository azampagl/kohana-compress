<?php defined('SYSPATH') or die('No direct access allowed.');
/**
 * Core of the Compress module.  Handles hashing, caching,
 * and the main public methods.
 *
 * Original concept by Jonathan Geiger.
 * @see http://github.com/jonathangeiger
 *
 * Special thanks to Richard Willis for ideas and testing.
 * @see http://github.com/badsyntax
 *
 * @package    Compress
 * @author     azampagl
 * @license    ISC
 * @copyright  (c) 2011 - Present Aaron Zampaglione <azampagl@azampagl.com>
 */
abstract class Kohana_Compress {

	// Cache key.
	const CACHE_KEY = 'kohana-compress-cache';

	// Cache lifetime.
	const CACHE_LIFETIME = PHP_INT_MAX;

	// Stores the cache for quick reference.
	protected static $_cache;
	
	// Stores the difference Compress instances.
	protected static $_instances = array();

	/**
	 * Gets/Sets the cache for this module.
	 *
	 * It is advised that this method be overloaded
	 * for increased performance if you have Kohana's
	 * Cache module enabled.
	 *
	 * @see  http://github.com/kohana/cache
	 *
	 * @param  array [optional] 
	 *   Data to store (set).
	 * @return mixed
	 *   An array of the cached items if cache was queried
	 *   or a boolean if a cache was set.
	 */
	protected static function _cache(array $data = NULL)
	{
		// Get.
		if ($data == NULL)
		{
			// Quick return the cache instead of calling Kohana::cache.
			if (isset(Compress::$_cache))
				return Compress::$_cache;

			return Compress::$_cache = Kohana::cache(Compress::CACHE_KEY, NULL, Compress::CACHE_LIFETIME);
		}

		// Set.
		Compress::$_cache = $data;
		return Kohana::cache(Compress::CACHE_KEY, $data);
	}

	/**
	 * Returns a specific instance of the Compress class.
	 *
	 * @param  string
	 *   Name of the instance to load.
	 * @param  array [optional]
	 *   In-line configuration
	 * @return Compress
	 */
	public static function instance($name = 'default', array $config = NULL)
	{
		// Check if we already made this instance.
		if ( ! isset(Compress::$_instances[$name]))
		{
			if ($config === NULL)
			{
				// Load the config.
				$config = Kohana::$config->load('compress')->$name;
			}

			// Create a new Compress instance.
			Compress::$_instances[$name] = new Compress($config);
		}

		return Compress::$_instances[$name];
	}

	/**
	 * Configuration for $this instance.
	 *
	 * @var  array
	 */
	protected $_config;

	/**
	 * Actual compressor implementation.
	 *
	 * @var  Compress_Compressor
	 */
	protected $_compressor;

	/**
	 * Set config instance and compressor.
	 *
	 * @param  Config
	 *   Kohana config object.
	 * @return Compress
	 */
	protected function __construct($config)
	{
		$this->_config = $config;

		// Load the specified type of compressor.
		$compressor = 'Compress_Compressor_'.Text::ucfirst($config['compressor'], '_');
		$compressor_config = Kohana::$config->load('compress/compressor')->{$config['compressor']};
		$this->_compressor = new $compressor($compressor_config);
	}

	/**
	 * Main execution flow.
	 *
	 * @param  array
	 *   Files to be compressed.
	 * @param  string
	 *   Desired out file name.
	 * @param  array
	 *   Additional params.
	 * @return string
	 *   The absolute path to the new file with compressed contents.
	 */
	protected function _execute(array $files, $out, array $args)
	{
		// Get cache.
		$cache = Compress::_cache();

		// Hash just the file names for a cache key.
		$key = $this->_hash($files, FALSE);

		// Determine output file path.
		if ($out == NULL)
		{
			// If gc is on, determine the hash with the filemtimes.
			if ($this->_config['gc'])
			{
				$out = $this->_out($this->_hash($files), $args['type']);
			}
			// Otherwise, the out file name is just the hash key.
			else
			{
				$out = $this->_out($key, $args['type']);
			}
		}

		// If output file is the same, just return the formatted output.
		if (isset($cache[$key]) AND $out == $cache[$key])
			return $this->_format($cache[$key]);

		// Check if we need to garbage collect the old file.
		if ($this->_config['gc'] AND isset($cache[$key]) AND $out != $cache[$key])
		{
			@unlink($cache[$key]);
		}

		$this->_compressor->compress($files, $out, $args);
		$cache[$key] = $out;
		Compress::_cache($cache);

		return $this->_format($out);
	}

	/**
	 * Returns a cleaned out (url) format.
	 *
	 * Cleans the absolute path of out file to a relative
	 * one that can be used by HTML::*.
	 *
	 * @param  string
	 *   Absolute path of out file.
	 * @return array
	 */
	protected function _format($out)
	{
		return str_ireplace(
			array(realpath($this->_config['root']).DIRECTORY_SEPARATOR, '\\'),
			array('', '/'),
			$out);
	}

	/**
	 * Determines a unique hash for the files.
	 *
	 * The order of the files MATTERS.  Some
	 * files might be dependent on others...  Also,
	 * if filemtime is set to true in the configuration
	 * file mod times will be included in the hash.
	 *
	 * @param  array
	 *   The files that are being compressed.
	 * @param  boolean
	 *   Use filemtime.
	 * @return string
	 */
	protected function _hash(array $files, $filemtime = TRUE)
	{
		$files = array_map('strtolower', $files);

		$hash = '';

		// File mod times enabled?
		if ($filemtime AND $this->_config['filemtime'])
		{
			foreach ($files as $file)
			{
				$hash .= $file.filemtime(realpath($file));
			}
		}
		else
		{
			foreach ($files as $file)
			{
				$hash .= $file;
			}
		}

		return sha1($hash);
	}

	/**
	 * Determines the out destination for the new
	 * compressed file.
	 *
	 * @param  string
	 *   File to determine the route of.
	 * @param  string
	 *   Extension of the file.
	 * @return string
	 *   Absolute path of the compressed file.
	 */
	protected function _out($file, $ext)
	{
		return realpath($this->_config['dir']).DIRECTORY_SEPARATOR.$file.'.'.$ext;
	}

	/**
	 * Generate compressed javascript.
	 *
	 * @param  array
	 *   Files to be compressed.
	 * @param  string [optional]
	 *   Desired out file name.
	 * @return string
	 */
	public function scripts(array $files, $out = NULL)
	{
		return $this->_execute($files, $out, array('type' => 'js'));
	}

	/**
	 * Generate compressed stylesheet.
	 *
	 * @param  array
	 *   Files to be compressed.
	 * @param  string [optional]
	 *   Desired out file name.
	 * @return string
	 */
	public function styles(array $files, $out = NULL)
	{
		return $this->_execute($files, $out, array('type' => 'css'));
	}

} // End Kohana_Compress
