<?php defined('SYSPATH') or die('No direct access allowed.');
/**
 * Core of the media module.  Handles hashing, caching,
 * and the main public methods.
 *
 * Original concept Jonathan Geiger
 * @see http://github.com/jonathangeiger/kohana-asset
 *
 * @package    Media
 * @category   Core
 * @author     azampagl
 */
abstract class Media_Core {

	// Key for cache
	const CACHE_KEY = 'media-cache';

	// Media instances
	protected static $_instances = array();

	/**
	 * Singleton instance of the Media class.
	 *
	 * @param   string   name of the instance to load
	 * @return  Media
	 */
	public static function instance($name = 'default')
	{
		// Check if we already made this instance
		if ( ! isset(Media::$_instances[$name]))
		{
			// Load the config
			$config = Kohana::config('media')->$name;

			// Create a new Media instance
			Media::$_instances[$name] = new Media($config);
		}

		return Media::$_instances[$name];
	}

	// Config
	protected $_config;

	// Compressor object
	protected $_compressor;

	/**
	 * Set config instance and compressor.
	 *
	 * @param   Config   config file
	 * @return  Media
	 */
	protected function __construct($config)
	{
		$this->_config = $config;

		// What type of compressor?
		$compressor = 'Media_Compressor_'.ucfirst($config['compressor']);

		// Generate compressor and pass config to it
		$this->_compressor = new $compressor(Kohana::config('media/compressors')->{$config['compressor']});
	}

	/**
	 * Checks to see if the cache has already been generated.
	 *
	 * If the current system is NOT in production, this
	 * function will do an additional check to see if any
	 * of the files have been modified.
	 *
	 * @param   array    files to be cached
	 * @param   string   designated out file
	 * @return  string
	 */
	protected function _cached(array $files, $out)
	{
		// Are we in production?  Do filemod check
		if (Kohana::$environment != Kohana::PRODUCTION)
		{
			$cache = Kohana::cache(Media::CACHE_KEY);
			$expired = FALSE;

			foreach ($files as $file)
			{
				if (isset($cache[$file]))
				{
					// Have we been modified since last cache?
					if ($cache[$file] < filemtime($file))
					{
						$cache[$file] = filemtime($file);
						$expired = TRUE;
					}
				}
				// It wasn't in the cache, add it
				else
				{
					$cache[$file] = filemtime($file);
				}
			}

			Kohana::cache(Media::CACHE_KEY, $cache, $this->_config['lifetime']);

			if ($expired)
			{
				return FALSE;
			}
		}

		// If cache module is available, add it here
		//  to see if out file exists instead of checking
		//  for it on disk.  Otherwise use the file check below.

		return file_exists($out);
	}

	/**
	 * Cleans the absolute path of out file to a relative
	 * one that can be used by html::*.
	 *
	 * @param   string    absolute path of out file
	 * @return  string
	 */
	protected function _format($out)
	{
		return str_replace(array($this->_config['root'], DIRECTORY_SEPARATOR), array('', '/'), $out);
	}

	/**
	 * Determines a unique hash for the files.
	 *
	 * The order of the files MATTERS.  Some might
	 * files might be dependent on others...
	 *
	 * @param   array    files
	 * @return  string
	 */
	protected function _hash(array $files)
	{
		$files = array_map('strtolower', $files);

		$hash = '';
		foreach ($files as $file)
		{
			$hash .= $file;
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
		$dir = $this->_config['dir'];

		// Make sure the directory exists
		if ( ! file_exists($dir))
		{
			mkdir($dir, 0777, TRUE);
		}
			
		return $dir.DIRECTORY_SEPARATOR.$this->_hash($files).'.'.$ext;
	}

	/**
	 * Generate compressed javascript.
	 *
	 * @param   array    files to be compressed
	 * @param   string   desired out file (absolute path) [optional]
	 * @return  string   the compressed out file
	 */
	public function javascripts(array $files, $out = NULL)
	{
		$out = ($out == NULL) ? $this->_out($files, 'js') : $out;

		if ( ! $this->_cached($files, $out))
		{
			$this->_compressor->compress($files, $out, array('type' => 'js'));
		}

		// We need to provide a path relative to root, NOT including it
		return $this->_format($out);
	}

	/**
	 * Generate compressed stylesheet.
	 *
	 * @param   array    files to be compressed
	 * @param   string   desired out file (absolute path) [optional]
	 * @return  string   the compressed out file
	 */
	public function stylesheets(array $files, $out = NULL)
	{
		$out = ($out == NULL) ? $this->_out($files, 'css') : $out;

		if ( ! $this->_cached($files, $out))
		{
			$this->_compressor->compress($files, $out, array('type' => 'css'));
		}

		// We need to provide a path relative to root, NOT including it
		return $this->_format($out);
	}

} // End Media_Core
