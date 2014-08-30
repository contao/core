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
var contaoPreviewModal = function(options) {
	var opt = options || {};
	var max = (window.getSize().y - 180).toInt();
	if (!opt.height || opt.height > max)
		opt.height = max;
	var M = new SimpleModal({
		'width': opt.width,
		'hideFooter': true,
		'draggable': false,
		'overlayOpacity': .5,
		'onShow': function() {
			document.body.setStyle('overflow', 'hidden');
		},
		'onHide': function() {
			document.body.setStyle('overflow', 'auto');
			window.location.reload();
		}
	});
	M.show({
		'title': opt.title,
		'contents': '<iframe src="' + opt.url + '" width="100%" height="' + opt.height + '" frameborder="0"></iframe>'
	});
};