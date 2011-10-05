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