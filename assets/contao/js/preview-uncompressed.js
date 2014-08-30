/**
 * Contao Open Source CMS
 *
 * Copyright (c) 2005-2014 Leo Feyer
 *
 * @package Core
 * @see     https://contao.org
 * @license http://www.gnu.org/licenses/lgpl-3.0.html LGPL
 */

(function($) {
	window.addEvent('domready', function() {
		if (document.createElement('datalist') && window.HTMLDataListElement) {
			$('contao_preview_user').addEvent('keyup', function() {
				var u = $('contao_preview_user'), l = $('userlist');
				if (u.value.length < 2)
					return;
				new Request.JSON({
					onSuccess: function(txt, json) {
						l.empty() && JSON.decode(json).each(function(v) {
							new Element('option', {'value': v}).inject(l);
						});
					}
				}).post({'value': u.value, 'REQUEST_TOKEN': Contao.request_token});
			});
		}
	});
})(document.id);