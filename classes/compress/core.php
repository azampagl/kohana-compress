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
abstract class Compress_Core {

	// Cache key
	const CACHE_KEY = 'kohana-compress-cache';

	// Cache lifetime
	const CACHE_LIFETIME = PHP_INT_MAX;

	// Instances
	protected static $_instances = array();

	/**
	 * Singleton instance of the class.
	 *
	 * @param   string   name of the instance to load
	 * @return  Compress
	 */
	public static function instance($name = 'default')
	{
		// Check if we already made this instance
		if ( ! isset(Compress::$_instances[$name]))
		{
			// Load the config
			$config = Kohana::config('compress')->$name;

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
		$compressor = 'Compress_Compressor_'.ucfirst($config['compressor']);
		$compressor_config = Kohana::config('Compress/compressors')->{$config['compressor']};
		$this->_compressor = new $compressor($compressor_config);
	}

	/**
	 * Sets/Gets the cache for this module.
	 *
	 * It is advised that this method be overloaded
	 * for increased performance if you have Kohana's
	 * Cache module.
	 *
	 * @see  http://github.com/kohana/cache
	 *
	 * @param   array     data to store [optional]
	 * @return  array
	 * @return  mixed
	 */
	protected function _cache($data = NULL)
	{
		$cache = Kohana::cache(Compress::CACHE_KEY, $data, Compress::CACHE_LIFETIME);
		return ($cache != NULL) ? $cache : array();
	}

	/**
	 * Checks if the compressed file has already been generated.
	 *
	 * It is advised that this method be overloaded
	 * for increased performance if you have Kohana's
	 * Cache module.
	 *
	 * @see  http://github.com/kohana/cache
	 *
	 * @param   string    designated out file
	 * @return  boolean
	 */
	protected function _compressed($out)
	{
		clearstatcache(TRUE, $out);
		return file_exists($out);
	}

	/**
	 * Main execution flow.
	 *
	 * @param   array    files to be compressed
	 * @param   string   desired out file name
	 * @param   boolean  output string formatted relative to DOCROOT?
	 * @param   array    additional params [optional]
	 * @return  array
	 */
	protected function _execute(array $files, $out, $format, array $args = NULL)
	{
		if (Kohana::$environment == Kohana::PRODUCTION)
		{
			$out = ($out == NULL) ? $this->_out($files, $args['type']) : $out;

			if ( ! $this->_compressed($out))
			{
				$this->_compressor->compress($files, $out, $args);
			}
				
			// Clean invalid cache files if GC enabled
			if ($this->_config['gc'])
			{
				// Get a hash of just the file names
				$hash = $this->_hash($files, FALSE);

				$cache = $this->_cache();

				if (isset($cache[$hash]) AND $out != $cache[$hash])
				{
					// Remove the old compressed file
					@unlink($cache[$hash]);
						
					// Reset the cache
					$cache[$hash] = $out;
					$this->_cache($cache);
				}
				elseif ( ! isset($cache[$hash]))
				{
					// Set the cache
					$cache[$hash] = $out;
					$this->_cache($cache);
				}
			}
				
			// Provide a (http) path relative to root or absolute path
			return ($format) ? array($this->_format($out)) : array($out);
		}

		// Not in production, return the files as-is.
		return $files;
	}

	/**
	 * Returns a cleaned out format.
	 *
	 * Cleans the absolute path of out file to a relative
	 * one that can be used by html::*.
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
	 * @param   array    files
	 * @param   string   extension
	 * @return  string
	 */
	protected function _out(array $files, $ext)
	{
		$dir = realpath($this->_config['dir']).DIRECTORY_SEPARATOR;
			
		return $dir.$this->_hash($files).'.'.$ext;
	}

	/**
	 * Generate compressed javascript.
	 *
	 * @param   array    files to be compressed
	 * @param   string   desired out file name [optional]
	 * @param   boolean  output string formatted relative to DOCROOT? [optional]
	 * @return  array
	 */
	public function scripts(array $files, $out = NULL, $format = TRUE)
	{
		return $this->_execute($files, $out, $format, array('type' => 'js'));
	}

	/**
	 * Generate compressed stylesheet.
	 *
	 * @param   array    files to be compressed
	 * @param   string   desired out file name [optional]
	 * @param   boolean  output string formatted relative to DOCROOT? [optional]
	 * @return  array
	 */
	public function styles(array $files, $out = NULL, $format = TRUE)
	{
		return $this->_execute($files, $out, $format, array('type' => 'css'));
	}

} // End Compress_Core
