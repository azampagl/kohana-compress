# Compress

Compress module built for the Kohana PHP framework.  Compresses multiple media files (javascripts or stylesheets) on the fly into one file increasing you website's performance (less requests and bandwidth).  The original concept was conceived by Jonathan Geiger's Asset module.  This module simply takes an alternative (and more advanced) route for the same problem.

## Notes

* If you don't have java on your web server (shared hosting), use the Closure Compiler Service and cssmin.
* Dependencies matter!  Make sure to place things like jquery.js before jquery.ui.js; array('jquery.js', jquery.ui.js').
* If you switch 'gc' on/off, make sure to clean the cache (application/cache and wherever the compressed files are being stored, i.e. DOCROOT/media/cache) before execution.