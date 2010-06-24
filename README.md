Media
====================

Media module built for the Kohana PHP framework.  This will compress multiple media files (javascript and stylesheets) on the fly into one file increasing you website's performance (less requests and bandwidth).  The original concept was conceived by Jonathan Geiger's Asset module.  This module simply takes an alternative route for the same problem.


Requirements
---------------------

- PHP 5.2+
- Kohana PHP framework (read the docs!)


Setup
---------------------

Enable the media module in Kohana's bootstrap file.


Configuration
---------------------

	'root' => DOCROOT,

Where is the "root" of your system?  This should rarely be changed.  This is used to help make the return output string relative so you can use it directly in kohana's html helper.

	'dir' => DOCROOT.'media/cache',

Where should the out files be stored?  The location of this folder needs to be open to the public so anyone browsing your site can access it.

	'lifetime' => 25200,

How long should the cache of the module last?  This really only comes into play if the environment is NOT Kohana::PRODUCTION (refer to the code for details).

	'compressor' => 'yui',

Which compressor to use?  YUI is able to compress javascript and stylesheet files, so it is used by default (and is the only one implemented in the module).


Usage
---------------------

A typical use case would be to provide the media files that you need to be compressed and a output file will be generated for you.

		$result = Media::instance()->scripts(array('media/js/jquery.js', 'media/js/jquery.ui.js', 'media/js/my-scripts.js'));
		html::script($result);

You can also choose a custom output file.  If an absolute path is not provided, the out file will be put relative to the DOCROOT (where index.php is).

		$result = Media::instance()->scripts(array('media/js/jquery.js', 'media/js/jquery.ui.js', 'media/js/my-scripts.js'), 'out.js');
		html::script($result);

Note, that in both cases jquery was put before jquery ui; dependencies matter!

Stylesheets work the same way.

		$result = Media::instance()->styles(array('media/css/reset.css', 'media/css/main.css'));
		html::style($result);

Links
---------------------

[Markdown Reader](http://www.google.com/search?sourceid=chrome&ie=UTF-8&q=markdown+reader)

[Kohana PHP Framework](http://kohanaframework.org/)

[Jonathan Geiger's Original Asset Module](http://github.com/jonathangeiger/kohana-asset)
