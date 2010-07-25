# Media

Media module built for the Kohana PHP framework.  This will compress multiple media files (javascript and stylesheets) on the fly into one file increasing you website's performance (less requests and bandwidth).  The original concept was conceived by Jonathan Geiger's Asset module.  This module simply takes an alternative route for the same problem.


## Requirements

- PHP 5.2+
- Kohana PHP 3.x (read the docs!)


## Setup

- Enable the media module in Kohana's bootstrap file.
- Properly set the Kohana::$environment variable in the bootstrap file.

## Configuration

### Core (config/media.php)

	'root' => DOCROOT,

Where is the "root" of your system?  This should rarely be changed.  This is used to help make the return output string relative so you can use it directly in kohana's html helper.

	'dir' => DOCROOT.'media/cache',

Where should the out files be stored?  The location of this folder needs to be open to the public so anyone browsing your site can access it.

	'filemtime' => TRUE,

Use file mod times when determining if the files are already cached?  If this is enabled, it will check if the file have changed since the last compression/cache.  It is recommended to set this value to FALSE for very popular sites.

	'compressor' => 'yui',

Which compressor to use?  YUI is able to compress javascript and stylesheet files, so it is used by default.  Google Closure (application and service) are implemented as well, and has been known to have a higher compression ratio than YUI (doesn't support stylesheets).

### Compressors (config/media/compressors.php)

## Usage

### Normal

If the Kohana::$environment variable is set to something other than Kohana::PRODUCTION, the files sent will be immediately returned.  The result of both Media::scripts() and Media::styles() will always be an array of length >= 1.

A typical use case would be to provide the media files that you need to be compressed and a output file will be generated for you.

		$result = Media::instance()->scripts(array('media/js/jquery.js', 'media/js/jquery.ui.js', 'media/js/my-scripts.js'));
		foreach ($result as $file) {echo html::script($file)};

You can also choose a custom output file.  If an absolute path is not provided, the out file will be put relative to the DOCROOT (where index.php is).

		$result = Media::instance()->scripts(array('media/js/jquery.js', 'media/js/jquery.ui.js', 'media/js/my-scripts.js'), 'out.js');
		foreach ($result as $file) {echo html::script($file);};

Note, that in both cases jquery was put before jquery ui; dependencies matter!

Stylesheets work the same way.

		$result = Media::instance()->styles(array('media/css/reset.css', 'media/css/main.css'));
		foreach ($result as $file) {echo html::style($file);};

### Google Closure Service


## Notes
* If you don't have java on your web server (shared hosting), use the Closure Service compiler.


## Links

[Markdown Reader](http://www.google.com/search?sourceid=chrome&ie=UTF-8&q=markdown+reader)

[Kohana PHP Framework](http://kohanaframework.org/)

[Jonathan Geiger's Original Asset Module](http://github.com/jonathangeiger/kohana-asset)
