# Compress

Compress module built for the Kohana PHP framework.  This will compress multiple media files (javascript and stylesheets) on the fly into one file increasing you website's performance (less requests and bandwidth).  The original concept was conceived by Jonathan Geiger's Asset module.  This module simply takes an alternative (and more advanced) route for the same problem.


## Requirements

- PHP 5.2+
- Kohana PHP 3.x (read the docs!)


## Setup

- Enable the compress module in Kohana's bootstrap file.
- Properly set the Kohana::$environment variable in the bootstrap file.
- Create a writable folder for the compressed files.


## Configuration

### Core (config/compress.php)

		'force_exec' => FALSE,

Force execution (compress files) regardless of the Kohana::$environment?

		'root' => DOCROOT,

Where is the "root" of your system?  This should rarely be changed.  This is used to help make the return output string relative so you can use it directly in kohana's html helper.

		'dir' => DOCROOT.'media/cache',

Where should the compressed files be stored?  The location of this folder needs to be open to the public so anyone browsing your site can access it.  **Make sure this directory exists beforehand!**

		'gc' => TRUE,

Garbage collect old compressed files?  When new files are compressed, the garbage collection will delete the old (unused) compressed files.  It is recommended to set this value to FALSE for very popular sites (clean the cache manually, if need be).

		'filemtime' => TRUE,

Use file mod times when determining if the files need to be compressed?  If this is enabled, it will check if the files have changed since the last compression.  It is recommended to set this value to FALSE for very popular sites.

		'compressor' => 'yui',

Which compressor to use?  YUI is able to compress javascript and stylesheet files, so it is used by default.  Google Closure (application and service) is implemented as well, and has been known to have a higher compression ratio than YUI (doesn't support stylesheets).

### Compressors (config/compress/compressors.php)

#### YUI

		'java' => 'java',

Where is the java executable located (as if being used on a command-line)?

		'jar' => 'vendor/yui/yuicompressor-x.y.z.jar',

Where is the compressor jar file located?

#### Closure Application

		'java' => 'java',

Where is the java executable located (as if being used on a command-line)?

		'jar' => 'vendor/closure/closure-compiler-x.jar',

Where is the compressor jar file located?

		'compilation_level' => 'SIMPLE_OPTIMIZATIONS',

What level of compilation should be used?  Read Closure documentation!

#### Closure Service

		'url' => 'http://closure-compiler.appspot.com/compile',

URL address of the closure service?  Shouldn't have to be modified.

		'compilation_level' => 'SIMPLE_OPTIMIZATIONS',

What level of compilation should be used?  Read Closure documentation!


## Usage

### Normal

If the Kohana::$environment variable is set to something other than Kohana::PRODUCTION and 'force_exec' is set to FALSE, the files sent will be immediately returned.  The result of both Compress::scripts() and Compress::styles() will always be an array of length >= 1.

A typical use case would be to provide the media files that you need to be compressed and an output file will be generated for you.

		$result = Compress::instance()->scripts(array('media/js/jquery.js', 'media/js/jquery.ui.js', 'media/js/my-scripts.js'));
		echo implode("\n", array_map('HTML::script', $result)), "\n";

You can also choose a custom output file.  If an absolute path is not provided, the out file will be put relative to the DOCROOT (where index.php is).

		$result = Compress::instance()->scripts(array('media/js/jquery.js', 'media/js/jquery.ui.js', 'media/js/my-scripts.js'), 'out.js');
		echo implode("\n", array_map('HTML::script', $result)), "\n";

If you want the absolute path of the compressed file, use FALSE for the $format parameter.

		$result = Compress::instance()->scripts(array('media/js/jquery.js', 'media/js/jquery.ui.js', 'media/js/my-scripts.js'), 'out.js', FALSE);
		echo implode("\n", array_map('HTML::script', $result)), "\n";

Stylesheets work the same way.

		$result = Compress::instance()->styles(array('media/css/reset.css', 'media/css/main.css'));
		echo implode("\n", array_map('HTML::style', $result)), "\n";

### Google Closure Service

Many shared hosts do not provide java, so the Google Closure Service is an excellent alternative for compression.  In the module, you can either pass the full url to the javascript file or a relative url (regardless, make sure it is available via HTTP/HTTPS).

		$result = Compress::instance('closure')->scripts(array('http://mysite.com/media/js1.js', 'http://mysite.com/media/js2.js'));
		echo implode("\n", array_map('HTML::script', $result)), "\n";

If you use relative paths, url::base(TRUE, TRUE) will be prepended to the file names.

		$result = Compress::instance('closure')->scripts(array('media/js1.js', 'media/js2.js'));
		echo implode("\n", array_map('HTML::script', $result)), "\n";


## Notes

* If you don't have java on your web server (shared hosting), use the Closure Service compiler.
* Dependencies matter!  Make sure to place things like jquery.js before jquery.ui.js; array('jquery.js', jquery.ui.js').
* If you switch 'gc' on/off, make sure to clean the cache before execution.


## Links

[Markdown Reader](http://www.google.com/search?sourceid=chrome&ie=UTF-8&q=markdown+reader)

[Kohana PHP Framework](http://kohanaframework.org/)

[Jonathan Geiger's Original Asset Module](http://github.com/jonathangeiger/kohana-asset)

[Yahoo's YUI Compressor](http://developer.yahoo.com/yui/compressor/)

[Google's Closure Compressor](http://code.google.com/closure/compiler/docs/overview.html)
