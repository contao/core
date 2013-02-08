/**
 * Contao Open Source CMS
 * Copyright (C) 2005-2013 Leo Feyer
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
 * PHP version 5
 * @copyright  Leo Feyer 2005-2013
 * @author     Leo Feyer <https://contao.org>
 * @package    Backend
 * @license    LGPL
 * @filesource
 */


/**
 * Class Request.Contao
 * 
 * Extend the basic Request.JSON class.
 * @copyright  Leo Feyer 2005-2013
 * @author     Leo Feyer <https://contao.org>
 * @package    Backend
 */
Request.Contao = new Class(
{
	Extends: Request.JSON,

	options: {
		'url': window.location.href
	},

	initialize: function(options) {
		if (options) {
			// Try to replace the URL with the form action
			try	{
				this.options.url = options.field.getParent('form').getAttribute('action');
			}
			catch(e) {}
		}
		this.parent(options);
	},

	success: function(text) {
		var json;

		// Support both plain text and JSON responses
		try	{
			json = this.response.json = JSON.decode(text, this.options.secure);
		} catch(e) {
			json = {'content':text};
		}

		// Empty response
		if (json == null) {
			json = {'content':''};
		}

		// Isolate scripts and execute them
		if (json.content != '') {
			json.content = json.content.stripScripts(function(script) {
				json.javascript = script.replace(/<!--|\/\/-->|<!\[CDATA\[\/\/>|<!\]\]>/g, '');
			});
			if (json.javascript && this.options.evalScripts) {
				$exec(json.javascript);
			}
		}

		this.onSuccess(json.content, json);
	}
});

// Backwards compatibility
Request.Mixed = Request.Contao;


/**
 * Class AjaxRequest
 *
 * Provide methods to handle Ajax requests.
 * @copyright  Leo Feyer 2005-2013
 * @author     Leo Feyer <https://contao.org>
 * @package    Backend
 */
