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