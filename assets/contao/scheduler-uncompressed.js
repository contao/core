/**
 * Contao Open Source CMS
 * Copyright (C) 2005-2012 Leo Feyer
 *
 * Formerly known as TYPOlight Open Source CMS.
 *
 * This program is free software: you can redistribute it and/or
 * modify it under the terms of the GNU Lesser General Public
 * License as published by the Free Software Foundation, either
 * version 3 of the License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU
 * Lesser General Public License for more details.
 *
 * You should have received a copy of the GNU Lesser General Public
 * License along with this program. If not, please visit the Free
 * Software Foundation website at <http://www.gnu.org/licenses/>.
 *
 * PHP version 5.3
 * @copyright  Leo Feyer 2005-2012
 * @author     Leo Feyer <http://www.contao.org>
 * @package    System
 * @license    LGPL
 */
setTimeout(function() {
	if (window.jQuery) {
		jQuery.ajax('system/cron/cron.txt', {
			complete: function(xhr) {
				var txt = xhr.responseText || 0;
				if (parseInt(txt) < (Math.round(+new Date()/1000) - 300)) {
					jQuery.ajax('system/cron/cron.php');
				}
			}
		});
	} else if (window.MooTools) {
		new Request({
			url:'system/cron/cron.txt',
			onComplete: function(txt) {
				if (!txt) txt = 0;
				if (parseInt(txt) < (Math.round(+new Date()/1000) - 300)) {
					new Request({url:'system/cron/cron.php'}).get();
				}
			}
		}).get();
	}
}, 5000);