<?php defined('SYSPATH') or die('No direct access allowed.');
/**
 * Core of the Compress module.  Handles hashing, caching,
 * and the main public methods.
 *
 * Original concept by Jonathan Geiger
 * @see http://github.com/jonathangeiger
 *
 * Special thanks to Richard Willis for ideas and testing
 * @see http://github.com/badsyntax
 *
 * @package    Compress
 * @author     azampagl
 * @license    ISC
 */
abstract class Kohana_Compress {

	// Cache key
	const CACHE_KEY = 'kohana-compress-cache';

	// Cache lifetime
	const CACHE_LIFETIME = PHP_INT_MAX;

	// Cache
	protected static $_cache;
	
	// Instances
	protected static $_instances = array();

	/**
	 * Gets/Sets the cache for this module.
	 *
	 * It is advised that this method be overloaded
	 * for increased performance if you have Kohana's
	 * Cache module.
	 *
	 * @see  http://github.com/kohana/cache
	 *
	 * @param   array     data to store [optional]
	 * @return  mixed
	 */
	protected static function _cache(array $data = NULL)
	{
		// Get
		if ($data == NULL)
		{
			if (isset(Compress::$_cache))
				return Compress::$_cache;

			return Compress::$_cache = Kohana::cache(Compress::CACHE_KEY, NULL, Compress::CACHE_LIFETIME);
		}
		
		// Set
		Compress::$_cache = $data;
		return Kohana::cache(Compress::CACHE_KEY, $data);
	}

	/**
	 * Singleton instance of the class.
	 *
	 * @param   string            name of the instance to load
	 * @param   array [optional]  in-line configuration
	 * @return  Compress
	 */
	public static function instance($name = 'default', array $config = NULL)
	{
		// Check if we already made this instance
		if ( ! isset(Compress::$_instances[$name]))
		{
			if ($config === NULL)
			{
				// Load the config
				$config = Kohana::$config->load('compress')->$name;
			}

			// Create a new Compress instance
			Compress::$_instances[$name] = new Compress($config);
		}

		return Compress::$_instances[$name];
	}

	/**
	 * @var  array  configuration
	 */
	protected $_config;

	/**
	 * @var  Compress_Compressor  compressor
	 */
	protected $_compressor;

	/**
	 * Set config instance and compressor.
	 *
	 * @param   Config   config file
	 * @return  Compress
	 */
	protected function __construct($config)
	{
		$this->_config = $config;

		// What type of compressor?
		$compressor = 'Compress_Compressor_'.$config['compressor'];
		$compressor_config = Kohana::$config->load('compress/compressor')->{$config['compressor']};
		$this->_compressor = new $compressor($compressor_config);
	}

	/**
	 * Main execution flow.
	 *
	 * @param   array    files to be compressed
	 * @param   string   desired out file name
	 * @param   array    additional params
	 * @return  string
	 */
	protected function _execute(array $files, $out, array $args)
	{
		// Get cache
		$cache = Compress::_cache();

		// Hash just the file names for a key
		$key = $this->_hash($files, FALSE);

		// If it's cached, don't re-process
		if (isset($cache[$key]) AND ! $this->_config['gc'])
			return $this->_format($cache[$key]);

		// Determine output file path
		if ($out == NULL)
		{
			if ($this->_config['gc'])
			{
				$out = $this->_out($this->_hash($files), $args['type']);
			}
			else
			{
				$out = $this->_out($key, $args['type']);
			}
		}

		// GC
		$gc = ($this->_config['gc'] AND isset($cache[$key]) AND $out != $cache[$key]);
		if ($gc)
		{
			@unlink($cache[$key]);
		}

		// Compress if new or if GC
		if ((! isset($cache[$key])) OR $gc)
		{
			$this->_compressor->compress($files, $out, $args);
			$cache[$key] = $out;
			Compress::_cache($cache);
		}

		return $this->_format($out);
	}

	/**
	 * Returns a cleaned out format.
	 *
	 * Cleans the absolute path of out file to a relative
	 * one that can be used by HTML::*.
	 *
	 * @param   string    absolute path of out file
	 * @return  array
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
	 * The order of the files MATTERS.  Some might
	 * files might be dependent on others...  Also,
	 * if filemtime is set to true in the configuration
	 * file mod times will be included in the hash.
	 *
	 * @param   array    files
	 * @param   boolean  use filemtime
	 * @return  string
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
	 * @param   string   file
	 * @param   string   extension
	 * @return  string
	 */
	protected function _out($file, $ext)
	{
		return realpath($this->_config['dir']).DIRECTORY_SEPARATOR.$file.'.'.$ext;
	}

	/**
	 * Generate compressed javascript.
	 *
	 * @param   array    files to be compressed
	 * @param   string   desired out file name [optional]
	 * @return  string
	 */
	public function scripts(array $files, $out = NULL)
	{
		return $this->_execute($files, $out, array('type' => 'js'));
	}

	/**
	 * Generate compressed stylesheet.
	 *
	 * @param   array    files to be compressed
	 * @param   string   desired out file name [optional]
	 * @return  string
	 */
	public function styles(array $files, $out = NULL)
	{
		return $this->_execute($files, $out, array('type' => 'css'));
	}

} // End Kohana_Compress