var AjaxRequest =
{
	/**
	 * Toggle the navigation menu
	 * @param object
	 * @param string
	 * @return boolean
	 */
	toggleNavigation: function(el, id) {
		el.blur();
		var item = $(id);
		var image = $(el).getFirst('img');

		if (item) {
			if (item.getStyle('display') == 'none') {
				item.setStyle('display', 'inline');
				image.src = image.src.replace('modPlus.gif', 'modMinus.gif');
				$(el).title = CONTAO_COLLAPSE;
				new Request.Contao().post({'action':'toggleNavigation', 'id':id, 'state':1, 'REQUEST_TOKEN':REQUEST_TOKEN});
			} else {
				item.setStyle('display', 'none');
				image.src = image.src.replace('modMinus.gif', 'modPlus.gif');
				$(el).title = CONTAO_EXPAND;
				new Request.Contao().post({'action':'toggleNavigation', 'id':id, 'state':0, 'REQUEST_TOKEN':REQUEST_TOKEN});
			}
			return false;
		}

		new Request.Contao({
			evalScripts: true,
			onRequest: AjaxRequest.displayBox(CONTAO_LOADING + ' …'),
			onSuccess: function(txt, json) {
				item = new Element('li', {
					'id': id,
					'class': 'tl_parent',
					'html': txt,
					'styles': {
						'display': 'inline'
					}
				}).inject($(el).getParent('li'), 'after');

				$(el).title = CONTAO_COLLAPSE;
				image.src = image.src.replace('modPlus.gif', 'modMinus.gif');
				AjaxRequest.hideBox();

				// HOOK
				window.fireEvent('ajax_change');
   			}
		}).post({'action':'loadNavigation', 'id':id, 'state':1, 'REQUEST_TOKEN':REQUEST_TOKEN});

		return false;
	},

	/**
	 * Toggle the site structure tree
	 * @param object
	 * @param string
	 * @param integer
	 * @param integer
	 * @return boolean
	 */
	toggleStructure: function (el, id, level, mode) {
		el.blur();
		var item = $(id);
		var image = $(el).getFirst('img');

		if (item) {
			if (item.getStyle('display') == 'none') {
				item.setStyle('display', 'inline');
				image.src = image.src.replace('folPlus.gif', 'folMinus.gif');
				$(el).title = CONTAO_COLLAPSE;
				new Request.Contao({field:el}).post({'action':'toggleStructure', 'id':id, 'state':1, 'REQUEST_TOKEN':REQUEST_TOKEN});
			} else {
				item.setStyle('display', 'none');
				image.src = image.src.replace('folMinus.gif', 'folPlus.gif');
				$(el).title = CONTAO_EXPAND;
				new Request.Contao({field:el}).post({'action':'toggleStructure', 'id':id, 'state':0, 'REQUEST_TOKEN':REQUEST_TOKEN});
			}
			return false;
		}

		new Request.Contao({
			field: el,
			evalScripts: true,
			onRequest: AjaxRequest.displayBox(CONTAO_LOADING + ' …'),
			onSuccess: function(txt, json) {
				var li = new Element('li', {
					'id': id,
					'class': 'parent',
					'styles': {
						'display': 'inline'
					}
				});

				var ul = new Element('ul', {
					'class': 'level_' + level,
					'html': txt
				}).inject(li, 'bottom');

				if (mode == 5) {
					li.inject($(el).getParent('li'), 'after');
				} else {
					var folder = false;
					var parent = $(el).getParent('li');

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

				$(el).title = CONTAO_COLLAPSE;
				image.src = image.src.replace('folPlus.gif', 'folMinus.gif');
				window.fireEvent('structure');
				AjaxRequest.hideBox();

				// HOOK
				window.fireEvent('ajax_change');
   			}
		}).post({'action':'loadStructure', 'id':id, 'level':level, 'state':1, 'REQUEST_TOKEN':REQUEST_TOKEN});

		return false;
	},

	/**
	 * Toggle the file manager tree
	 * @param object
	 * @param string
	 * @param string
	 * @param integer
	 * @return boolean
	 */
	toggleFileManager: function (el, id, folder, level) {
		el.blur();
		var item = $(id);
		var image = $(el).getFirst('img');
		var icon = $(el).getNext('img');

		if (item) {
			if (item.getStyle('display') == 'none') {
				item.setStyle('display', 'inline');
				image.src = image.src.replace('folPlus.gif', 'folMinus.gif');
				icon.src = icon.src.replace('folderC', 'folderO');
				$(el).title = CONTAO_COLLAPSE;
				new Request.Contao({field:el}).post({'action':'toggleFileManager', 'id':id, 'state':1, 'REQUEST_TOKEN':REQUEST_TOKEN});
			} else {
				item.setStyle('display', 'none');
				image.src = image.src.replace('folMinus.gif', 'folPlus.gif');
				icon.src = icon.src.replace('folderO', 'folderC');
				$(el).title = CONTAO_EXPAND;
				new Request.Contao({field:el}).post({'action':'toggleFileManager', 'id':id, 'state':0, 'REQUEST_TOKEN':REQUEST_TOKEN});
			}
			return false;
		}

		new Request.Contao({
			field: el,
			evalScripts: true,
			onRequest: AjaxRequest.displayBox(CONTAO_LOADING + ' …'),
			onSuccess: function(txt, json) {
				var li = new Element('li', {
					'id': id,
					'class': 'parent',
					'styles': {
						'display': 'inline'
					}
				});

				var ul = new Element('ul', {
					'class': 'level_' + level,
					'html': txt
				}).inject(li, 'bottom');

				li.inject($(el).getParent('li'), 'after');
				$(el).title = CONTAO_COLLAPSE;
				image.src = image.src.replace('folPlus.gif', 'folMinus.gif');
				icon.src = icon.src.replace('folderC.gif', 'folderO.gif');
				AjaxRequest.hideBox();

				// HOOK
				window.fireEvent('ajax_change');
   			}
		}).post({'action':'loadFileManager', 'id':id, 'level':level, 'folder':folder, 'state':1, 'REQUEST_TOKEN':REQUEST_TOKEN});

		return false;
	},

	/**
	 * Toggle the page tree input field
	 * @param object
	 * @param string
	 * @param string
	 * @param string
	 * @param integer
	 * @return boolean
	 */
	togglePagetree: function (el, id, field, name, level) {
		el.blur();
		var item = $(id);
		var image = $(el).getFirst('img');

		if (item) {
			if (item.getStyle('display') == 'none') {
				item.setStyle('display', 'inline');
				image.src = image.src.replace('folPlus.gif', 'folMinus.gif');
				$(el).title = CONTAO_COLLAPSE;
				new Request.Contao({field:el}).post({'action':'togglePagetree', 'id':id, 'state':1, 'REQUEST_TOKEN':REQUEST_TOKEN});
			} else {
				item.setStyle('display', 'none');
				image.src = image.src.replace('folMinus.gif', 'folPlus.gif');
				$(el).title = CONTAO_EXPAND;
				new Request.Contao({field:el}).post({'action':'togglePagetree', 'id':id, 'state':0, 'REQUEST_TOKEN':REQUEST_TOKEN});
			}
			return false;
		}

		new Request.Contao({
			field: el,
			evalScripts: true,
			onRequest: AjaxRequest.displayBox(CONTAO_LOADING + ' …'),
			onSuccess: function(txt, json) {
				var li = new Element('li', {
					'id': id,
					'class': 'parent',
					'styles': {
						'display': 'inline'
					}
				});

				var ul = new Element('ul', {
					'class': 'level_' + level,
					'html': txt
				}).inject(li, 'bottom');

				li.inject($(el).getParent('li'), 'after');
				$(el).title = CONTAO_COLLAPSE;
				image.src = image.src.replace('folPlus.gif', 'folMinus.gif');
				AjaxRequest.hideBox();

				// HOOK
				window.fireEvent('ajax_change');
   			}
		}).post({'action':'loadPagetree', 'id':id, 'level':level, 'field':field, 'name':name, 'state':1, 'REQUEST_TOKEN':REQUEST_TOKEN});

		return false;
	},

	/**
	 * Toggle the file tree input field
	 * @param object
	 * @param string
	 * @param string
	 * @param string
	 * @param string
	 * @param integer
	 * @return boolean
	 */
	toggleFiletree: function (el, id, folder, field, name, level) {
		el.blur();
		var item = $(id);
		var image = $(el).getFirst('img');

		if (item) {
			if (item.getStyle('display') == 'none') {
				item.setStyle('display', 'inline');
				image.src = image.src.replace('folPlus.gif', 'folMinus.gif');
				$(el).title = CONTAO_COLLAPSE;
				new Request.Contao({field:el}).post({'action':'toggleFiletree', 'id':id, 'state':1, 'REQUEST_TOKEN':REQUEST_TOKEN});
			} else {
				item.setStyle('display', 'none');
				image.src = image.src.replace('folMinus.gif', 'folPlus.gif');
				$(el).title = CONTAO_EXPAND;
				new Request.Contao({field:el}).post({'action':'toggleFiletree', 'id':id, 'state':0, 'REQUEST_TOKEN':REQUEST_TOKEN});
			}
			return false;
		}

		new Request.Contao({
			field: el,
			evalScripts: true,
			onRequest: AjaxRequest.displayBox(CONTAO_LOADING + ' …'),
			onSuccess: function(txt, json) {
				var li = new Element('li', {
					'id': id,
					'class': 'parent',
					'styles': {
						'display': 'inline'
					}
				});

				var ul = new Element('ul', {
					'class': 'level_' + level,
					'html': txt
				}).inject(li, 'bottom');

				li.inject($(el).getParent('li'), 'after');
				$(el).title = CONTAO_COLLAPSE;
				image.src = image.src.replace('folPlus.gif', 'folMinus.gif');
				AjaxRequest.hideBox();

				// HOOK
				window.fireEvent('ajax_change');
   			}
		}).post({'action':'loadFiletree', 'id':id, 'level':level, 'folder':folder, 'field':field, 'name':name, 'state':1, 'REQUEST_TOKEN':REQUEST_TOKEN});

		return false;
	},

	/**
	 * Reload all file trees (input field)
	 */
	reloadFiletrees: function () {
		$$('.filetree').each(function(el) {
			var name = el.id;
			var field = name.replace(/_[0-9]+$/, '');

			new Request.Contao({
				evalScripts: true,
				onRequest: AjaxRequest.displayBox(CONTAO_LOADING + ' …'),
				onSuccess: function(txt, json) {

					// Preserve the "reset selection" entry
					var ul = $(el.id + '_parent').getFirst('ul');
					var li = ul.getLast('li');
					ul.set('html', txt);
					li.inject(ul, 'bottom');
					AjaxRequest.hideBox();

					// HOOK
					window.fireEvent('ajax_change');
				}
			}).post({'action':'loadFiletree', 'field':field, 'name':name, 'REQUEST_TOKEN':REQUEST_TOKEN});
		});
	},

	/**
	 * Toggle subpalettes in edit mode
	 * @param object
	 * @param string
	 * @param string
	 */
	toggleSubpalette: function (el, id, field) {
		el.blur();
		var item = $(id);

		if (item) {
			if (!el.value) {
				el.value = 1;
				el.checked = 'checked';
				item.setStyle('display', 'block');
				new Request.Contao({field:el}).post({'action':'toggleSubpalette', 'id':id, 'field':field, 'state':1, 'REQUEST_TOKEN':REQUEST_TOKEN});
			} else {
				el.value = '';
				el.checked = '';
				item.setStyle('display', 'none');
				new Request.Contao({field:el}).post({'action':'toggleSubpalette', 'id':id, 'field':field, 'state':0, 'REQUEST_TOKEN':REQUEST_TOKEN});
			}
			return;
		}

		new Request.Contao({
			field: el,
			evalScripts: false,
			onRequest: AjaxRequest.displayBox(CONTAO_LOADING + ' …'),
			onSuccess: function(txt, json) {
				var div = new Element('div', {
					'id': id,
					'html': txt,
					'styles': {
						'display': 'block'
					}
				}).inject($(el).getParent('div').getParent('div'), 'after');

				// Execute scripts after the DOM has been updated
				if (json.javascript) $exec(json.javascript);

				el.value = 1;
				el.checked = 'checked';

				AjaxRequest.hideBox();
				Backend.hideTreeBody();
				Backend.addInteractiveHelp();
				Backend.addColorPicker();

				// HOOK
				window.fireEvent('subpalette'); // Backwards compatibility
				window.fireEvent('ajax_change');
			}
		}).post({'action':'toggleSubpalette', 'id':id, 'field':field, 'load':1, 'state':1, 'REQUEST_TOKEN':REQUEST_TOKEN});
	},

	/**
	 * Toggle the visibility of an element
	 * @param object
	 * @param string
	 * @param string
	 * @return boolean
	 */
	toggleVisibility: function(el, id, table) {
		el.blur();
		var img = null;
		var image = $(el).getFirst('img');
		var publish = (image.src.indexOf('invisible') != -1);
		var div = el.getParent('div');

		// Find the icon depending on the view (tree view, list view, parent view)
		if (div.hasClass('tl_right')) {
			img = div.getPrevious('div').getElement('img');
		} else if (div.hasClass('tl_listing_container')) {
			img = el.getParent('td').getPrevious('td').getFirst('div.list_icon');
			if (img == null) { // Comments
				img = el.getParent('td').getPrevious('td').getElement('div.cte_type');
			}
			if (img == null) { // showColumns
				img = el.getParent('tr').getFirst('td').getElement('div.list_icon_new');
			}
		} else if ((next = div.getNext('div')) && next.hasClass('cte_type')) {
			img = next;
		}

		// Change the icon
		if (img != null) {
			// Tree view
			if (img.nodeName.toLowerCase() == 'img') {
				if (img.getParent('ul.tl_listing').hasClass('tl_tree_xtnd')) {
					if (publish) {
						img.src = img.src.replace(/_\.(gif|png|jpe?g)/, '.$1');
					} else {
						img.src = img.src.replace(/\.(gif|png|jpe?g)/, '_.$1');
					}
				} else {
					if (img.src.match(/folPlus|folMinus/)) {
						if (img.getParent('a').getNext('a')) {
							img = img.getParent('a').getNext('a').getFirst('img');
						} else {
							img = new Element('img'); // no icons used (see #2286)
						}
					}
					if (publish) {
						var index = img.src.replace(/.*_([0-9])\.(gif|png|jpe?g)/, '$1');
						img.src = img.src.replace(/_[0-9]\.(gif|png|jpe?g)/, ((index.toInt() == 1) ? '' : '_' + (index.toInt() - 1)) + '.$1');
					} else {
						var index = img.src.replace(/.*_([0-9])\.(gif|png|jpe?g)/, '$1');
						img.src = img.src.replace(/(_[0-9])?\.(gif|png|jpe?g)/, ((index == img.src) ? '_1' : '_' + (index.toInt() + 1)) + '.$2');
					}
				}
			}
			// Parent view
			else if (img.hasClass('cte_type')) {
				if (publish) {
					img.addClass('published');
					img.removeClass('unpublished');
				} else {
					img.addClass('unpublished');
					img.removeClass('published');
				}
			}
			// List view
			else {
				if (publish) {
					img.setStyle('background-image', img.getStyle('background-image').replace(/_\.(gif|png|jpe?g)/, '.$1'));
				} else {
					img.setStyle('background-image', img.getStyle('background-image').replace(/\.(gif|png|jpe?g)/, '_.$1'));
				}
			}
		}

		// Mark disabled format definitions
		if (table == 'tl_style') {
			div.getParent('div').getElement('pre').toggleClass('disabled');
		}

		// Send request
		if (publish) {
			image.src = image.src.replace('invisible.gif', 'visible.gif');
			new Request({'url':window.location.href}).get({'tid':id, 'state':1});
		} else {
			image.src = image.src.replace('visible.gif', 'invisible.gif');
			new Request({'url':window.location.href}).get({'tid':id, 'state':0});
		}

		return false;
	},

	/**
	 * Feature/unfeature an element
	 * @param object
	 * @param string
	 * @return boolean
	 */
	toggleFeatured: function(el, id) {
		el.blur();
		var image = $(el).getFirst('img');
		var featured = (image.src.indexOf('featured_') == -1);

		// Send the request
		if (!featured) {
			image.src = image.src.replace('featured_.gif', 'featured.gif');
			new Request.Contao().post({'action':'toggleFeatured', 'id':id, 'state':1, 'REQUEST_TOKEN':REQUEST_TOKEN});
		} else {
			image.src = image.src.replace('featured.gif', 'featured_.gif');
			new Request.Contao().post({'action':'toggleFeatured', 'id':id, 'state':0, 'REQUEST_TOKEN':REQUEST_TOKEN});
		}

		return false;
	},

	/**
	 * Toggle the visibility of a fieldset
	 * @param object
	 * @param string
	 * @param string
	 * @return boolean
	 */
	toggleFieldset: function(el, id, table) {
		el.blur();
		var fs = $('pal_' + id);

		if (fs.hasClass('collapsed')) {
			fs.removeClass('collapsed');
			new Request.Contao().post({'action':'toggleFieldset', 'id':id, 'table':table, 'state':1, 'REQUEST_TOKEN':REQUEST_TOKEN});
		} else {
			fs.addClass('collapsed');
			new Request.Contao().post({'action':'toggleFieldset', 'id':id, 'table':table, 'state':0, 'REQUEST_TOKEN':REQUEST_TOKEN});
		}

		return false;
	},

	/**
	 * Toggle a group of a multi-checkbox field
	 * @param object
	 * @param string
	 * @return boolean
	 */
	toggleCheckboxGroup: function(el, id) {
		el.blur();
		var item = $(id);
		var image = $(el).getFirst('img');

		if (item) {
			if (item.getStyle('display') != 'block') {
				item.setStyle('display', 'block');
				image.src = image.src.replace('folPlus.gif', 'folMinus.gif');
				new Request.Contao().post({'action':'toggleCheckboxGroup', 'id':id, 'state':1, 'REQUEST_TOKEN':REQUEST_TOKEN});
			} else {
				item.setStyle('display', 'none');
				image.src = image.src.replace('folMinus.gif', 'folPlus.gif');
				new Request.Contao().post({'action':'toggleCheckboxGroup', 'id':id, 'state':0, 'REQUEST_TOKEN':REQUEST_TOKEN});
			}
			return true;
		}

		return false;
	},

	/**
	 * Store the live update ID
	 * @param string
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
		}).post({'action':'liveUpdate', 'id':uid.value, 'REQUEST_TOKEN':REQUEST_TOKEN});
	},

	/**
	 * Display the "loading data" message
	 * @param string
	 */
	displayBox: function(message) {
		var box = $('tl_ajaxBox');
		var overlay = $('tl_ajaxOverlay');
		var scroll = window.getScroll();

		if (overlay == null) {
			overlay = new Element('div', {
				'id': 'tl_ajaxOverlay'
			}).inject($(document.body), 'bottom');
		}

		overlay.set({
			'styles': {
				'display': 'block',
				'top': scroll.y + 'px'
			}
		})

		if (box == null) {
			box = new Element('div', {
				'id': 'tl_ajaxBox'
			}).inject($(document.body), 'bottom');
		}

		box.set({
			'html': message,
			'styles': {
				'display': 'block',
				'top': (scroll.y + 18) + 'px'
			}
		})
	},

	/**
	 * Hide the "loading data" message
	 */
	hideBox: function() {
		var box = $('tl_ajaxBox');
		var overlay = $('tl_ajaxOverlay');

		if (overlay) {
			overlay.setStyle('display', 'none');
		}

		if (box) {
			box.setStyle('display', 'none');
		}
	}
};


/**
 * Class Backend
 *
 * Provide methods to handle back end tasks.
 * @copyright  Leo Feyer 2005-2013
 * @author     Leo Feyer <https://contao.org>
 * @package    Backend
 */
var Backend =
{
	/**
	 * Properties
	 */
	currentId: null,
	xMousePosition: 0,
	yMousePosition: 0,
	popupWindow: null,

	/**
	 * Get the current mouse position
	 * @param object
	 */
	getMousePosition: function(event) {
		Backend.xMousePosition = event.client.x;
		Backend.yMousePosition = event.client.y;
	},

	/**
	 * Open a new window (used by file trees and the help wizard)
	 * @param object
	 * @param integer
	 * @param integer
	 */
	openWindow: function(el, width, height) {
		el.blur();
		width = Browser.Engine.trident ? (width + 40) : (width + 17);
		height = Browser.Engine.trident ? (height + 30) : (height + 17);
		Backend.popupWindow = window.open(el.href, '', 'width='+width+',height='+height+',modal=yes,left=100,top=50,location=no,menubar=no,resizable=yes,scrollbars=yes,status=no,toolbar=no');
	},

	/**
	 * Get the current scroll offset and store it in a cookie
	 */
	getScrollOffset: function() {
		document.cookie = "BE_PAGE_OFFSET=" + window.getScroll().y + "; path=" + (CONTAO_PATH || '/');
	},

	/**
	 * Automatically submit a form
	 * @param object
	 */
	autoSubmit: function(el) {
		Backend.getScrollOffset();

		var hidden = new Element('input', {
			'type': 'hidden',
			'name': 'SUBMIT_TYPE',
			'value': 'auto'
		});

		var form = $(el);
		hidden.inject(form, 'bottom');
		form.submit();
	},

	/**
	 * Scroll the window to a certain vertical position
	 * @param integer
	 */
	vScrollTo: function(offset) {
		window.addEvent('load', function() {
			window.scrollTo(null, parseInt(offset));
		});
	},

	/**
	 * Show all pagetree and filetree nodes
	 * @param object
	 * @param string
	 */
	showTreeBody: function(el, id) {
		el.blur();
		$(id).setStyle('display', ($(el).checked ? 'inline' : 'none'));
	},

	/**
	 * Hide all pagetree and filetree nodes
	 */
	hideTreeBody: function() {
		var lists = $$('ul');
		var parent = null;

		for (var i=0; i<lists.length; i++) {
			if (lists[i].hasClass('mandatory')) {
				$('ctrl_' + lists[i].id).checked = 'checked';
			} else if (lists[i].hasClass('tl_listing') && (parent = lists[i].getFirst('li').getNext('li')) && parent.hasClass('parent')) {
				parent.setStyle('display', 'none');
			}
		}
	},

	/**
	 * Limit the height of the preview pane
	 */
	limitPreviewHeight: function() {
		var size = null;
		var toggler = null;
		var style = '';
		var hgt = 0;

		$$('div.limit_height').each(function(div) {
			size = div.getCoordinates();

			if (hgt == 0) {
				hgt = div.className.replace(/[^0-9]*/, '').toInt();
			}

			// Return if there is no height value
			if (!$chk(hgt)) return;

			div.setStyle('height', hgt);
			var path = CONTAO_SCRIPT_URL + 'system/themes/' + CONTAO_THEME + '/images/';

			toggler = new Element('img', {
				'class': 'limit_toggler',
				'alt': '',
				'width': 20,
				'height': 24
			});

			// Disable the function if the preview height is below the max-height
			if (size.height < hgt) {
				toggler.src = path + 'expand_.gif';
				toggler.inject(div, 'after');
				return;
			}

			toggler.src = path + 'expand.gif';
			toggler.setStyle('cursor', 'pointer');

			toggler.addEvent('click', function() {
				style = this.getPrevious('div').getStyle('height').toInt();
				this.getPrevious('div').setStyle('height', ((style > hgt) ? hgt : ''));
				this.src = (this.src.indexOf('expand.gif') != -1) ? path + 'collapse.gif' : path + 'expand.gif';
			});

			toggler.inject(div, 'after');
		});
	},

	/**
	 * Toggle checkboxes
	 * @param object
	 * @param string
	 */
	toggleCheckboxes: function(trigger, id) {
		var items = $$('input');
		var status = trigger.checked ? 'checked' : '';

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
	 * Toggle checkbox group
	 * @param object
	 * @param string
	 */
	toggleCheckboxGroup: function(el, id) {
		var cls = $(el).className;
		var status = $(el).checked ? 'checked' : '';

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
	 * @param string
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
	 * Toggle textarea line wrap
	 * @param string
	 */
	toggleWrap: function(id) {
		var textarea = $(id);
		var status = (textarea.getProperty('wrap') == 'off') ? 'soft' : 'off';
		textarea.setProperty('wrap', status);

		if (!Browser.Engine.trident) {
			var v = textarea.value;
			var n = textarea.clone();
			n.setProperty('wrap', status);
			n.setProperty('id', $(id).getProperty('id'));
			n.value = v;
			n.replaces(textarea);
		}
	},

	/**
	 * Toggle opacity
	 * @param string
	 */
	blink: function() {
		var remove = null;

		$$('img.blink').each(function(el) {
			if (remove == null) {
				remove = el.hasClass('opacity');
			}
			if (remove) {
				el.removeClass('opacity');
			} else {
				el.addClass('opacity');
			}
		});
	},

	/**
	 * Initialize the mootools color picker
	 */
	addColorPicker: function() {
		/*
		 * FIXME: replace with http://www.nogray.com/color_picker.php
		 * as soon as the new version has been released
		 */
	},

	/**
	 * Open the page picker wizard in a modal window
	 * @param string
	 */
	pickPage: function(id) {
		var width = 320;
		var height = 112;

		Backend.currentId = id;
		Backend.ppValue = $(id).value;

		Backend.getScrollOffset();
		window.open($$('base')[0].href + 'contao/page.php?value=' + Backend.ppValue, '', 'width='+width+',height='+height+',modal=yes,left='+(Backend.xMousePosition ? (Backend.xMousePosition-(width/2)) : 200)+',top='+(Backend.yMousePosition ? (Backend.yMousePosition-(height/2)+80) : 100)+',location=no,menubar=no,resizable=yes,scrollbars=no,status=no,toolbar=no');
	},

	/**
	 * Open the file picker wizard in a modal window
	 * @param string
	 * @param string
	 */
	pickFile: function(id, filter) {
		var width = 320;
		var height = 112;

		Backend.currentId = id;
		Backend.ppValue = $(id).value;

		Backend.getScrollOffset();
		window.open($$('base')[0].href + 'contao/file.php?value=' + Backend.ppValue + '&filter=' + filter, '', 'width='+width+',height='+height+',modal=yes,left='+(Backend.xMousePosition ? (Backend.xMousePosition-(width/2)) : 200)+',top='+(Backend.yMousePosition ? (Backend.yMousePosition-(height/2)+80) : 100)+',location=no,menubar=no,resizable=yes,scrollbars=no,status=no,toolbar=no');
	},

	/**
	 * Collapse palettes
	 * @param string
	 */
	collapsePalettes: function(id) {
		$$('fieldset.hide').each(function(el) {
			el.addClass('collapsed');
		});
		$$('label.error', 'label.mandatory').each(function(el) {
			if (fs = el.getParent('fieldset')) {
				fs.removeClass('collapsed');
			}
		});
	},

	/**
	 * Add the interactive help
	 */
	addInteractiveHelp: function() {
		$$('p.tl_tip').each(function(el) {
			if (el.retrieve('complete')) {
				return;
			}

			el.addEvent('mouseover', function() {
				el.timo = setTimeout(function() {
					var scroll = el.getTop();
					var box = $('tl_helpBox');

					if (!box) {
						box = new Element('div', {
							'id': 'tl_helpBox'
						}).inject($(document.body), 'after');
					}

					box.set({
						'html': el.get('html'),
						'styles': {
							'display': 'block',
							'top': (scroll + 18) + 'px'
						}
					})
				}, 1000);
			});

			el.addEvent('mouseout', function() {
				var box = $('tl_helpBox');
				if (box) {
					box.setStyle('display', 'none');
				}
				clearTimeout(el.timo);
			});

			el.store('complete', true);
		});
	},

	/**
	 * Make parent view items sortable
	 * @param object
	 */
	makeParentViewSortable: function(ul) {
		var list = new Sortables(ul, {
			contstrain: true,
			opacity: 0.6
		});

		list.active = false;

		list.addEvent('start', function() {
			list.active = true;
		});

		list.addEvent('complete', function(el) {
	    	if (!list.active) return;

    		if (el.getPrevious('li')) {
    			var id = el.get('id').replace(/li_/, '');
    			var pid = el.getPrevious('li').get('id').replace(/li_/, '');
    			var req = window.location.search.replace(/id=[0-9]*/, 'id=' + id) + '&act=cut&mode=1&pid=' + pid;
    			var href = window.location.href.replace(/\?.*$/, '');
    			new Request({'url':href+req}).get();
    		} else if (el.getParent('ul')) {
    			var id = el.get('id').replace(/li_/, '');
    			var pid = el.getParent('ul').get('id').replace(/ul_/, '');
    			var req = window.location.search.replace(/id=[0-9]*/, 'id=' + id) + '&act=cut&mode=2&pid=' + pid;
    			var href = window.location.href.replace(/\?.*$/, '');
    			new Request({'url':href+req}).get();
    		}
    	});
	},

	/**
	 * List wizard
	 * @param object
	 * @param string
	 * @param string
	 */
	listWizard: function(el, command, id) {
		var list = $(id);
		var parent = $(el).getParent('li');
		var items = list.getChildren();
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
				}
				break;
		}

		rows = list.getChildren();
		var tabindex = 1;

		for (var i=0; i<rows.length; i++) {
			if (input = rows[i].getFirst('input[type="text"]')) {
				input.set('tabindex', tabindex++);
			}
		}
	},

	/**
	 * Table wizard
	 * @param object
	 * @param string
	 * @param string
	 */
	tableWizard: function(el, command, id) {
		var table = $(id);
		var tbody = table.getElement('tbody');
		var rows = tbody.getChildren();
		var parentTd = $(el).getParent('td');
		var parentTr = parentTd.getParent('tr');
		var cols = parentTr.getChildren();
		var index = 0;

		for (var i=0; i<cols.length; i++) {
			if (cols[i] == parentTd) {
				break;
			}
			index++;
		}

		Backend.getScrollOffset();

		switch (command) {
			case 'rcopy':
				var tr = new Element('tr');
				for (var i=0; i<cols.length; i++) {
					var next = cols[i].clone(true).inject(tr, 'bottom');
					if (textarea = cols[i].getFirst('textarea')) {
						next.getFirst('textarea').value = textarea.value;
					}
				}
				tr.inject(parentTr, 'after');
				break;
			case 'rup':
				var previous = parentTr.getPrevious('tr');
				if (previous.getPrevious('tr')) {
					parentTr.inject(previous, 'before');
				} else {
					parentTr.inject(tbody, 'bottom')
				}
				break;
			case 'rdown':
				if (next = parentTr.getNext('tr')) {
					parentTr.inject(next, 'after');
				} else {
					parentTr.inject(tbody.getFirst('tr').getNext('tr'), 'before');
				}
				break;
			case 'rdelete':
				if (rows.length > 2) {
					parentTr.destroy();
				}
				break;
			case 'ccopy':
				for (var i=0; i<rows.length; i++) {
					var current = rows[i].getChildren()[index];
					var next = current.clone(true).inject(current, 'after');
					if (textarea = current.getFirst('textarea')) {
						next.getFirst('textarea').value = textarea.value;
					}
				}
				break;
			case 'cmovel':
				if (index > 0) {
					for (var i=0; i<rows.length; i++) {
						var current = rows[i].getChildren()[index];
						current.inject(current.getPrevious(), 'before');
					}
				} else {
					for (var i=0; i<rows.length; i++) {
						var current = rows[i].getChildren()[index];
						current.inject(rows[i].getLast(), 'before');
					}
				}
				break;
			case 'cmover':
				if (index < (cols.length - 2)) {
					for (var i=0; i<rows.length; i++) {
						var current = rows[i].getChildren()[index];
						current.inject(current.getNext(), 'after');
					}
				} else {
					for (var i=0; i<rows.length; i++) {
						var current = rows[i].getChildren()[index];
						current.inject(rows[i].getFirst(), 'before');
					}
				}
				break;
			case 'cdelete':
				if (cols.length > 2) {
					for (var i=0; i<rows.length; i++) {
						rows[i].getChildren()[index].destroy();
					}
				}
				break;
		}

		rows = tbody.getChildren();
		var tabindex = 1;

		for (var i=0; i<rows.length; i++) {
			var childs = rows[i].getChildren();
			for (var j=0; j<childs.length; j++) {
				if (textarea = childs[j].getFirst('textarea')) {
					textarea.set('tabindex', tabindex++);
					textarea.name = textarea.name.replace(/\[[0-9]+\][[0-9]+\]/ig, '[' + (i-1) + '][' + j + ']')
				}
			}
		}

		Backend.tableWizardResize();
	},

	/**
	 * Resize the table wizard fields on focus
	 * @param float
	 */
	tableWizardResize: function(factor) {
		var size = Cookie.read('BE_CELL_SIZE');
		if (size == null && factor == null) return;

		if (factor != null) {
			var size = '';
			$$('.tl_tablewizard textarea').each(function(el) {
				el.setStyle('width', (el.getStyle('width').toInt() * factor).round().limit(142, 284));
				el.setStyle('height', (el.getStyle('height').toInt() * factor).round().limit(66, 132));
				if (size == '') {
					size = el.getStyle('width') + '|' + el.getStyle('height');
				}
			});
			Cookie.write('BE_CELL_SIZE', size);
		} else if (size != null) {
			var chunks = size.split('|');
			$$('.tl_tablewizard textarea').each(function(el) {
				el.setStyle('width', chunks[0]);
				el.setStyle('height', chunks[1]);
			});
		}
	},

	/**
	 * Module wizard
	 * @param object
	 * @param string
	 * @param string
	 */
	moduleWizard: function(el, command, id) {
		var table = $(id);
		var tbody = table.getElement('tbody');
		var parent = $(el).getParent('tr');
		var rows = tbody.getChildren();
		Backend.getScrollOffset();

		switch (command) {
			case 'copy':
				var tr = new Element('tr');
				var childs = parent.getChildren();
				for (var i=0; i<childs.length; i++) {
					var next = childs[i].clone(true).inject(tr, 'bottom');
					if (select = childs[i].getFirst('select')) {
						next.getFirst('select').value = select.value;
					}
				}
				tr.inject(parent, 'after');
				tr.getElement('.chzn-container').destroy();
				new Chosen(tr.getElement('select.tl_select'));
				Stylect.convertSelects();
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
		var tabindex = 1;

		for (var i=0; i<rows.length; i++) {
			var childs = rows[i].getChildren();
			for (var j=0; j<childs.length; j++) {
				if (select = childs[j].getFirst('select')) {
					select.set('tabindex', tabindex++);
					select.name = select.name.replace(/\[[0-9]+\]/ig, '[' + i + ']');
				}
			}
		}
	},

	/**
	 * Options wizard
	 * @param object
	 * @param string
	 * @param string
	 */
	optionsWizard: function(el, command, id) {
		var table = $(id);
		var tbody = table.getElement('tbody');
		var parent = $(el).getParent('tr');
		var rows = tbody.getChildren();
		Backend.getScrollOffset();

		switch (command) {
			case 'copy':
				var tr = new Element('tr');
				var childs = parent.getChildren();
				for (var i=0; i<childs.length; i++) {
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
		var fieldnames = ['value', 'label', 'default'];
		var tabindex = 1;

		for (var i=0; i<rows.length; i++) {
			var childs = rows[i].getChildren();
			for (var j=0; j<childs.length; j++) {
				if (input = childs[j].getFirst('input')) {
					input.set('tabindex', tabindex++);
					input.name = input.name.replace(/\[[0-9]+\]/g, '[' + i + ']')
					if (input.type == 'checkbox') {
						input.id = input.name.replace(/\[[0-9]+\]/g, '').replace(/\[/g, '_').replace(/\]/g, '') + '_' + i;
						input.getNext('label').set('for', input.id);
					}
				}
			}
		}
	},

	/**
	 * Key/value wizard
	 * @param object
	 * @param string
	 * @param string
	 */
	keyValueWizard: function(el, command, id) {
		var table = $(id);
		var tbody = table.getElement('tbody');
		var parent = $(el).getParent('tr');
		var rows = tbody.getChildren();
		Backend.getScrollOffset();

		switch (command) {
			case 'copy':
				var tr = new Element('tr');
				var childs = parent.getChildren();
				for (var i=0; i<childs.length; i++) {
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
		var fieldnames = ['key', 'value'];
		var tabindex = 1;

		for (var i=0; i<rows.length; i++) {
			var childs = rows[i].getChildren();
			for (var j=0; j<childs.length; j++) {
				if (input = first = childs[j].getFirst('input')) {
					input.set('tabindex', tabindex++);
					input.name = input.name.replace(/\[[0-9]+\]/g, '[' + i + ']')
				}
			}
		}
	},

	/**
	 * Checkbox wizard
	 * @param object
	 * @param string
	 * @param string
	 */
	checkboxWizard: function(el, command, id) {
		var container = $(id);
		var parent = $(el).getParent('span');
		Backend.getScrollOffset();

		switch (command) {
			case 'up':
				if ((span = parent.getPrevious('span')) && !span.hasClass('fixed')) {
					parent.inject(span, 'before');
				} else {
					parent.inject(container, 'bottom');
				}
				break;
			case 'down':
				if (span = parent.getNext('span')) {
					parent.inject(span, 'after');
				} else if (all = container.getFirst('span.fixed')) {
					parent.inject(all, 'after');
				}
				break;
		}
	},

	/**
	 * Update the "edit module" links in the module wizard
	 * @param object
	 */
	updateModuleLink: function(el) {
		var td = el.getParent('td').getNext('td');
		var a = td.getElement('a.module_link');

		a.href = a.href.replace(/id=[0-9]+/, 'id=' + el.value);

		if (el.value > 0) {
			td.getElement('a.module_link').setStyle('display', 'inline');
			td.getElement('img.module_image').setStyle('display', 'none');
		} else {
			td.getElement('a.module_link').setStyle('display', 'none');
			td.getElement('img.module_image').setStyle('display', 'inline');
		}
	}
};

// Track the mousedown event
document.addEvent('mousedown', function(event) {
	Backend.getMousePosition(event);
});

// Initialize the back end script
window.addEvent('domready', function() {
	// Chosen
	if (Elements.chosen != undefined) {
		$$('select.tl_chosen').chosen();
	}

	Backend.hideTreeBody();
	Backend.blink.periodical(600);

	// Remove line wraps from textareas
	$$('textarea.monospace').each(function(el) {
		Backend.toggleWrap(el);
	});

	Backend.collapsePalettes();
	Backend.addInteractiveHelp();
	Backend.addColorPicker();
});

// Limit the height of the preview fields
window.addEvent('load', function() {
	Backend.limitPreviewHeight();
});


/**
 * Class ContextMenu
 *
 * Provide methods to handle context menus
 * @copyright  Leo Feyer 2005-2013
 * @author     Leo Feyer <https://contao.org>
 * @package    Backend
 */
var ContextMenu =
{
	/**
	 * Initialize the context menu
	 * @param object
	 * @param object
	 */
	initialize: function() {
		// Hide the edit header buttons
		$$('a.edit-header').each(function(el) {
			el.addClass('invisible');
		});

		// Add a trigger to the edit buttons
		$$('a.contextmenu').each(function(el) {
			var el2 = el.getNext('a');

			// Return if there is no edit header button
			if (!el2 || !el2.hasClass('edit-header')) {
				return;
			}

			// Show the context menu
			el.addEvent('contextmenu', function(e) {
				e.preventDefault();
				ContextMenu.show(el, el2, e);
			});
		});

		// Hide the context menu 
		$(document.body).addEvent('click', function() {
			ContextMenu.hide();
		});
	},

	/**
	 * Show the context menu
	 * @param object
	 * @param object
	 * @param object
	 */
	show: function(el, el2, e) {
		ContextMenu.hide();
		var img = el.getFirst('img');
		var im2 = el2.getFirst('img');

		var div = new Element('div', {
			'id': 'contextmenu',
			'html': '<a href="'+ el.href +'" title="'+ el.title +'">'+ el.get('html') +' '+ img.alt +'</a><a href="'+ el2.href +'" title="'+ el2.title +'">'+ el2.get('html') +' '+ im2.alt +'</a>',
			'styles': {
				'top': (el.getPosition().y - 6) + 'px'
			}
		}).inject($(document.body), 'bottom');

		// Set the left position after the element has been generated!
		div.setStyle('left', el.getPosition().x - (div.getSize().x / 2));
	},

	/**
	 * Hide the context menu
	 */
	hide: function() {
		if ($('contextmenu') != null) {
			$('contextmenu').destroy();
		}
	}
};

// Initialize the context menu
window.addEvent('domready', function() {
	ContextMenu.initialize();
});
window.addEvent('structure', function() {
	ContextMenu.initialize();
});


/**
 * Class TinyCallback
 *
 * Provide callback functions for TinyMCE.
 * @copyright  Leo Feyer 2005-2013
 * @author     Leo Feyer <https://contao.org>
 * @package    Backend
 */
var TinyCallback =
{
	/**
	 * Get the scroll offset of the editor
	 * @param object
	 */
	getScrollOffset: function(ed) {
		tinymce.dom.Event.add((tinymce.isGecko ? ed.getDoc() : ed.getWin()), 'focus', function(e) {
			Backend.getScrollOffset();
	    });
	}
};