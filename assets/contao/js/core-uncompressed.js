/**
 * Contao Open Source CMS
 *
 * Copyright (c) 2005-2016 Leo Feyer
 *
 * @license LGPL-3.0+
 */


/**
 * Provide methods to handle Ajax requests.
 *
 * @author Leo Feyer <https://github.com/leofeyer>
 */
var AjaxRequest =
{
	/**
	 * The theme path
	 * @member {string}
	 */
	themePath: Contao.script_url + 'system/themes/' + Contao.theme + '/images/',

	/**
	 * Toggle the navigation menu
	 *
	 * @param {object} el The DOM element
	 * @param {string} id The ID of the menu item
	 *
	 * @returns {boolean}
	 */
	toggleNavigation: function(el, id) {
		el.blur();

		var item = $(id),
			image = $(el).getFirst('img');

		if (item) {
			if (item.getStyle('display') == 'none') {
				item.setStyle('display', null);
				image.src = AjaxRequest.themePath + 'modMinus.gif';
				$(el).store('tip:title', Contao.lang.collapse);
				new Request.Contao().post({'action':'toggleNavigation', 'id':id, 'state':1, 'REQUEST_TOKEN':Contao.request_token});
			} else {
				item.setStyle('display', 'none');
				image.src = AjaxRequest.themePath + 'modPlus.gif';
				$(el).store('tip:title', Contao.lang.expand);
				new Request.Contao().post({'action':'toggleNavigation', 'id':id, 'state':0, 'REQUEST_TOKEN':Contao.request_token});
			}
			return false;
		}

		new Request.Contao({
			evalScripts: true,
			onRequest: AjaxRequest.displayBox(Contao.lang.loading + ' …'),
			onSuccess: function(txt) {
				var li = new Element('li', {
					'id': id,
					'class': 'tl_parent',
					'html': txt,
					'styles': {
						'display': 'inline'
					}
				}).inject($(el).getParent('li'), 'after');

				// Update the referer ID
				li.getElements('a').each(function(el) {
					el.href = el.href.replace(/&ref=[a-f0-9]+/, '&ref=' + Contao.referer_id);
				});

				$(el).store('tip:title', Contao.lang.collapse);
				image.src = AjaxRequest.themePath + 'modMinus.gif';
				AjaxRequest.hideBox();

				// HOOK
				window.fireEvent('ajax_change');
   			}
		}).post({'action':'loadNavigation', 'id':id, 'state':1, 'REQUEST_TOKEN':Contao.request_token});

		return false;
	},

	/**
	 * Toggle the site structure tree
	 *
	 * @param {object} el    The DOM lement
	 * @param {string} id    The ID of the target element
	 * @param {int}    level The indentation level
	 * @param {int}    mode  The insert mode
	 *
	 * @returns {boolean}
	 */
	toggleStructure: function (el, id, level, mode) {
		el.blur();

		var item = $(id),
			image = $(el).getFirst('img');

		if (item) {
			if (item.getStyle('display') == 'none') {
				item.setStyle('display', null);
				image.src = AjaxRequest.themePath + 'folMinus.gif';
				$(el).store('tip:title', Contao.lang.collapse);
				new Request.Contao({field:el}).post({'action':'toggleStructure', 'id':id, 'state':1, 'REQUEST_TOKEN':Contao.request_token});
			} else {
				item.setStyle('display', 'none');
				image.src = AjaxRequest.themePath + 'folPlus.gif';
				$(el).store('tip:title', Contao.lang.expand);
				new Request.Contao({field:el}).post({'action':'toggleStructure', 'id':id, 'state':0, 'REQUEST_TOKEN':Contao.request_token});
			}
			return false;
		}

		new Request.Contao({
			field: el,
			evalScripts: true,
			onRequest: AjaxRequest.displayBox(Contao.lang.loading + ' …'),
			onSuccess: function(txt) {
				var li = new Element('li', {
					'id': id,
					'class': 'parent',
					'styles': {
						'display': 'inline'
					}
				});

				new Element('ul', {
					'class': 'level_' + level,
					'html': txt
				}).inject(li, 'bottom');

				if (mode == 5) {
					li.inject($(el).getParent('li'), 'after');
				} else {
					var folder = false,
						parent = $(el).getParent('li'),
						next;

					while (typeOf(parent) == 'element' && (next = parent.getNext('li'))) {
						parent = next;
						if (parent.hasClass('tl_folder')) {
							folder = true;
							break;
						}
					}

					if (folder) {
						li.inject(parent, 'before');
					} else {
						li.inject(parent, 'after');
					}
				}

				// Update the referer ID
				li.getElements('a').each(function(el) {
					el.href = el.href.replace(/&ref=[a-f0-9]+/, '&ref=' + Contao.referer_id);
				});

				$(el).store('tip:title', Contao.lang.collapse);
				image.src = AjaxRequest.themePath + 'folMinus.gif';
				window.fireEvent('structure');
				AjaxRequest.hideBox();

				// HOOK
				window.fireEvent('ajax_change');
   			}
		}).post({'action':'loadStructure', 'id':id, 'level':level, 'state':1, 'REQUEST_TOKEN':Contao.request_token});

		return false;
	},

	/**
	 * Toggle the file manager tree
	 *
	 * @param {object} el     The DOM element
	 * @param {string} id     The ID of the target element
	 * @param {string} folder The folder's path
	 * @param {int}    level  The indentation level
	 *
	 * @returns {boolean}
	 */
	toggleFileManager: function (el, id, folder, level) {
		el.blur();

		var item = $(id),
			image = $(el).getFirst('img');

		if (item) {
			if (item.getStyle('display') == 'none') {
				item.setStyle('display', null);
				image.src = AjaxRequest.themePath + 'folMinus.gif';
				$(el).store('tip:title', Contao.lang.collapse);
				new Request.Contao({field:el}).post({'action':'toggleFileManager', 'id':id, 'state':1, 'REQUEST_TOKEN':Contao.request_token});
			} else {
				item.setStyle('display', 'none');
				image.src = AjaxRequest.themePath + 'folPlus.gif';
				$(el).store('tip:title', Contao.lang.expand);
				new Request.Contao({field:el}).post({'action':'toggleFileManager', 'id':id, 'state':0, 'REQUEST_TOKEN':Contao.request_token});
			}
			return false;
		}

		new Request.Contao({
			field: el,
			evalScripts: true,
			onRequest: AjaxRequest.displayBox(Contao.lang.loading + ' …'),
			onSuccess: function(txt) {
				var li = new Element('li', {
					'id': id,
					'class': 'parent',
					'styles': {
						'display': 'inline'
					}
				});

				new Element('ul', {
					'class': 'level_' + level,
					'html': txt
				}).inject(li, 'bottom');

				li.inject($(el).getParent('li'), 'after');

				// Update the referer ID
				li.getElements('a').each(function(el) {
					el.href = el.href.replace(/&ref=[a-f0-9]+/, '&ref=' + Contao.referer_id);
				});

				$(el).store('tip:title', Contao.lang.collapse);
				image.src = AjaxRequest.themePath + 'folMinus.gif';
				AjaxRequest.hideBox();

				// HOOK
				window.fireEvent('ajax_change');
   			}
		}).post({'action':'loadFileManager', 'id':id, 'level':level, 'folder':folder, 'state':1, 'REQUEST_TOKEN':Contao.request_token});

		return false;
	},

	/**
	 * Toggle the page tree input field
	 *
	 * @param {object} el    The DOM element
	 * @param {string} id    The ID of the target element
	 * @param {string} field The field name
	 * @param {string} name  The Ajax field name
	 * @param {int}    level The indentation level
	 *
	 * @returns {boolean}
	 */
	togglePagetree: function (el, id, field, name, level) {
		el.blur();
		Backend.getScrollOffset();

		var item = $(id),
			image = $(el).getFirst('img');

		if (item) {
			if (item.getStyle('display') == 'none') {
				item.setStyle('display', null);
				image.src = AjaxRequest.themePath + 'folMinus.gif';
				$(el).store('tip:title', Contao.lang.collapse);
				new Request.Contao({field:el}).post({'action':'togglePagetree', 'id':id, 'state':1, 'REQUEST_TOKEN':Contao.request_token});
			} else {
				item.setStyle('display', 'none');
				image.src = AjaxRequest.themePath + 'folPlus.gif';
				$(el).store('tip:title', Contao.lang.expand);
				new Request.Contao({field:el}).post({'action':'togglePagetree', 'id':id, 'state':0, 'REQUEST_TOKEN':Contao.request_token});
			}
			return false;
		}

		new Request.Contao({
			field: el,
			evalScripts: true,
			onRequest: AjaxRequest.displayBox(Contao.lang.loading + ' …'),
			onSuccess: function(txt) {
				var li = new Element('li', {
					'id': id,
					'class': 'parent',
					'styles': {
						'display': 'inline'
					}
				});

				new Element('ul', {
					'class': 'level_' + level,
					'html': txt
				}).inject(li, 'bottom');

				li.inject($(el).getParent('li'), 'after');

				// Update the referer ID
				li.getElements('a').each(function(el) {
					el.href = el.href.replace(/&ref=[a-f0-9]+/, '&ref=' + Contao.referer_id);
				});

				$(el).store('tip:title', Contao.lang.collapse);
				image.src = AjaxRequest.themePath + 'folMinus.gif';
				AjaxRequest.hideBox();

				// HOOK
				window.fireEvent('ajax_change');
   			}
		}).post({'action':'loadPagetree', 'id':id, 'level':level, 'field':field, 'name':name, 'state':1, 'REQUEST_TOKEN':Contao.request_token});

		return false;
	},

	/**
	 * Toggle the file tree input field
	 *
	 * @param {object} el     The DOM element
	 * @param {string} id     The ID of the target element
	 * @param {string} folder The folder name
	 * @param {string} field  The field name
	 * @param {string} name   The Ajax field name
	 * @param {int}    level  The indentation level
	 *
	 * @returns {boolean}
	 */
	toggleFiletree: function (el, id, folder, field, name, level) {
		el.blur();
		Backend.getScrollOffset();

		var item = $(id),
			image = $(el).getFirst('img');

		if (item) {
			if (item.getStyle('display') == 'none') {
				item.setStyle('display', null);
				image.src = AjaxRequest.themePath + 'folMinus.gif';
				$(el).store('tip:title', Contao.lang.collapse);
				new Request.Contao({field:el}).post({'action':'toggleFiletree', 'id':id, 'state':1, 'REQUEST_TOKEN':Contao.request_token});
			} else {
				item.setStyle('display', 'none');
				image.src = AjaxRequest.themePath + 'folPlus.gif';
				$(el).store('tip:title', Contao.lang.expand);
				new Request.Contao({field:el}).post({'action':'toggleFiletree', 'id':id, 'state':0, 'REQUEST_TOKEN':Contao.request_token});
			}
			return false;
		}

		new Request.Contao({
			field: el,
			evalScripts: true,
			onRequest: AjaxRequest.displayBox(Contao.lang.loading + ' …'),
			onSuccess: function(txt) {
				var li = new Element('li', {
					'id': id,
					'class': 'parent',
					'styles': {
						'display': 'inline'
					}
				});

				new Element('ul', {
					'class': 'level_' + level,
					'html': txt
				}).inject(li, 'bottom');

				li.inject($(el).getParent('li'), 'after');

				// Update the referer ID
				li.getElements('a').each(function(el) {
					el.href = el.href.replace(/&ref=[a-f0-9]+/, '&ref=' + Contao.referer_id);
				});

				$(el).store('tip:title', Contao.lang.collapse);
				image.src = AjaxRequest.themePath + 'folMinus.gif';
				AjaxRequest.hideBox();

				// HOOK
				window.fireEvent('ajax_change');
   			}
		}).post({'action':'loadFiletree', 'id':id, 'folder':folder, 'level':level, 'field':field, 'name':name, 'state':1, 'REQUEST_TOKEN':Contao.request_token});

		return false;
	},

	/**
	 * Toggle subpalettes in edit mode
	 *
	 * @param {object} el    The DOM element
	 * @param {string} id    The ID of the target element
	 * @param {string} field The field name
	 */
	toggleSubpalette: function (el, id, field) {
		el.blur();
		var item = $(id);

		if (item) {
			if (!el.value) {
				el.value = 1;
				el.checked = 'checked';
				item.setStyle('display', null);
				item.getElements('[data-required]').each(function(el) {
					el.set('required', '').set('data-required', null);
				});
				new Request.Contao({field:el}).post({'action':'toggleSubpalette', 'id':id, 'field':field, 'state':1, 'REQUEST_TOKEN':Contao.request_token});
			} else {
				el.value = '';
				el.checked = '';
				item.setStyle('display', 'none');
				item.getElements('[required]').each(function(el) {
					el.set('required', null).set('data-required', '');
				});
				new Request.Contao({field:el}).post({'action':'toggleSubpalette', 'id':id, 'field':field, 'state':0, 'REQUEST_TOKEN':Contao.request_token});
			}
			return;
		}

		new Request.Contao({
			field: el,
			evalScripts: false,
			onRequest: AjaxRequest.displayBox(Contao.lang.loading + ' …'),
			onSuccess: function(txt, json) {
				var div = new Element('div', {
					'id': id,
					'html': txt,
					'styles': {
						'display': 'block'
					}
				}).inject($(el).getParent('div').getParent('div'), 'after');

				// Execute scripts after the DOM has been updated
				if (json.javascript) {

					// Use Asset.javascript() instead of document.write() to load a
					// JavaScript file and re-execute the code after it has been loaded
					document.write = function(str) {
						var src = '';
						str.replace(/<script src="([^"]+)"/i, function(all, match){
							src = match;
						});
						src && Asset.javascript(src, {
							onLoad: function() {
								Browser.exec(json.javascript);
							}
						});
					};

					Browser.exec(json.javascript);
				}

				el.value = 1;
				el.checked = 'checked';

				// Update the referer ID
				div.getElements('a').each(function(el) {
					el.href = el.href.replace(/&ref=[a-f0-9]+/, '&ref=' + Contao.referer_id);
				});

				AjaxRequest.hideBox();

				// HOOK
				window.fireEvent('subpalette'); // Backwards compatibility
				window.fireEvent('ajax_change');
			}
		}).post({'action':'toggleSubpalette', 'id':id, 'field':field, 'load':1, 'state':1, 'REQUEST_TOKEN':Contao.request_token});
	},

	/**
	 * Toggle the visibility of an element
	 *
	 * @param {object} el    The DOM element
	 * @param {string} id    The ID of the target element
	 * @param {string} table The table name
	 *
	 * @returns {boolean}
	 */
	toggleVisibility: function(el, id, table) {
		el.blur();

		var img = null,
			image = $(el).getFirst('img'),
			published = (image.get('data-state') == 1),
			div = el.getParent('div'),
			index, next, icon, icond, pa;

		// Backwards compatibility
		if (image.get('data-state') === null) {
			published = (image.src.indexOf('invisible') == -1);
			console.warn('Using a visibility toggle without a "data-state" attribute is deprecated. Please adjust your Contao DCA file.');
		}

		// Find the icon depending on the view (tree view, list view, parent view)
		if (div.hasClass('tl_right')) {
			img = div.getPrevious('div').getElement('img');
		} else if (div.hasClass('tl_listing_container')) {
			img = el.getParent('td').getPrevious('td').getFirst('div.list_icon');
			if (img === null) { // comments
				img = el.getParent('td').getPrevious('td').getElement('div.cte_type');
			}
			if (img === null) { // showColumns
				img = el.getParent('tr').getFirst('td').getElement('div.list_icon_new');
			}
		} else if (next = div.getNext('div')) {
			if (next.hasClass('cte_type')) {
				img = next;
			}
			if (img === null) { // newsletter recipients
				img = next.getFirst('div.list_icon');
			}
		}

		// Change the icon
		if (img !== null) {
			// Tree view
			if (img.nodeName.toLowerCase() == 'img') {
				if (img.getParent('ul.tl_listing').hasClass('tl_tree_xtnd')) {
					icon = img.get('data-icon');
					icond = img.get('data-icon-disabled');

					// Backwards compatibility
					if (icon === null) {
						icon = img.src.replace(/(.*)\/([a-z0-9]+)_?\.(gif|png|jpe?g|svg)$/, '$1/$2.$3');
						console.warn('Using a row icon without a "data-icon" attribute is deprecated. Please adjust your Contao DCA file.');
					}
					if (icond === null) {
						icond = img.src.replace(/(.*)\/([a-z0-9]+)_?\.(gif|png|jpe?g|svg)$/, '$1/$2_.$3');
						console.warn('Using a row icon without a "data-icon-disabled" attribute is deprecated. Please adjust your Contao DCA file.');
					}

					// Prepend the theme path
					if (icon.indexOf('/') == -1) {
						icon = AjaxRequest.themePath + icon;
					}
					if (icond.indexOf('/') == -1) {
						icond = AjaxRequest.themePath + icond;
					}

					img.src = !published ? icon : icond;
				} else {
					pa = img.getParent('a');

					if (pa && pa.href.indexOf('do=feRedirect') == -1) {
						if (next = pa.getNext('a')) {
							img = next.getFirst('img');
						} else {
							img = new Element('img'); // no icons used (see #2286)
						}
					}

					icon = img.get('data-icon');
					icond = img.get('data-icon-disabled');

					// Backwards compatibility
					if (icon === null) {
						index = img.src.replace(/.*_([0-9])\.(gif|png|jpe?g|svg)/, '$1');
						icon = img.src.replace(/_[0-9]\.(gif|png|jpe?g|svg)/, ((index.toInt() == 1) ? '' : '_' + (index.toInt() - 1)) + '.$1').split(/[\\/]/).pop();
						console.warn('Using a row icon without a "data-icon" attribute is deprecated. Please adjust your Contao DCA file.');
					}
					if (icond === null) {
						index = img.src.replace(/.*_([0-9])\.(gif|png|jpe?g|svg)/, '$1');
						icond = img.src.replace(/(_[0-9])?\.(gif|png|jpe?g|svg)/, ((index == img.src) ? '_1' : '_' + (index.toInt() + 1)) + '.$2').split(/[\\/]/).pop();
						console.warn('Using a row icon without a "data-icon-disabled" attribute is deprecated. Please adjust your Contao DCA file.');
					}

					// Prepend the theme path
					if (icon.indexOf('/') == -1) {
						icon = AjaxRequest.themePath + icon;
					}
					if (icond.indexOf('/') == -1) {
						icond = AjaxRequest.themePath + icond;
					}

					img.src = !published ? icon : icond;
				}
			}
			// Parent view
			else if (img.hasClass('cte_type')) {
				if (!published) {
					img.addClass('published');
					img.removeClass('unpublished');
				} else {
					img.addClass('unpublished');
					img.removeClass('published');
				}
			}
			// List view
			else {
				icon = img.get('data-icon');
				icond = img.get('data-icon-disabled');

				// Backwards compatibility
				if (icon === null) {
					icon = img.getStyle('background-image').replace(/(.*)\/([a-z0-9]+)_?\.(gif|png|jpe?g|svg)\);?$/, '$1/$2.$2');
					console.warn('Using a row icon without a "data-icon" attribute is deprecated. Please adjust your Contao DCA file.');
				}
				if (icond === null) {
					icond = img.getStyle('background-image').replace(/(.*)\/([a-z0-9]+)_?\.(gif|png|jpe?g|svg)\);?$/, '$1/$2_.$3');
					console.warn('Using a row icon without a "data-icon-disabled" attribute is deprecated. Please adjust your Contao DCA file.');
				}

				// Prepend the theme path
				if (icon.indexOf('/') == -1) {
					icon = AjaxRequest.themePath + icon;
				}
				if (icond.indexOf('/') == -1) {
					icond = AjaxRequest.themePath + icond;
				}

				img.setStyle('background-image', 'url(' + (!published ? icon : icond) + ')');
			}
		}

		// Mark disabled format definitions
		if (table == 'tl_style') {
			div.getParent('div').getElement('pre').toggleClass('disabled');
		}

		// Send request
		if (!published) {
			image.src = AjaxRequest.themePath + 'visible.gif';
			image.set('data-state', 1);
			new Request.Contao({'url':window.location.href, 'followRedirects':false}).get({'tid':id, 'state':1, 'rt':Contao.request_token});
		} else {
			image.src = AjaxRequest.themePath + 'invisible.gif';
			image.set('data-state', 0);
			new Request.Contao({'url':window.location.href, 'followRedirects':false}).get({'tid':id, 'state':0, 'rt':Contao.request_token});
		}

		return false;
	},

	/**
	 * Feature/unfeature an element
	 *
	 * @param {object} el The DOM element
	 * @param {string} id The ID of the target element
	 *
	 * @returns {boolean}
	 */
	toggleFeatured: function(el, id) {
		el.blur();

		var image = $(el).getFirst('img'),
			featured = (image.get('data-state') == 1);

		// Backwards compatibility
		if (image.get('data-state') === null) {
			featured = (image.src.indexOf('featured_') == -1);
			console.warn('Using a featured toggle without a "data-state" attribute is deprecated. Please adjust your Contao DCA file.');
		}

		// Send the request
		if (!featured) {
			image.src = AjaxRequest.themePath + 'featured.gif';
			image.set('data-state', 1);
			new Request.Contao().post({'action':'toggleFeatured', 'id':id, 'state':1, 'REQUEST_TOKEN':Contao.request_token});
		} else {
			image.src = AjaxRequest.themePath + 'featured_.gif';
			image.set('data-state', 0);
			new Request.Contao().post({'action':'toggleFeatured', 'id':id, 'state':0, 'REQUEST_TOKEN':Contao.request_token});
		}

		return false;
	},

	/**
	 * Toggle the visibility of a fieldset
	 *
	 * @param {object} el    The DOM element
	 * @param {string} id    The ID of the target element
	 * @param {string} table The table name
	 *
	 * @returns {boolean}
	 */
	toggleFieldset: function(el, id, table) {
		el.blur();
		var fs = $('pal_' + id);

		if (fs.hasClass('collapsed')) {
			fs.removeClass('collapsed');
			new Request.Contao().post({'action':'toggleFieldset', 'id':id, 'table':table, 'state':1, 'REQUEST_TOKEN':Contao.request_token});
		} else {
			var form = fs.getParent('form'),
				inp = fs.getElements('[required]'),
				collapse = true;

			for (var i=0; i<inp.length; i++) {
				if (!inp[i].get('value')) {
					collapse = false;
					break;
				}
			}

			if (!collapse) {
				if (typeof(form.checkValidity) == 'function') form.getElement('input[type="submit"]').click();
			} else {
				fs.addClass('collapsed');
				new Request.Contao().post({'action':'toggleFieldset', 'id':id, 'table':table, 'state':0, 'REQUEST_TOKEN':Contao.request_token});
			}
		}

		return false;
	},

	/**
	 * Toggle a group of a multi-checkbox field
	 *
	 * @param {object} el The DOM element
	 * @param {string} id The ID of the target element
	 *
	 * @returns {boolean}
	 */
	toggleCheckboxGroup: function(el, id) {
		el.blur();

		var item = $(id),
			image = $(el).getFirst('img');

		if (item) {
			if (item.getStyle('display') == 'none') {
				item.setStyle('display', null);
				image.src = AjaxRequest.themePath + 'folMinus.gif';
				new Request.Contao().post({'action':'toggleCheckboxGroup', 'id':id, 'state':1, 'REQUEST_TOKEN':Contao.request_token});
			} else {
				item.setStyle('display', 'none');
				image.src = AjaxRequest.themePath + 'folPlus.gif';
				new Request.Contao().post({'action':'toggleCheckboxGroup', 'id':id, 'state':0, 'REQUEST_TOKEN':Contao.request_token});
			}
			return true;
		}

		return false;
	},

	/**
	 * Store the Live Update ID
	 *
	 * @param {object} el The DOM element
	 * @param {string} id The ID of the input field
	 */
	liveUpdate: function(el, id) {
		var uid = $(id);
		if (!uid) return;

		new Request.Contao({
			onRequest: $('lu_message').set('html', '<p class="tl_info">Connecting to the Live Update server</p>'),
			onSuccess: function(txt, json) {
				if (txt) {
					$('lu_message').set('html', json.content);
				} else {
					$(el).submit();
				}
			}
		}).post({'action':'liveUpdate', 'id':uid.value, 'REQUEST_TOKEN':Contao.request_token});
	},

	/**
	 * Display the "loading data" message
	 *
	 * @param {string} message The message text
	 */
	displayBox: function(message) {
		var box = $('tl_ajaxBox'),
			overlay = $('tl_ajaxOverlay'),
			scroll = window.getScroll();

		if (overlay === null) {
			overlay = new Element('div', {
				'id': 'tl_ajaxOverlay'
			}).inject($(document.body), 'bottom');
		}

		overlay.set({
			'styles': {
				'display': 'block',
				'top': scroll.y + 'px'
			}
		});

		if (box === null) {
			box = new Element('div', {
				'id': 'tl_ajaxBox'
			}).inject($(document.body), 'bottom');
		}

		box.set({
			'html': message,
			'styles': {
				'display': 'block',
				'top': (scroll.y + 100) + 'px'
			}
		})
	},

	/**
	 * Hide the "loading data" message
	 */
	hideBox: function() {
		var box = $('tl_ajaxBox'),
			overlay = $('tl_ajaxOverlay');

		if (overlay) {
			overlay.setStyle('display', 'none');
		}

		if (box) {
			box.setStyle('display', 'none');
		}
	}
};


