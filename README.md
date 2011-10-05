# Compress

Compress module built for the Kohana PHP framework.  Compresses multiple media files (javascripts or stylesheets) on the fly into one file increasing you website's performance (less requests and bandwidth).  The original concept was conceived by Jonathan Geiger's Asset module.  This module simply takes an alternative (and more advanced) route for the same problem.


## Requirements

- PHP 5.2+
- Kohana PHP 3.2 (read the docs!)


## Setup

- Enable the compress module in Kohana's bootstrap file.
- Create a writable folder for the compressed files (i.e. DOCROOT/media/cache).


## Configuration

### Core (config/compress.php)

		'root' => DOCROOT,

Where is the "root" of your system?  This should rarely be changed.  This is used to help make the return output string relative so you can use it directly in kohana's HTML helper.

		'dir' => DOCROOT.'media/cache',

Where should the compressed files be stored?  The location of this folder needs to be open to the public so anyone browsing your site can access it.  **Make sure this directory exists beforehand!**

		'gc' => TRUE,

Garbage collect old compressed files?  When new files are compressed, the garbage collection will delete the old (unused) compressed files.  It is recommended to set this value to FALSE for very popular sites (clean the cache manually, if need be).

		'filemtime' => TRUE,

Use file mod times when determining if the files need to be compressed?  If this is enabled, it will check if the files have changed since the last compression.  It is recommended to set this value to FALSE for very popular sites.

		'compressor' => 'yui',

Which compressor to use?  YUI is able to compress javascript and stylesheet files, so it is used by default.

### Compressors (config/compress/compressors.php)

#### YUI

		'java' => 'java',

Where is the java executable located (as if being used on a command-line)?

		'jar' => 'vendor/yui/yui.jar',

Where is the yui jar file located?

#### Closure Application

		'java' => 'java',

Where is the java executable located (as if being used on a command-line)?

		'jar' => 'vendor/closure/closure.jar',

Where is the closure jar file located?

		'compilation_level' => 'SIMPLE_OPTIMIZATIONS',

What level of compilation should be used?  Read Closure documentation!

#### Closure Service

		'url' => 'http://closure-compiler.appspot.com/compile',

URL address of the closure service?  Shouldn't have to be modified.

		'compilation_level' => 'SIMPLE_OPTIMIZATIONS',

What level of compilation should be used?  Read the Closure documentation!

#### cssmin

		'exe' => 'vendor/cssmin/cssmin.php',

What is the location of the cssmin.php file?

		'options' => array( ... ),

Are there any additional options for cssmin?  Read the cssmin documentation!


## Usage

### Normal

A typical use case would be to provide the media files that you need to be compressed.  An output file will be generated for you.

		$result = Compress::instance()->scripts(array('media/js/jquery.js', 'media/js/jquery.ui.js', 'media/js/my-scripts.js'));
		// E.g. <script type="text/javascript" src="/media/cache/0a21d9d23a9fa5e19ea62a49dd5cd85680a2c63f.js">
		echo HTML::script($result);

You can also choose a custom output file.  If an absolute path is not provided, the out file will be put relative to the DOCROOT (where index.php is).

		$result = Compress::instance()->scripts(array('media/js/jquery.js', 'media/js/jquery.ui.js', 'media/js/my-scripts.js'), 'out.js');
		// E.g. <script type="text/javascript" src="/out.js">
		echo HTML::script($result);

Stylesheets work the same way.

		$result = Compress::instance()->styles(array('media/css/reset.css', 'media/css/main.css'));
		echo HTML::style($result);

If you want to provide your own in-line configuration, you can pass it as a second parameter to the instance methods.  Just make sure it follows the same format as the configuration files provided!

		$result = Compress::instance('my_custom_config', array('root' => DOCROOT, ...))->scripts(array('http://mysite.com/media/js1.js', 'http://mysite.com/media/js2.js'));
		echo HTML::script($result);

### Google Closure Service and cssmin

Many shared hosts do not provide java, so Google Closure Service and cssmin are excellent alternatives for javascript and stylesheet compression, respectively.  When using Google Closure Service, pass either the full url to the file or a relative url (regardless, make sure it is available via HTTP/HTTPS).

		$result = Compress::instance('javascripts')->scripts(array('http://mysite.com/media/js1.js', 'http://mysite.com/media/js2.js'));
		echo HTML::script($result);

If you use relative paths, url::base(TRUE, TRUE) will be prepended to the file names.

		$result = Compress::instance('javascripts')->scripts(array('media/js1.js', 'media/js2.js'));
		echo HTML::script($result);

When using cssmin, make sure it is a path relative to the web server.

		$result = Compress::instance('stylesheets')->styles(array('media/css1.css', 'media/css2.css'));
		
		echo HTML::style($result);

## Notes

* If you don't have java on your web server (shared hosting), use the Closure Compiler Service and cssmin.
* Dependencies matter!  Make sure to place things like jquery.js before jquery.ui.js; array('jquery.js', jquery.ui.js').
* If you switch 'gc' on/off, make sure to clean the cache (application/cache and wherever the compressed files are being stored, i.e. DOCROOT/media/cache) before execution.


## Links

[Markdown Reader](http://www.google.com/search?sourceid=chrome&ie=UTF-8&q=markdown+reader)

[Kohana PHP Framework](http://kohanaframework.org/)

[Jonathan Geiger's Original Asset Module](http://github.com/jonathangeiger/kohana-asset)

[Yahoo's YUI Compressor](http://developer.yahoo.com/yui/compressor/)

[Google's Closure Compressor](http://code.google.com/closure/compiler/docs/overview.html)

[cssmin](http://code.google.com/p/cssmin/)