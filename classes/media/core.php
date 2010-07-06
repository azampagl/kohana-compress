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
	 * @param   array    files to be cached
	 * @param   string   designated out file
	 * @return  boolean
	 */
	protected function _cached(array $files, $out)
	{
		// If cache module is available, add it here
		//  to see if out file exists instead of checking
		//  for it on disk.  Otherwise use the file check below.

		return file_exists($out);
	}

	/**
	 * Returns a cleaned out format.
	 *
	 * Cleans the absolute path of out file to a relative
	 * one that can be used by html::*.  The output is
	 * also put into array so the output, regardless of
	 * the current environment, is normalized.
	 *
	 * @param   string    absolute path of out file
	 * @return  array
	 */
	protected function _format($out)
	{
		return array(str_replace(array(strtolower($this->_config['root']), '\\'), array('', '/'), $out));
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
	 * @return  string
	 */
	protected function _hash(array $files)
	{
		$files = array_map('strtolower', $files);

		$hash = '';

		// File mod times enabled?
		if ($this->_config['filemtime'])
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
		$dir = strtolower($this->_config['dir'].DIRECTORY_SEPARATOR);
		$ext = strtolower($ext);

		// Make sure the directory exists
		if ( ! file_exists($dir))
		{
			mkdir($dir, 0777, TRUE);
		}
			
		return $dir.$this->_hash($files).'.'.$ext;
	}

	/**
	 * Generate compressed javascript.
	 *
	 * @param   array    files to be compressed
	 * @param   string   desired out file (absolute path or rel to root) [optional]
	 * @return  array
	 */
	public function scripts(array $files, $out = NULL)
	{
		if (Kohana::$environment == Kohana::PRODUCTION)
		{
			$out = ($out == NULL) ? $this->_out($files, 'js') : $out;

			if ( ! $this->_cached($files, $out))
			{
				$this->_compressor->compress($files, $out, array('type' => 'js'));
			}

			// We need to provide a path relative to root, NOT including it
			return $this->_format($out);
		}

		// We're not in production, return the files as-is.
		return $files;
	}

	/**
	 * Generate compressed stylesheet.
	 *
	 * @param   array    files to be compressed
	 * @param   string   desired out file (absolute path or rel to root) [optional]
	 * @return  array
	 */
	public function styles(array $files, $out = NULL)
	{
		if (Kohana::$environment == Kohana::PRODUCTION)
		{
			$out = ($out == NULL) ? $this->_out($files, 'css') : $out;
				
			if ( ! $this->_cached($files, $out))
			{
				$this->_compressor->compress($files, $out, array('type' => 'css'));
			}
				
			// We need to provide a path relative to root, NOT including it
			return $this->_format($out);
		}

		// We're not in production, return the files as-is.
		return $files;
	}

} // End Media_Core