/**
 * Provide methods to handle back end tasks.
 *
 * @author Leo Feyer <https://github.com/leofeyer>
 */
var Backend =
{
	/**
	 * The current ID
	 * @member {(string|null)}
	 */
	currentId: null,

	/**
	 * The x mouse position
	 * @member {int}
	 */
	xMousePosition: 0,

	/**
	 * The Y mouse position
	 * @member {int}
	 */
	yMousePosition: 0,

	/**
	 * The popup window
	 * @member {object}
	 */
	popupWindow: null,

	/**
	 * The theme path
	 * @member {string}
	 */
	themePath: Contao.script_url + 'system/themes/' + Contao.theme + '/images/',

	/**
	 * Get the current mouse position
	 *
	 * @param {object} event The event object
	 */
	getMousePosition: function(event) {
		Backend.xMousePosition = event.client.x;
		Backend.yMousePosition = event.client.y;
	},

	/**
	 * Open a new window
	 *
	 * @param {object} el     The DOM element
	 * @param {int}    width  The width in pixels
	 * @param {int}    height The height in pixels
	 *
	 * @deprecated Use Backend.openModalWindow() instead
	 */
	openWindow: function(el, width, height) {
		el.blur();
		width = Browser.ie ? (width + 40) : (width + 17);
		height = Browser.ie ? (height + 30) : (height + 17);
		Backend.popupWindow = window.open(el.href, '', 'width=' + width + ',height=' + height + ',modal=yes,left=100,top=50,location=no,menubar=no,resizable=yes,scrollbars=yes,status=no,toolbar=no');
	},

	/**
	 * Open a modal window
	 *
	 * @param {int}    width   The width in pixels
	 * @param {string} title   The window's title
	 * @param {string} content The window's content
	 */
	openModalWindow: function(width, title, content) {
		new SimpleModal({
			'width': width,
			'hideFooter': true,
			'draggable': false,
			'overlayOpacity': .5,
			'onShow': function() { document.body.setStyle('overflow', 'hidden'); },
			'onHide': function() { document.body.setStyle('overflow', 'auto'); }
		}).show({
			'title': title,
			'contents': content
		});
	},

	/**
	 * Open an image in a modal window
	 *
	 * @param {object} options An optional options object
	 */
	openModalImage: function(options) {
		var opt = options || {};
		var M = new SimpleModal({
			'width': opt.width,
			'hideFooter': true,
			'draggable': false,
			'overlayOpacity': .5,
			'onShow': function() { document.body.setStyle('overflow', 'hidden'); },
			'onHide': function() { document.body.setStyle('overflow', 'auto'); }
		});
		M.show({
			'title': opt.title,
			'contents': '<img src="' + opt.url + '" alt="">'
		});
	},

	/**
	 * Open an iframe in a modal window
	 *
	 * @param {object} options An optional options object
	 */
	openModalIframe: function(options) {
		var opt = options || {};
		var max = (window.getSize().y-180).toInt();
		if (!opt.height || opt.height > max) opt.height = max;
		var M = new SimpleModal({
			'width': opt.width,
			'hideFooter': true,
			'draggable': false,
			'overlayOpacity': .5,
			'onShow': function() { document.body.setStyle('overflow', 'hidden'); },
			'onHide': function() { document.body.setStyle('overflow', 'auto'); }
		});
		M.show({
			'title': opt.title,
			'contents': '<iframe src="' + opt.url + '" width="100%" height="' + opt.height + '" frameborder="0"></iframe>'
		});
	},

	/**
	 * Open a selector page in a modal window
	 *
	 * @param {object} options An optional options object
	 */
	openModalSelector: function(options) {
		var opt = options || {},
			max = (window.getSize().y-180).toInt();
		if (!opt.height || opt.height > max) opt.height = max;
		var M = new SimpleModal({
			'width': opt.width,
			'btn_ok': Contao.lang.close,
			'draggable': false,
			'overlayOpacity': .5,
			'onShow': function() { document.body.setStyle('overflow', 'hidden'); },
			'onHide': function() { document.body.setStyle('overflow', 'auto'); }
		});
		M.addButton(Contao.lang.close, 'btn', function() {
			this.hide();
		});
		M.addButton(Contao.lang.apply, 'btn primary', function() {
			var frm = window.frames['simple-modal-iframe'],
				val = [], inp, field, i;
			if (frm === undefined) {
				alert('Could not find the SimpleModal frame');
				return;
			}
			if (frm.document.location.href.indexOf('contao/main.php') != -1) {
				alert(Contao.lang.picker);
				return; // see #5704
			}
			inp = frm.document.getElementById('tl_select').getElementsByTagName('input');
			for (i=0; i<inp.length; i++) {
				if (!inp[i].checked || inp[i].id.match(/^check_all_/)) continue;
				if (!inp[i].id.match(/^reset_/)) val.push(inp[i].get('value'));
			}
			if (opt.tag) {
				$(opt.tag).value = val.join(',');
				if (frm.document.location.href.indexOf('contao/page.php') != -1) {
					$(opt.tag).value = '{{link_url::' + $(opt.tag).value + '}}';
				}
				opt.self.set('href', opt.self.get('href').replace(/&value=[^&]*/, '&value=' + val.join(',')));
			} else {
				field = $('ctrl_' + opt.id);
				field.value = val.join("\t");
				var act = (frm.document.location.href.indexOf('contao/page.php') != -1) ? 'reloadPagetree' : 'reloadFiletree';
				new Request.Contao({
					field: field,
					evalScripts: false,
					onRequest: AjaxRequest.displayBox(Contao.lang.loading + ' …'),
					onSuccess: function(txt, json) {
						$('ctrl_' + opt.id).getParent('div').set('html', json.content);
						json.javascript && Browser.exec(json.javascript);
						AjaxRequest.hideBox();
						window.fireEvent('ajax_change');
					}
				}).post({'action':act, 'name':opt.id, 'value':field.value, 'REQUEST_TOKEN':Contao.request_token});
			}
			this.hide();
		});
		M.show({
			'title': opt.title,
			'contents': '<iframe src="' + opt.url + '" name="simple-modal-iframe" width="100%" height="' + opt.height + '" frameborder="0"></iframe>',
			'model': 'modal'
		});
	},

	/**
	 * Open a TinyMCE file browser in a modal window
	 *
	 * @param {string} field_name The field name
	 * @param {string} url        The URL
	 * @param {string} type       The picker type
	 * @param {object} win        The window object
	 */
	openModalBrowser: function(field_name, url, type, win) {
		var file = 'file.php',
			swtch = (type == 'file' ? '&amp;switch=1' : ''),
			isLink = (url.indexOf('{{link_url::') != -1);
		if (type == 'file' && (url == '' || isLink)) {
			file = 'page.php';
		}
		if (isLink) {
			url = url.replace(/^\{\{link_url::([0-9]+)}}$/, '$1');
		}
		var M = new SimpleModal({
			'width': 768,
			'btn_ok': Contao.lang.close,
			'draggable': false,
			'overlayOpacity': .5,
			'onShow': function() { document.body.setStyle('overflow', 'hidden'); },
			'onHide': function() { document.body.setStyle('overflow', 'auto'); }
		});
		M.addButton(Contao.lang.close, 'btn', function() {
			this.hide();
		});
		M.addButton(Contao.lang.apply, 'btn primary', function() {
			var frm = window.frames['simple-modal-iframe'],
				val, inp, i;
			if (frm === undefined) {
				alert('Could not find the SimpleModal frame');
				return;
			}
			inp = frm.document.getElementById('tl_select').getElementsByTagName('input');
			for (i=0; i<inp.length; i++) {
				if (inp[i].checked && !inp[i].id.match(/^reset_/)) {
					val = inp[i].get('value');
					break;
				}
			}
			if (!isNaN(val)) {
				val = '{{link_url::' + val + '}}';
			}
			win.document.getElementById(field_name).value = val;
			this.hide();
		});
		M.show({
			'title': win.document.getElement('div.mce-title').get('text'),
			'contents': '<iframe src="contao/' + file + '?table=tl_content&amp;field=singleSRC&amp;value=' + encodeURIComponent(url) + swtch + '" name="simple-modal-iframe" width="100%" height="' + (window.getSize().y-180).toInt() + '" frameborder="0"></iframe>',
			'model': 'modal'
		});
	},

	/**
	 * Get the current scroll offset and store it in a cookie
	 */
	getScrollOffset: function() {
		document.cookie = "BE_PAGE_OFFSET=" + window.getScroll().y + "; path=" + (Contao.path || '/');
	},

	/**
	 * Automatically submit a form
	 *
	 * @param {object} el The DOM element
	 */
	autoSubmit: function(el) {
		Backend.getScrollOffset();

		var hidden = new Element('input', {
			'type': 'hidden',
			'name': 'SUBMIT_TYPE',
			'value': 'auto'
		});

		var form = $(el) || el;
		hidden.inject(form, 'bottom');
		form.submit();
	},

	/**
	 * Scroll the window to a certain vertical position
	 *
	 * @param {int} offset The offset to scroll to
	 */
	vScrollTo: function(offset) {
		window.addEvent('load', function() {
			window.scrollTo(null, parseInt(offset));
		});
	},

	/**
	 * Limit the height of the preview pane
	 */
	limitPreviewHeight: function() {
		var hgt = 0;

		$$('div.limit_height').each(function(div) {
			var toggler, size, style;

			if (hgt === 0) {
				hgt = div.className.replace(/[^0-9]*/, '').toInt();
			}

			// Return if there is no height value
			if (!hgt) return;

			toggler = new Element('img', {
				'class': 'limit_toggler',
				'alt': '',
				'title': Contao.lang.expand,
				'width': 20,
				'height': 24,
				'data-state': 0
			});

			size = div.getCoordinates();

			new Tips.Contao(toggler, {
				offset: {x:0, y:30}
			});

			div.setStyle('height', hgt);

			// Disable the function if the preview height is below the max-height
			if (size.height <= hgt) {
				toggler.src = Backend.themePath + 'expand_.gif';
				toggler.inject(div, 'after');
				return;
			}

			toggler.src = Backend.themePath + 'expand.gif';
			toggler.setStyle('cursor', 'pointer');

			toggler.addEvent('click', function() {
				style = this.getPrevious('div').getStyle('height').toInt();
				toggler.getPrevious('div').setStyle('height', ((style > hgt) ? hgt : ''));

				if (toggler.get('data-state') == 0) {
					toggler.src = Backend.themePath + 'collapse.gif';
					toggler.set('data-state', 1);
					toggler.store('tip:title', Contao.lang.collapse);
				} else {
					toggler.src = Backend.themePath + 'expand.gif';
					toggler.set('data-state', 0);
					toggler.store('tip:title', Contao.lang.expand);
				}
			});

			toggler.inject(div, 'after');
		});
	},

	/**
	 * Toggle checkboxes
	 *
	 * @param {object} el   The DOM element
	 * @param {string} [id] The ID of the target element
	 */
	toggleCheckboxes: function(el, id) {
		var items = $$('input'),
			status = $(el).checked ? 'checked' : '';

		for (var i=0; i<items.length; i++) {
			if (items[i].type.toLowerCase() != 'checkbox') {
				continue;
			}
			if (id && items[i].id.substr(0, id.length) != id) {
				continue;
			}
			items[i].checked = status;
		}
	},

	/**
	 * Toggle a checkbox group
	 *
	 * @param {object} el The DOM element
	 * @param {string} id The ID of the target element
	 */
	toggleCheckboxGroup: function(el, id) {
		var cls = $(el).className,
			status = $(el).checked ? 'checked' : '';

		if (cls == 'tl_checkbox') {
			var cbx = $(id) ? $$('#' + id + ' .tl_checkbox') : $(el).getParent('fieldset').getElements('.tl_checkbox');
			cbx.each(function(checkbox) {
				checkbox.checked = status;
			});
		} else if (cls == 'tl_tree_checkbox') {
			$$('#' + id + ' .parent .tl_tree_checkbox').each(function(checkbox) {
				checkbox.checked = status;
			});
		}

		Backend.getScrollOffset();
	},

	/**
	 * Toggle checkbox elements
	 *
	 * @param {string} el  The DOM element
	 * @param {string} cls The CSS class name
	 */
	toggleCheckboxElements: function(el, cls) {
		var status = $(el).checked ? 'checked' : '';

		$$('.' + cls).each(function(checkbox) {
			if (checkbox.hasClass('tl_checkbox')) {
				checkbox.checked = status;
			}
		});

		Backend.getScrollOffset();
	},

	/**
	 * Toggle the line wrapping mode of a textarea
	 *
	 * @param {string} id The ID of the target element
	 */
	toggleWrap: function(id) {
		var textarea = $(id),
			status = (textarea.getProperty('wrap') == 'off') ? 'soft' : 'off';
		textarea.setProperty('wrap', status);
	},

	/**
	 * Toggle the synchronization results
	 */
	toggleUnchanged: function() {
		$$('#result-list .tl_confirm').each(function(el) {
			el.toggleClass('hidden');
		});
	},

	/**
	 * Toggle the opacity of the paste buttons
	 *
	 * @deprecated Not required anymore
	 */
	blink: function() {},

	/**
	 * Initialize the mootools color picker
	 *
	 * @returns {boolean}
	 *
	 * @deprecated Not required anymore
	 */
	addColorPicker: function() {
		return true;
	},

	/**
	 * Open the page picker wizard in a modal window
	 *
	 * @param {string} id The ID of the target element
	 *
	 * @deprecated Use Backend.openModalIframe() instead
	 */
	pickPage: function(id) {
		var width = 320,
			height = 112;

		Backend.currentId = id;
		Backend.ppValue = $(id).value;

		Backend.getScrollOffset();
		window.open($$('base')[0].href + 'contao/page.php?value=' + Backend.ppValue, '', 'width=' + width + ',height=' + height + ',modal=yes,left=' + (Backend.xMousePosition ? (Backend.xMousePosition-(width/2)) : 200) + ',top=' + (Backend.yMousePosition ? (Backend.yMousePosition-(height/2)+80) : 100) + ',location=no,menubar=no,resizable=yes,scrollbars=no,status=no,toolbar=no');
	},

	/**
	 * Open the file picker wizard in a modal window
	 *
	 * @param {string} id     The ID of the target element
	 * @param {string} filter The filter value
	 *
	 * @deprecated Use Backend.openModalIframe() instead
	 */
	pickFile: function(id, filter) {
		var width = 320,
			height = 112;

		Backend.currentId = id;
		Backend.ppValue = $(id).value;

		Backend.getScrollOffset();
		window.open($$('base')[0].href + 'contao/file.php?value=' + Backend.ppValue + '&filter=' + filter, '', 'width=' + width + ',height=' + height + ',modal=yes,left=' + (Backend.xMousePosition ? (Backend.xMousePosition-(width/2)) : 200) + ',top=' + (Backend.yMousePosition ? (Backend.yMousePosition-(height/2)+80) : 100) + ',location=no,menubar=no,resizable=yes,scrollbars=no,status=no,toolbar=no');
	},

	/**
	 * Collapse all palettes
	 */
	collapsePalettes: function() {
		$$('fieldset.hide').each(function(el) {
			el.addClass('collapsed');
		});
		$$('label.error, label.mandatory').each(function(el) {
			var fs = el.getParent('fieldset');
			fs && fs.removeClass('collapsed');
		});
	},

	/**
	 * Add the interactive help
	 */
	addInteractiveHelp: function() {
		new Tips.Contao('p.tl_tip', {
			offset: {x:9, y:21},
			text: function(e) {
				return e.get('html');
			}
		});

		// Links and input elements
		['a[title]', 'input[title]'].each(function(el) {
			new Tips.Contao($$(el).filter(function(i) {
				return i.title != '';
			}), {
				offset: {x:0, y:26}
			});
		});

		// Images
		$$('img[title]').filter(function(i) {
			return i.title != '';
		}).each(function(el) {
			new Tips.Contao(el, {
				offset: {x:0, y:((el.get('class') == 'gimage') ? 60 : 30)}
			});
		});
	},

	/**
	 * Make parent view items sortable
	 *
	 * @param {object} ul The DOM element
	 *
	 * @author Joe Ray Gregory
	 * @author Martin Auswöger
	 */
	makeParentViewSortable: function(ul) {
		var ds = new Scroller(document.getElement('body'), {
			onChange: function(x, y) {
				this.element.scrollTo(this.element.getScroll().x, y);
			}
		});

		var list = new Sortables(ul, {
			constrain: true,
			opacity: 0.6,
			onStart: function() {
				ds.start();
			},
			onComplete: function() {
				ds.stop();
			},
			onSort: function(el) {
				var ul = el.getParent('ul'),
					wrapLevel = 0;

				if (!ul) return;

				ul.getChildren('li').each(function(el) {
					var div = el.getFirst('div');

					if (!div) return;

					if (div.hasClass('wrapper_stop') && wrapLevel > 0) {
						wrapLevel--;
					}

					div.className = div.className.replace(/(^|\s)indent[^\s]*/g, '');

					if (wrapLevel > 0) {
						div.addClass('indent').addClass('indent_' + wrapLevel);
					}

					if (div.hasClass('wrapper_start')) {
						wrapLevel++;
					}
				});
			},
			handle: '.drag-handle'
		});

		list.active = false;

		list.addEvent('start', function() {
			list.active = true;
		});

		list.addEvent('complete', function(el) {
			if (!list.active) return;
			var id, pid, req, href;

			if (el.getPrevious('li')) {
				id = el.get('id').replace(/li_/, '');
				pid = el.getPrevious('li').get('id').replace(/li_/, '');
				req = window.location.search.replace(/id=[0-9]*/, 'id=' + id) + '&act=cut&mode=1&pid=' + pid;
				href = window.location.href.replace(/\?.*$/, '');
				new Request.Contao({'url':href+req, 'followRedirects':false}).get();
			} else if (el.getParent('ul')) {
				id = el.get('id').replace(/li_/, '');
				pid = el.getParent('ul').get('id').replace(/ul_/, '');
				req = window.location.search.replace(/id=[0-9]*/, 'id=' + id) + '&act=cut&mode=2&pid=' + pid;
				href = window.location.href.replace(/\?.*$/, '');
				new Request.Contao({'url':href+req, 'followRedirects':false}).get();
			}
		});
	},

	/**
	 * Make multiSRC items sortable
	 *
	 * @param {string} id  The ID of the target element
	 * @param {string} oid The DOM element
	 */
	makeMultiSrcSortable: function(id, oid) {
		var list = new Sortables($(id), {
			constrain: true,
			opacity: 0.6
		}).addEvent('complete', function() {
			var els = [],
				lis = $(id).getChildren('li'),
				i;
			for (i=0; i<lis.length; i++) {
				els.push(lis[i].get('data-id'));
			}
			$(oid).value = els.join(',');
		});
		list.fireEvent('complete'); // Initial sorting
	},

	/**
	 * Make the wizards sortable
	 */
	makeWizardsSortable: function() {
		$$('.tl_listwizard').each(function(el) {
			new Sortables(el, {
				constrain: true,
				opacity: 0.6,
				handle: '.drag-handle'
			});
		});

		$$('.tl_tablewizard').each(function(el) {
			var els = el.getElement('.sortable');
			new Sortables(els, {
				constrain: true,
				opacity: 0.6,
				handle: '.drag-handle',
				onComplete: function() {
					Backend.tableWizardResort(els);
				}
			});
		});

		$$('.tl_modulewizard').each(function(el) {
			new Sortables(el.getElement('.sortable'), {
				constrain: true,
				opacity: 0.6,
				handle: '.drag-handle'
			});
		});

		$$('.tl_optionwizard').each(function(el) {
			new Sortables(el.getElement('.sortable'), {
				constrain: true,
				opacity: 0.6,
				handle: '.drag-handle'
			});
		});

		$$('.tl_checkbox_wizard').each(function(el) {
			var els = el.getElement('.sortable');
			if (els.hasClass('sortable-done')) return;

			new Sortables(els, {
				constrain: true,
				opacity: 0.6,
				handle: '.drag-handle'
			});

			els.addClass('sortable-done');
		});
	},

	/**
	 * List wizard
	 *
	 * @param {object} el      The DOM element
	 * @param {string} command The command name
	 * @param {string} id      The ID of the target element
	 */
	listWizard: function(el, command, id) {
		var list = $(id),
			parent = $(el).getParent('li'),
			items = list.getChildren(),
			tabindex = list.get('data-tabindex'),
			input, previous, next, rows, i;

		Backend.getScrollOffset();

		switch (command) {
			case 'copy':
				var clone = parent.clone(true).inject(parent, 'before');
				if (input = parent.getFirst('input')) {
					clone.getFirst('input').value = input.value;
				}
				break;
			case 'up':
				if (previous = parent.getPrevious('li')) {
					parent.inject(previous, 'before');
				} else {
					parent.inject(list, 'bottom');
				}
				break;
			case 'down':
				if (next = parent.getNext('li')) {
					parent.inject(next, 'after');
				} else {
					parent.inject(list.getFirst('li'), 'before');
				}
				break;
			case 'delete':
				if (items.length > 1) {
					parent.destroy();
				} else {
					parent.getFirst('input').set('value', '');
				}
				break;
		}

		rows = list.getChildren();

		for (i=0; i<rows.length; i++) {
			if (input = rows[i].getFirst('input[type="text"]')) {
				input.set('tabindex', tabindex++);
			}
		}

		new Sortables(list, {
			constrain: true,
			opacity: 0.6,
			handle: '.drag-handle'
		});
	},

	/**
	 * Table wizard
	 *
	 * @param {object} el      The DOM element
	 * @param {string} command The command name
	 * @param {string} id      The ID of the target element
	 */
	tableWizard: function(el, command, id) {
		var table = $(id),
			tbody = table.getElement('tbody'),
			rows = tbody.getChildren(),
			parentTd = $(el).getParent('td'),
			parentTr = parentTd.getParent('tr'),
			headTr = table.getElement('thead').getFirst('tr'),
			cols = parentTr.getChildren(),
			index = 0,
			textarea, previous, next, current, i;

		for (i=0; i<cols.length; i++) {
			if (cols[i] == parentTd) {
				break;
			}
			index++;
		}

		Backend.getScrollOffset();

		switch (command) {
			case 'rcopy':
				var tr = new Element('tr');
				for (i=0; i<cols.length; i++) {
					next = cols[i].clone(true).inject(tr, 'bottom');
					if (textarea = cols[i].getFirst('textarea')) {
						next.getFirst('textarea').value = textarea.value;
					}
				}
				tr.inject(parentTr, 'after');
				break;
			case 'rup':
				if (previous = parentTr.getPrevious('tr')) {
					parentTr.inject(previous, 'before');
				} else {
					parentTr.inject(tbody, 'bottom')
				}
				break;
			case 'rdown':
				if (next = parentTr.getNext('tr')) {
					parentTr.inject(next, 'after');
				} else {
					parentTr.inject(tbody, 'top');
				}
				break;
			case 'rdelete':
				if (rows.length > 1) {
					parentTr.destroy();
				} else {
					parentTr.getElements('textarea').set('text', '');
				}
				break;
			case 'ccopy':
				for (i=0; i<rows.length; i++) {
					current = rows[i].getChildren()[index];
					next = current.clone(true).inject(current, 'after');
					if (textarea = current.getFirst('textarea')) {
						next.getFirst('textarea').value = textarea.value;
					}
				}
				headTr.getFirst('td').clone(true).inject(headTr.getLast('td'), 'before');
				break;
			case 'cmovel':
				if (index > 0) {
					for (i=0; i<rows.length; i++) {
						current = rows[i].getChildren()[index];
						current.inject(current.getPrevious(), 'before');
					}
				} else {
					for (i=0; i<rows.length; i++) {
						current = rows[i].getChildren()[index];
						current.inject(rows[i].getLast(), 'before');
					}
				}
				break;
			case 'cmover':
				if (index < (cols.length - 2)) {
					for (i=0; i<rows.length; i++) {
						current = rows[i].getChildren()[index];
						current.inject(current.getNext(), 'after');
					}
				} else {
					for (i=0; i<rows.length; i++) {
						current = rows[i].getChildren()[index];
						current.inject(rows[i].getFirst(), 'before');
					}
				}
				break;
			case 'cdelete':
				if (cols.length > 2) {
					for (i=0; i<rows.length; i++) {
						rows[i].getChildren()[index].destroy();
					}
					headTr.getFirst('td').destroy();
				} else {
					for (i=0; i<rows.length; i++) {
						rows[i].getElements('textarea').set('text', '');
					}
				}
				break;
		}

		Backend.tableWizardResort(tbody);

		new Sortables(tbody, {
			constrain: true,
			opacity: 0.6,
			handle: '.drag-handle',
			onComplete: function() {
				Backend.tableWizardResort(tbody);
			}
		});

		Backend.tableWizardResize();
	},

	/**
	 * Resort the table wizard fields
	 *
	 * @param {object} tbody The DOM element
	 */
	tableWizardResort: function(tbody) {
		var rows = tbody.getChildren(),
			tabindex = tbody.get('data-tabindex'),
			textarea, childs, i, j;

		for (i=0; i<rows.length; i++) {
			childs = rows[i].getChildren();
			for (j=0; j<childs.length; j++) {
				if (textarea = childs[j].getFirst('textarea')) {
					textarea.set('tabindex', tabindex++);
					textarea.name = textarea.name.replace(/\[[0-9]+][[0-9]+]/g, '[' + i + '][' + j + ']')
				}
			}
		}
	},

	/**
	 * Resize the table wizard fields on focus
	 *
	 * @param {float} [factor] The resize factor
	 */
	tableWizardResize: function(factor) {
		var size = Cookie.read('BE_CELL_SIZE');
		if (size === null && factor === null) return;

		if (factor !== null) {
			size = '';
			$$('.tl_tablewizard textarea').each(function(el) {
				el.setStyle('width', (el.getStyle('width').toInt() * factor).round().limit(142, 284));
				el.setStyle('height', (el.getStyle('height').toInt() * factor).round().limit(66, 132));
				if (size == '') {
					size = el.getStyle('width') + '|' + el.getStyle('height');
				}
			});
			Cookie.write('BE_CELL_SIZE', size, { path: Contao.path });
		} else if (size !== null) {
			var chunks = size.split('|');
			$$('.tl_tablewizard textarea').each(function(el) {
				el.setStyle('width', chunks[0]);
				el.setStyle('height', chunks[1]);
			});
		}
	},

	/**
	 * Module wizard
	 *
	 * @param {object} el      The DOM element
	 * @param {string} command The command name
	 * @param {string} id      The ID of the target element
	 */
	moduleWizard: function(el, command, id) {
		var table = $(id),
			tbody = table.getElement('tbody'),
			parent = $(el).getParent('tr'),
			rows = tbody.getChildren(),
			tabindex = tbody.get('data-tabindex'),
			input, select, childs, a, i, j;

		Backend.getScrollOffset();

		switch (command) {
			case 'copy':
				var tr = new Element('tr');
				childs = parent.getChildren();
				for (i=0; i<childs.length; i++) {
					var next = childs[i].clone(true).inject(tr, 'bottom');
					if (select = childs[i].getFirst('select')) {
						next.getFirst('select').value = select.value;
					}
				}
				tr.inject(parent, 'after');
				tr.getElement('.chzn-container').destroy();
				tr.getElement('.tl_select_column').destroy();
				new Chosen(tr.getElement('select.tl_select'));
				Stylect.convertSelects();
				Backend.convertEnableModules();
				break;
			case 'up':
				if (tr = parent.getPrevious('tr')) {
					parent.inject(tr, 'before');
				} else {
					parent.inject(tbody, 'bottom');
				}
				break;
			case 'down':
				if (tr = parent.getNext('tr')) {
					parent.inject(tr, 'after');
				} else {
					parent.inject(tbody, 'top');
				}
				break;
			case 'delete':
				if (rows.length > 1) {
					parent.destroy();
				}
				break;
		}

		rows = tbody.getChildren();

		for (i=0; i<rows.length; i++) {
			childs = rows[i].getChildren();
			for (j=0; j<childs.length; j++) {
				if (a = childs[j].getFirst('a.chzn-single')) {
					a.set('tabindex', tabindex++);
				}
				if (select = childs[j].getFirst('select')) {
					select.name = select.name.replace(/\[[0-9]+]/g, '[' + i + ']');
				}
				if (input = childs[j].getFirst('input[type="checkbox"]')) {
					input.set('tabindex', tabindex++);
					input.name = input.name.replace(/\[[0-9]+]/g, '[' + i + ']');
				}
			}
		}

		new Sortables(tbody, {
			constrain: true,
			opacity: 0.6,
			handle: '.drag-handle'
		});
	},

	/**
	 * Options wizard
	 *
	 * @param {object} el      The DOM element
	 * @param {string} command The command name
	 * @param {string} id      The ID of the target element
	 */
	optionsWizard: function(el, command, id) {
		var table = $(id),
			tbody = table.getElement('tbody'),
			parent = $(el).getParent('tr'),
			rows = tbody.getChildren(),
			tabindex = tbody.get('data-tabindex'),
			input, childs, i, j;

		Backend.getScrollOffset();

		switch (command) {
			case 'copy':
				var tr = new Element('tr');
				childs = parent.getChildren();
				for (i=0; i<childs.length; i++) {
					var next = childs[i].clone(true).inject(tr, 'bottom');
					if (input = childs[i].getFirst('input')) {
						next.getFirst('input').value = input.value;
						if (input.type == 'checkbox') {
							next.getFirst('input').checked = input.checked ? 'checked' : '';
						}
					}
				}
				tr.inject(parent, 'after');
				break;
			case 'up':
				if (tr = parent.getPrevious('tr')) {
					parent.inject(tr, 'before');
				} else {
					parent.inject(tbody, 'bottom');
				}
				break;
			case 'down':
				if (tr = parent.getNext('tr')) {
					parent.inject(tr, 'after');
				} else {
					parent.inject(tbody, 'top');
				}
				break;
			case 'delete':
				if (rows.length > 1) {
					parent.destroy();
				}
				break;
		}

		rows = tbody.getChildren();

		for (i=0; i<rows.length; i++) {
			childs = rows[i].getChildren();
			for (j=0; j<childs.length; j++) {
				if (input = childs[j].getFirst('input')) {
					input.set('tabindex', tabindex++);
					input.name = input.name.replace(/\[[0-9]+]/g, '[' + i + ']');
					if (input.type == 'checkbox') {
						input.id = input.name.replace(/\[[0-9]+]/g, '').replace(/\[/g, '_').replace(/]/g, '') + '_' + i;
						input.getNext('label').set('for', input.id);
					}
				}
			}
		}

		new Sortables(tbody, {
			constrain: true,
			opacity: 0.6,
			handle: '.drag-handle'
		});
	},

	/**
	 * Key/value wizard
	 *
	 * @param {object} el      The DOM element
	 * @param {string} command The command name
	 * @param {string} id      The ID of the target element
	 */
	keyValueWizard: function(el, command, id) {
		var table = $(id),
			tbody = table.getElement('tbody'),
			parent = $(el).getParent('tr'),
			rows = tbody.getChildren(),
			tabindex = tbody.get('data-tabindex'),
			input, childs, i, j;

		Backend.getScrollOffset();

		switch (command) {
			case 'copy':
				var tr = new Element('tr');
				childs = parent.getChildren();
				for (i=0; i<childs.length; i++) {
					var next = childs[i].clone(true).inject(tr, 'bottom');
					if (input = childs[i].getFirst('input')) {
						next.getFirst().value = input.value;
					}
				}
				tr.inject(parent, 'after');
				break;
			case 'up':
				if (tr = parent.getPrevious('tr')) {
					parent.inject(tr, 'before');
				} else {
					parent.inject(tbody, 'bottom');
				}
				break;
			case 'down':
				if (tr = parent.getNext('tr')) {
					parent.inject(tr, 'after');
				} else {
					parent.inject(tbody, 'top');
				}
				break;
			case 'delete':
				if (rows.length > 1) {
					parent.destroy();
				}
				break;
		}

		rows = tbody.getChildren();

		for (i=0; i<rows.length; i++) {
			childs = rows[i].getChildren();
			for (j=0; j<childs.length; j++) {
				if (input = childs[j].getFirst('input')) {
					input.set('tabindex', tabindex++);
					input.name = input.name.replace(/\[[0-9]+]/g, '[' + i + ']')
				}
			}
		}

		new Sortables(tbody, {
			constrain: true,
			opacity: 0.6,
			handle: '.drag-handle'
		});
	},

	/**
	 * Checkbox wizard
	 *
	 * @param {object} el      The DOM element
	 * @param {string} command The command name
	 * @param {string} id      The ID of the target element
	 */
	checkboxWizard: function(el, command, id) {
		var container = $(id).getElement('.sortable'),
			parent = $(el).getParent('span'), span;

		Backend.getScrollOffset();

		switch (command) {
			case 'up':
				if ((span = parent.getPrevious('span'))) {
					parent.inject(span, 'before');
				} else {
					parent.inject(container, 'bottom');
				}
				break;
			case 'down':
				if (span = parent.getNext('span')) {
					parent.inject(span, 'after');
				} else {
					parent.inject(container, 'top');
				}
				break;
		}
	},

	/**
	 * Meta wizard
	 *
	 * @param {object} el The select element
	 * @param {string} ul The DOM element
	 */
	metaWizard: function(el, ul) {
		var opt = el.getParent('div').getElement('select');

		if (opt.value == '') {
			return; // No language given
		}

		var li = $(ul).getLast('li').clone(),
			span = li.getElement('span'),
			img = span.getElement('img');

		// Update the data-language attribute
		li.setProperty('data-language', opt.value);

		// Update the language text
		span.set('text', opt.options[opt.selectedIndex].text + ' ');
		img.inject(span, 'bottom');

		// Update the name, label and ID attributes
		li.getElements('input').each(function(inp) {
			inp.value = '';
			inp.name = inp.name.replace(/\[[a-z]{2}(_[A-Z]{2})?]/, '[' + opt.value + ']');
			var lbl = inp.getPrevious('label'),
				i = parseInt(lbl.get('for').replace(/ctrl_[^_]+_/, ''));
			lbl.set('for', lbl.get('for').replace(i, i+1));
			inp.id = lbl.get('for');
		});

		// Update the class name
		li.className = (li.className == 'even') ? 'odd' : 'even';
		li.inject($(ul), 'bottom');

		// Disable the "add language" button
		el.getParent('div').getElement('input[type="button"]').setProperty('disabled', true);

		// Disable the option and update chosen
		opt.options[opt.selectedIndex].setProperty('disabled', true);
		opt.value = '';
		opt.fireEvent('liszt:updated');
	},

	/**
	 * Remove a meta entry
	 *
	 * @param {object} el The DOM element
	 */
	metaDelete: function(el) {
		var li = el.getParent('li'),
			opt = el.getParent('div').getElement('select');

		// Empty the last element instead of removing it (see #4858)
		if (li.getPrevious() === null && li.getNext() === null) {
			li.getElements('input').each(function(input) {
				input.value = '';
			});
		} else {
			// Enable the option and update chosen
			opt.getElement('option[value=' + li.getProperty('data-language') + ']').removeProperty('disabled');
			li.destroy();
			opt.fireEvent('liszt:updated');
		}
	},

	/**
	 * Toggle the "add language" button
	 *
	 * @param {object} el The DOM element
	 */
	toggleAddLanguageButton: function(el) {
		var inp = el.getParent('div').getElement('input[type="button"]');
		if (el.value != '') {
			inp.removeProperty('disabled');
		} else {
			inp.setProperty('disabled', true);
		}
	},

	/**
	 * Update the "edit module" links in the module wizard
	 *
	 * @param {object} el The DOM element
	 */
	updateModuleLink: function(el) {
		var td = el.getParent('tr').getLast('td'),
			a = td.getElement('a.module_link');

		a.href = a.href.replace(/id=[0-9]+/, 'id=' + el.value);

		if (el.value > 0) {
			td.getElement('a.module_link').setStyle('display', null);
			td.getElement('img.module_image').setStyle('display', 'none');
		} else {
			td.getElement('a.module_link').setStyle('display', 'none');
			td.getElement('img.module_image').setStyle('display', null);
		}
	},

	/**
	 * Convert the "enable module" checkboxes
	 */
	convertEnableModules: function() {
		$$('img.mw_enable').filter(function(el) {
			return !el.hasEvent('click');
		}).each(function(el) {
			el.addEvent('click', function() {
				Backend.getScrollOffset();
				var cbx = el.getNext('input');

				if (cbx.checked) {
					cbx.checked = '';
					el.src = Backend.themePath + 'invisible.gif';
				} else {
					cbx.checked = 'checked';
					el.src = Backend.themePath + 'visible.gif';
				}
			});
		});
	},

	/**
	 * Update the fields of the imageSize widget upon change
	 */
	enableImageSizeWidgets: function() {
		$$('.tl_image_size').each(function(el) {
			var select = el.getElement('select'),
				widthInput = el.getChildren('input')[0],
				heightInput = el.getChildren('input')[1],
				update = function() {
					if (select.get('value') === '' || select.get('value').toInt().toString() === select.get('value')) {
						widthInput.readOnly = true;
						heightInput.readOnly = true;
						var dimensions = $(select.getSelected()[0]).get('text');
						dimensions = dimensions.split('(').length > 1
							? dimensions.split('(').getLast().split(')')[0].split('x')
							: ['', ''];
						widthInput.set('value', '').set('placeholder', dimensions[0] * 1 || '');
						heightInput.set('value', '').set('placeholder', dimensions[1] * 1 || '');
					}
					else {
						widthInput.set('placeholder', '');
						heightInput.set('placeholder', '');
						widthInput.readOnly = false;
						heightInput.readOnly = false;
					}
				}
			;

			update();
			select.addEvent('change', update);
			select.addEvent('keyup', update);
		});
	},

	/**
	 * Allow to toggle checkboxes or radio buttons by clicking a row
	 *
	 * @author Kamil Kuzminski
	 */
	enableToggleSelect: function() {
		var container = $('tl_select'),
			checkboxes = [], start, thisIndex, startIndex, status, from, to,
			shiftToggle = function(el) {
				thisIndex = checkboxes.indexOf(el);
				startIndex = checkboxes.indexOf(start);
				from = Math.min(thisIndex, startIndex);
				to = Math.max(thisIndex, startIndex);
				status = checkboxes[startIndex].checked ? true : false;

				for (from; from<=to; from++) {
					checkboxes[from].checked = status;
				}
			};

		if (container) {
			checkboxes = container.getElements('input[type="checkbox"]');
		}

		// Row click
		$$('.toggle_select').each(function(el) {
			el.addEvent('click', function(e) {
				var input = $(el).getElement('input[type="checkbox"],input[type="radio"]');

				if (!input) {
					return;
				}

				// Radio buttons
				if (input.type == 'radio') {
					if (!input.checked) {
						input.checked = 'checked';
					}
					return;
				}

				// Checkboxes
				if (e.shift && start) {
					shiftToggle(input);
				} else {
					input.checked = input.checked ? '' : 'checked';

					if (input.get('onclick') == 'Backend.toggleCheckboxes(this)') {
						Backend.toggleCheckboxes(input); // see #6399
					}
				}

				start = input;
			});
		});

		// Checkbox click
		checkboxes.each(function(el) {
			el.addEvent('click', function(e) {
				if (e.shift && start) {
					shiftToggle(this);
				}

				start = this;
			});
		});
	},

	/**
	 * Allow to mark the important part of an image
	 *
	 * @param {object} el The DOM element
	 */
	editPreviewWizard: function(el) {
		el = $(el);
		var imageElement = el.getElement('img'),
			inputElements = {},
			isDrawing = false,
			originalWidth = el.get('data-original-width'),
			originalHeight = el.get('data-original-height'),
			partElement, startPos,
			getScale = function() {
				return imageElement.getComputedSize().width / originalWidth;
			},
			updateImage = function() {
				var scale = getScale(),
					imageSize = imageElement.getComputedSize();
				partElement.setStyles({
					top: imageSize.computedTop + (inputElements.y.get('value') * scale).round() + 'px',
					left: imageSize.computedLeft + (inputElements.x.get('value') * scale).round() + 'px',
					width: (inputElements.width.get('value') * scale).round() + 'px',
					height: (inputElements.height.get('value') * scale).round() + 'px'
				});
				if (!inputElements.width.get('value').toInt() || !inputElements.height.get('value').toInt()) {
					partElement.setStyle('display', 'none');
				} else {
					partElement.setStyle('display', null);
				}
			},
			updateValues = function() {
				var scale = getScale(),
					styles = partElement.getStyles('top', 'left', 'width', 'height'),
					imageSize = imageElement.getComputedSize(),
					values = {
						x: Math.max(0, Math.min(originalWidth, (styles.left.toFloat() - imageSize.computedLeft) / scale)).round(),
						y: Math.max(0, Math.min(originalHeight, (styles.top.toFloat() - imageSize.computedTop) / scale)).round()
					};
				values.width = Math.min(originalWidth - values.x, styles.width.toFloat() / scale).round();
				values.height = Math.min(originalHeight - values.y, styles.height.toFloat() / scale).round();
				if (!values.width || !values.height) {
					values.x = values.y = values.width = values.height = '';
					partElement.setStyle('display', 'none');
				} else {
					partElement.setStyle('display', null);
				}
				Object.each(values, function(value, key) {
					inputElements[key].set('value', value);
				});
			},
			start = function(event) {
				event.preventDefault();
				if (isDrawing) {
					return;
				}
				isDrawing = true;
				startPos = {
					x: event.page.x - el.getPosition().x - imageElement.getComputedSize().computedLeft,
					y: event.page.y - el.getPosition().y - imageElement.getComputedSize().computedTop
				};
				move(event);
			},
			move = function(event) {
				if (!isDrawing) {
					return;
				}
				event.preventDefault();
				var imageSize = imageElement.getComputedSize();
				var rect = {
					x: [
						Math.max(0, Math.min(imageSize.width, startPos.x)),
						Math.max(0, Math.min(imageSize.width, event.page.x - el.getPosition().x - imageSize.computedLeft))
					],
					y: [
						Math.max(0, Math.min(imageSize.height, startPos.y)),
						Math.max(0, Math.min(imageSize.height, event.page.y - el.getPosition().y - imageSize.computedTop))
					]
				};
				partElement.setStyles({
					top: Math.min(rect.y[0], rect.y[1]) + imageSize.computedTop + 'px',
					left: Math.min(rect.x[0], rect.x[1]) + imageSize.computedLeft + 'px',
					width: Math.abs(rect.x[0] - rect.x[1]) + 'px',
					height: Math.abs(rect.y[0] - rect.y[1]) + 'px'
				});
				updateValues();
			},
			stop = function(event) {
				move(event);
				isDrawing = false;
			},
			init = function() {
				el.getParent().getElements('input[name^="importantPart"]').each(function(input) {
					['x', 'y', 'width', 'height'].each(function(key) {
						if (input.get('name').substr(13, key.length) === key.capitalize()) {
							inputElements[key] = input = $(input);
						}
					});
				});
				if (Object.getLength(inputElements) !== 4) {
					return;
				}
				Object.each(inputElements, function(input) {
					input.getParent().setStyle('display', 'none');
				});
				el.addClass('tl_edit_preview_enabled');
				partElement = new Element('div', {
					'class': 'tl_edit_preview_important_part'
				}).inject(el);
				updateImage();
				imageElement.addEvent('load', updateImage);
				el.addEvents({
					mousedown: start,
					touchstart: start
				});
				$(document.documentElement).addEvents({
					mousemove: move,
					touchmove: move,
					mouseup: stop,
					touchend: stop,
					touchcancel: stop,
					resize: updateImage
				});
			}
		;

		window.addEvent('domready', init);
	}
};

