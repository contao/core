/**
 * Contao Open Source CMS
 * 
 * Copyright (C) 2005-2013 Leo Feyer
 * 
 * @package Core
 * @link    https://contao.org
 * @license http://www.gnu.org/licenses/lgpl-3.0.html LGPL
 */

// Get the timeout value
var tmo = parseInt(document.getElementById('cron').src.replace(/^[^=]+=/, ''));

// Trigger the cron.php script
setTimeout(function() {
	if (window.jQuery) {
		jQuery.ajax('system/cron/cron.txt', {
			complete: function(xhr) {
				var txt = xhr.responseText || 0;
				if (parseInt(txt) < (Math.round(+new Date()/1000) - tmo)) {
					jQuery.ajax('system/cron/cron.php');
				}
			}
		});
	} else if (window.MooTools) {
		new Request({
			url:'system/cron/cron.txt',
			onComplete: function(txt) {
				if (!txt) txt = 0;
				if (parseInt(txt) < (Math.round(+new Date()/1000) - tmo)) {
					new Request({url:'system/cron/cron.php'}).get();
				}
			}
		}).get();
	}
}, 5000);