// Track the mousedown event
document.addEvent('mousedown', function(event) {
	Backend.getMousePosition(event);
});

// Initialize the back end script
window.addEvent('domready', function() {
	$(document.body).addClass('js');

	// Mark touch devices (see #5563)
	if (Browser.Features.Touch) {
		$(document.body).addClass('touch');
	}

	Backend.collapsePalettes();
	Backend.addInteractiveHelp();
	Backend.convertEnableModules();
	Backend.makeWizardsSortable();
	Backend.enableImageSizeWidgets();
	Backend.enableToggleSelect();

	// Chosen
	if (Elements.chosen != undefined) {
		$$('select.tl_chosen').chosen();
	}

	// Remove line wraps from textareas
	$$('textarea.monospace').each(function(el) {
		Backend.toggleWrap(el);
	});
});

// Limit the height of the preview fields
window.addEvent('load', function() {
	Backend.limitPreviewHeight();
});

// Re-apply certain changes upon ajax_change
window.addEvent('ajax_change', function() {
	Backend.addInteractiveHelp();
	Backend.makeWizardsSortable();
	Backend.enableImageSizeWidgets();
	Backend.enableToggleSelect();

	// Chosen
	if (Elements.chosen != undefined) {
		$$('select.tl_chosen').filter(function(el) {
			return el.getStyle('display') != 'none';
		}).chosen();
	}
});
