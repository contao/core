/**
 * TYPOlight webCMS
 * Copyright (C) 2005 Leo Feyer
 *
 * This program is free software: you can redistribute it and/or
 * modify it under the terms of the GNU Lesser General Public
 * License as published by the Free Software Foundation, either
 * version 2.1 of the License, or (at your option) any later version.
 * 
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU
 * Lesser General Public License for more details.
 * 
 * You should have received a copy of the GNU Lesser General Public
 * License along with this program. If not, please visit the Free
 * Software Foundation website at http://www.gnu.org/licenses/.
 *
 * PHP version 5
 * @copyright  Leo Feyer 2005
 * @author     Leo Feyer <leo@typolight.org>
 * @package    Backend
 * @license    LGPL
 * @filesource
 */


/**
 * Class AjaxRequest
 *
 * Provide methods to handle Ajax requests.
 * @copyright  Leo Feyer 2006
 * @author     Leo Feyer <leo@typolight.org>
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
	toggleNavigation: function(el, id)
	{
		el.blur();
		var item = $(id);
		var image = $(el).getFirst();

		if (item)
		{
			if (item.getStyle('display') != 'inline')
			{
				item.setStyle('display', 'inline');
				image.src = image.src.replace('modPlus.gif', 'modMinus.gif');
				new Ajax(window.location.href, {data: 'isAjax=1&action=toggleNavigation&id=' + id + '&state=1'}).request();
			}
			else
			{
				item.setStyle('display', 'none');
				image.src = image.src.replace('modMinus.gif', 'modPlus.gif');
				new Ajax(window.location.href, {data: 'isAjax=1&action=toggleNavigation&id=' + id + '&state=0'}).request();
			}

			return false;
		}

		new Ajax(window.location.href, 
		{
			data: 'isAjax=1&action=loadNavigation&id=' + id + '&state=1',
			onStateChange: AjaxRequest.displayBox('Loading data …'),

			onComplete: function(txt, xml)
			{
				item = new Element('li');

				item.addClass('tl_parent');
				item.setProperty('id', id);
				item.setHTML(txt);
				item.setStyle('display', 'inline');
				item.injectAfter($(el).getParent());

				image.src = image.src.replace('modPlus.gif', 'modMinus.gif');
				AjaxRequest.hideBox();
   			}
		}).request();

		return false;
	},


	/**
	 * Toggle the page structure tree
	 * @param object
	 * @param string
	 * @param integer
	 * @param integer
	 * @return boolean
	 */
	toggleStructure: function (el, id, level, mode)
	{
		el.blur();
		var item = $(id);
		var image = $(el).getFirst();

		if (item)
		{
			if (item.getStyle('display') != 'inline')
			{
				item.setStyle('display', 'inline');
				image.src = image.src.replace('folPlus.gif', 'folMinus.gif');
				new Ajax(window.location.href, {data: 'isAjax=1&action=toggleStructure&id=' + id + '&state=1'}).request();
			}
			else
			{
				item.setStyle('display', 'none');
				image.src = image.src.replace('folMinus.gif', 'folPlus.gif');
				new Ajax(window.location.href, {data: 'isAjax=1&action=toggleStructure&id=' + id + '&state=0'}).request();
			}

			return false;
		}

		new Ajax(window.location.href, 
		{
			data: 'isAjax=1&action=loadStructure&id=' + id + '&level=' + level + '&state=1',
			onStateChange: AjaxRequest.displayBox('Loading data …'),

			onComplete: function(txt, xml)
			{
				var ul = new Element('ul');

				ul.addClass('level_' + level);
				ul.setHTML(txt);

				item = new Element('li');

				item.addClass('parent');
				item.setProperty('id', id);
				item.setStyle('display', 'inline');
				ul.injectInside(item);

				if (mode == 5)
				{
					item.injectAfter($(el).getParent().getParent());
				}
				else
				{
					var folder = false;
					var li = $(el).getParent().getParent();

					while ($type(li) == 'element' && li.getNext())
					{
						li = li.getNext();

						if (li.className == 'tl_folder')
						{
							folder = true;
							break;
						}
					}

					folder ? item.injectBefore(li) : item.injectAfter(li);
				}

				image.src = image.src.replace('folPlus.gif', 'folMinus.gif');
				AjaxRequest.hideBox();
   			}
		}).request();

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
	toggleFileManager: function (el, id, folder, level)
	{
		el.blur();
		var item = $(id);
		var image = $(el).getFirst();
		var icon = $(el).getNext();

		if (item)
		{
			if (item.getStyle('display') != 'inline')
			{
				item.setStyle('display', 'inline');
				image.src = image.src.replace('folPlus.gif', 'folMinus.gif');
				icon.src = icon.src.replace('folderC', 'folderO');

				new Ajax(window.location.href, {data: 'isAjax=1&action=toggleFileManager&id=' + id + '&state=1'}).request();
			}
			else
			{
				item.setStyle('display', 'none');
				image.src = image.src.replace('folMinus.gif', 'folPlus.gif');
				icon.src = icon.src.replace('folderO', 'folderC');

				new Ajax(window.location.href, {data: 'isAjax=1&action=toggleFileManager&id=' + id + '&state=0'}).request();
			}

			return false;
		}

		new Ajax(window.location.href, 
		{
			data: 'isAjax=1&action=loadFileManager&id=' + id + '&level=' + level + '&folder=' + folder + '&state=1',
			onStateChange: AjaxRequest.displayBox('Loading data …'),

			onComplete: function(txt, xml)
			{
				var ul = new Element('ul');

				ul.addClass('level_' + level);
				ul.setHTML(txt);

				item = new Element('li');

				item.addClass('parent');
				item.setProperty('id', id);
				item.setStyle('display', 'inline');

				ul.injectInside(item);
				item.injectAfter($(el).getParent().getParent());

				image.src = image.src.replace('folPlus.gif', 'folMinus.gif');
				icon.src = icon.src.replace('folderC.gif', 'folderO.gif');

				AjaxRequest.hideBox();
   			}
		}).request();

		return false;
	},


	/**
	 * Toggle the page tree (input field)
	 * @param object
	 * @param string
	 * @param string
	 * @param string
	 * @param integer
	 * @return boolean
	 */
	togglePagetree: function (el, id, field, name, level)
	{
		el.blur();
		var item = $(id);
		var image = $(el).getFirst();

		if (item)
		{
			if (item.getStyle('display') != 'inline')
			{
				item.setStyle('display', 'inline');
				image.src = image.src.replace('folPlus.gif', 'folMinus.gif');
				new Ajax(window.location.href, {data: 'isAjax=1&action=togglePagetree&id=' + id + '&state=1'}).request();
			}
			else
			{
				item.setStyle('display', 'none');
				image.src = image.src.replace('folMinus.gif', 'folPlus.gif');
				new Ajax(window.location.href, {data: 'isAjax=1&action=togglePagetree&id=' + id + '&state=0'}).request();
			}

			return false;
		}

		new Ajax(window.location.href, 
		{
			data: 'isAjax=1&action=loadPagetree&id=' + id + '&level=' + level + '&field=' + field + '&name=' + name + '&state=1',
			onStateChange: AjaxRequest.displayBox('Loading data …'),

			onComplete: function(txt, xml)
			{
				var ul = new Element('ul');

				ul.addClass('level_' + level);
				ul.setHTML(txt);

				item = new Element('li');

				item.addClass('parent');
				item.setProperty('id', id);
				item.setStyle('display', 'inline');

				ul.injectInside(item);
				item.injectAfter($(el).getParent().getParent());

				image.src = image.src.replace('folPlus.gif', 'folMinus.gif');
				AjaxRequest.hideBox();
   			}
		}).request();

		return false;
	},


	/**
	 * Toggle the file tree (input field)
	 * @param object
	 * @param string
	 * @param string
	 * @param string
	 * @param string
	 * @param integer
	 * @return boolean
	 */
	toggleFiletree: function (el, id, folder, field, name, level)
	{
		el.blur();
		var item = $(id);
		var image = $(el).getFirst();

		if (item)
		{
			if (item.getStyle('display') != 'inline')
			{
				item.setStyle('display', 'inline');
				image.src = image.src.replace('folPlus.gif', 'folMinus.gif');
				new Ajax(window.location.href, {data: 'isAjax=1&action=toggleFiletree&id=' + id + '&state=1'}).request();
			}
			else
			{
				item.setStyle('display', 'none');
				image.src = image.src.replace('folMinus.gif', 'folPlus.gif');
				new Ajax(window.location.href, {data: 'isAjax=1&action=toggleFiletree&id=' + id + '&state=0'}).request();
			}

			return false;
		}

		new Ajax(window.location.href, 
		{
			data: 'isAjax=1&action=loadFiletree&id=' + id + '&level=' + level + '&folder=' + folder + '&field=' + field + '&name=' + name + '&state=1',
			onStateChange: AjaxRequest.displayBox('Loading data …'),

			onComplete: function(txt, xml)
			{
				var ul = new Element('ul');

				ul.addClass('level_' + level);
				ul.setHTML(txt);

				item = new Element('li');

				item.addClass('parent');
				item.setProperty('id', id);
				item.setStyle('display', 'inline');

				ul.injectInside(item);
				item.injectAfter($(el).getParent().getParent());

				image.src = image.src.replace('folPlus.gif', 'folMinus.gif');
				AjaxRequest.hideBox();
   			}
		}).request();

		return false;
	},


	/**
	 * Toggle subpalettes (edit mode)
	 * @param object
	 * @param string
	 * @param string
	 */
	toggleSubpalette: function (el, id, field)
	{
		el.blur();
		var item = $(id);

		if (item)
		{
			if (!el.value)
			{
				el.value = 1;
				el.checked = 'checked';
				item.setStyle('display', 'block');
				new Ajax(window.location.href, {data: 'isAjax=1&action=toggleSubpalette&id=' + id + '&field=' + field + '&state=1'}).request();
			}
			else
			{
				el.value = '';
				el.checked = '';
				item.setStyle('display', 'none');
				new Ajax(window.location.href, {data: 'isAjax=1&action=toggleSubpalette&id=' + id + '&field=' + field + '&state=0'}).request();
			}

			return;
		}

		new Ajax(window.location.href, 
		{
			data: 'isAjax=1&action=toggleSubpalette&id=' + id + '&field=' + field + '&load=1&state=1',
			onStateChange: AjaxRequest.displayBox('Loading data …'),

			onComplete: function(txt, xml)
			{
				item = new Element('div');
				item.setProperty('id', id);
				item.setHTML(txt);

				var folder = false;
				var div = $(el).getParent();

				while ($type(div) == 'element' && div.getNext())
				{
					div = div.getNext();

					if (div.nodeName.toLowerCase() == 'h3' || div.nodeName.toLowerCase() == 'div')
					{
						folder = true;
						break;
					}
				}

				folder ? item.injectBefore(div) : item.injectAfter(div);

				el.value = 1;
				el.checked = 'checked';
				item.setStyle('display', 'block');

				AjaxRequest.hideBox();
				Backend.hideTreeBody();
   			}
		}).request();
	},


	/**
	 * Toggle the visibility of content elements
	 * @param object
	 * @param string
	 * @return boolean
	 */
	toggleVisibility: function(el, id)
	{
		el.blur();
		var image = $(el).getFirst();

		if (image.src.indexOf('invisible') != -1)
		{
			image.src = image.src.replace('invisible.gif', 'visible.gif');
			new Ajax(window.location.href, {data: 'isAjax=1&action=toggleVisibility&id=' + id + '&state=1'}).request();
		}
		else
		{
			image.src = image.src.replace('visible.gif', 'invisible.gif');
			new Ajax(window.location.href, {data: 'isAjax=1&action=toggleVisibility&id=' + id + '&state=0'}).request();
		}

		return false;
	},


	/**
	 * Store the live update ID
	 * @param string
	 * @return boolean
	 */
	liveUpdate: function(el, id)
	{
		var uid = $(id);

		if (!uid)
		{
			return;
		}

		new Ajax(window.location.href,
		{
			data: 'isAjax=1&action=liveUpdate&id=' + uid.value,
			onStateChange: $('lu_message').innerHTML = '<p class="tl_info">Connecting to live update server</p>',

			onComplete: function(txt, xml)
			{
				if (txt)
				{
					$('lu_message').innerHTML = txt;
				}
				else
				{
					$(el).submit();
				}
			}
		}).request();
	},


	/**
	 * Toggle a group of a multi-checkbox field
	 * @param object
	 * @param string
	 */
	toggleCheckboxGroup: function(el, id)
	{
		el.blur();

		var item = $(id);
		var image = $(el).getFirst();

		if (item)
		{
			if (item.getStyle('display') != 'block')
			{
				item.setStyle('display', 'block');
				image.src = image.src.replace('folPlus.gif', 'folMinus.gif');
				new Ajax(window.location.href, {data: 'isAjax=1&action=toggleCheckboxGroup&id=' + id + '&state=1'}).request();
			}
			else
			{
				item.setStyle('display', 'none');
				image.src = image.src.replace('folMinus.gif', 'folPlus.gif');
				new Ajax(window.location.href, {data: 'isAjax=1&action=toggleCheckboxGroup&id=' + id + '&state=0'}).request();
			}

			return true;
		}

		return false;
	},


	/**
	 * Display a "loading data" message
	 * @param string
	 */
	displayBox: function(message)
	{
		var box = $('tl_ajaxBox');
		var overlay = $('tl_ajaxOverlay');

		if (!overlay)
		{
			overlay = new Element('div').setProperty('id', 'tl_ajaxOverlay').injectInside($(document.body));
		}

		if (!box)
		{
			box = new Element('div').setProperty('id', 'tl_ajaxBox').injectInside($(document.body));
		}

		var scroll = window.getScrollTop();
		if (window.ie6) { var sel = $$('select'); for (var i=0; i<sel.length; i++) { sel[i].setStyle('visibility', 'hidden'); } }

		overlay.setStyle('display', 'block');
		overlay.setStyle('top', scroll + 'px');

		box.setHTML(message);
		box.setStyle('display', 'block');
		box.setStyle('top', (scroll + 100) + 'px');
	},


	/**
	 * Hide the "loading data" message
	 */
	hideBox: function()
	{
		var box = $('tl_ajaxBox');
		var overlay = $('tl_ajaxOverlay');

		if (overlay)
		{
			overlay.setStyle('display', 'none');
		}

		if (box)
		{
			box.setStyle('display', 'none');
			if (window.ie6) { var sel = $$('select'); for (var i=0; i<sel.length; i++) { sel[i].setStyle('visibility', 'visible'); } }
		}
	}
};


/**
 * Class Backend
 *
 * Provide methods to handle back end tasks.
 * @copyright  Leo Feyer 2006
 * @author     Leo Feyer <leo@typolight.org>
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
	 * Check whether the popup window has been closed
	 */
	checkPopup: function()
	{
		setTimeout("Backend.doCheckPopup()", 10);
	},


	/**
	 * Reload the page if the popup window has been closed
	 */
	doCheckPopup: function()
	{
		if (Backend.popupWindow && Backend.popupWindow.closed)
		{
			location.reload();
		}
	},


	/**
	 * Get the current mouse position
	 * @param object
	 */
	getMousePosition: function(event)
	{
		Backend.xMousePosition = event.client.x;
		Backend.yMousePosition = event.client.y;
	},


	/**
	 * Open a new window (used by file trees and the help wizard)
	 * @param object
	 * @param integer
	 * @param integer
	 */
	openWindow: function(el, width, height)
	{
		el.blur();
		width = window.ie ? (width + 40) : (width + 17);
		height = window.ie ? (height + 30) : (height + 17);

		Backend.popupWindow = window.open(el.href, '', 'width='+width+',height='+height+',modal=yes,left=100,top=50,location=no,menubar=no,resizable=yes,scrollbars=yes,status=no,toolbar=no');
	},


	/**
	 * Get current scroll offset and store it in a cookie
	 */
	getScrollOffset: function()
	{
		document.cookie = "BE_PAGE_OFFSET=" + window.getScrollTop() + "; path=/";
	},


	/**
	 * Automatically submit a form
	 * @param object
	 */
	autoSubmit: function(form)
	{
		Backend.getScrollOffset();

		var formbody = $(form);
		var hidden = new Element('input');

		hidden.setProperty('type', 'hidden');
		hidden.setProperty('name', 'SUBMIT_TYPE');
		hidden.setProperty('value', 'auto');

		hidden.injectInside(formbody);
		formbody.submit();
	},


	/**
	 * Scroll the window to a certain vertical position
	 * @param integer
	 */
	vScrollTo: function(offset)
	{
		window.addEvent('domready', function()
		{
			window.scrollTo(null, parseInt(offset));
		});
	},


	/**
	 * Show all pagetree and filetree nodes
	 * @param object
	 * @param string
	 */
	showTreeBody: function(el, id)
	{
		el.blur();
		var parent = $(id);

		parent.setStyle('display', ($(el).checked ? 'inline' : 'none'));
	},


	/**
	 * Hide all pagetree and filetree nodes
	 */
	hideTreeBody: function()
	{
		var lists = $$('ul');

		for (var i=0; i<lists.length; i++)
		{
			if (lists[i].className.indexOf('mandatory') != -1)
			{
				$('ctrl_' + lists[i].id).checked = 'checked';
				continue;
			}

			if (lists[i].className.indexOf('tl_listing') != -1)
			{
				var parent = lists[i].getFirst().getNext();

				if (parent && parent.className == 'parent')
				{
					parent.setStyle('display', 'none');
				}
			}
		}
	},


	/**
	 * Limit the height of the preview pane
	 */
	limitPreviewHeight: function()
	{
		var size = null;
		var toggler = null;
		var style = '';
		var hgt = 0;

		$$('div.limit_height').each(function(div)
		{
			size = div.getCoordinates();

			if (hgt == 0)
			{
				hgt = div.className.replace(/[^0-9]*/, '').toInt();
			}

			// Return if there is no height value
			if (!$chk(hgt))
			{
				return;
			}

			div.setStyle('height', hgt);
			toggler = new Element('img').addClass('limit_toggler');

			// Disable function if the preview height is below the max-height
			if (size.height < hgt)
			{
				toggler.src = 'system/themes/default/images/expand_.gif';
				toggler.injectAfter(div);

				return;
			}

			toggler.setStyle('cursor', 'pointer');
			toggler.src = 'system/themes/default/images/expand.gif';

			toggler.addEvent('click', function()
			{
				style = this.getPrevious().getStyle('height').toInt();
				this.getPrevious().setStyle('height', ((style > hgt) ? hgt : ''));

				this.src = (this.src.indexOf('expand.gif') != -1) ? 'system/themes/default/images/collapse.gif' : 'system/themes/default/images/expand.gif';
			});

			toggler.injectAfter(div);
		});
	},


	/**
	 * Toggle checkboxes
	 * @param object 
	 * @param string
	 */
	toggleCheckboxes: function(trigger, id)
	{
		var items = $$('input');
		var status = trigger.checked ? 'checked' : '';

		for (var i=0; i<items.length; i++)
		{
			if (items[i].type.toLowerCase() != "checkbox")
			{
				continue;
			}

			if (id && items[i].id.substr(0, id.length) != id)
			{
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
	toggleCheckboxGroup: function(el, id)
	{
		var status = $(el).checked ? 'checked' : '';

		$$('#' + id + ' .tl_checkbox').each(function(checkbox)
		{
			checkbox.checked = status;
		});

		Backend.getScrollOffset();
	},


	/**
	 * Toggle textarea line wrap
	 * @param string
	 */
	toggleWrap: function(id)
	{
		var textarea = $(id);
		var status = (textarea.getProperty('wrap') == 'off') ? 'soft' : 'off';
		textarea.setProperty('wrap', status);

		if (!window.ie)
		{
			var v = textarea.value;
			var n = textarea.clone();
			n.setProperty('wrap', status);
			textarea.replaceWith(n);
			n.value = v;
		}
	},


	/**
	 * Toggle opacity
	 * @param string
	 */
	blink: function()
	{
		var remove = null;

		$$('img.blink').each(function(el)
		{
			if (!$defined(remove))
			{
				remove = el.hasClass('opacity');
			}

			remove ? el.removeClass('opacity') : el.addClass('opacity');
		});
	},


	/**
	 * Open the color picker wizard in a modal window
	 * @param string
	 */
	pickColor: function(id)
	{
		var width = 275;
		var height = 204;

		Backend.currentId = id;
		Backend.cpColor = $(id).value;

		Backend.getScrollOffset();
		window.open($$('base')[0].href + 'typolight/color.php?color=' + Backend.cpColor, '', 'width='+width+',height='+height+',modal=yes,left='+(Backend.xMousePosition ? (Backend.xMousePosition-width-30) : 200)+',top='+(Backend.yMousePosition ? (Backend.yMousePosition-(height/2)+80) : 100)+',location=no,menubar=no,resizable=yes,scrollbars=no,status=no,toolbar=no');
	},


	/**
	 * Open the page picker wizard in a modal window
	 * @param string
	 */
	pickPage: function(id)
	{
		var width = 320;
		var height = 112;

		Backend.currentId = id;
		Backend.ppHref = $(id).value;

		Backend.getScrollOffset();
		window.open($$('base')[0].href + 'typolight/page.php?href=' + Backend.ppHref, '', 'width='+width+',height='+height+',modal=yes,left='+(Backend.xMousePosition ? (Backend.xMousePosition-width-30) : 200)+',top='+(Backend.yMousePosition ? (Backend.yMousePosition-(height/2)+80) : 100)+',location=no,menubar=no,resizable=yes,scrollbars=no,status=no,toolbar=no');
	},


	/**
	 * List wizard
	 * @param object
	 * @param string
	 * @param string
	 */
	listWizard: function(el, command, id)
	{
		var list = $(id);
		var parent = $(el).getParent();
		var items = list.getChildren();

		Backend.getScrollOffset();

		switch (command)
		{
			case 'copy':
				var clone = parent.clone(true).injectBefore(parent);
				clone.getFirst().value = parent.getFirst().value;
				break;

			case 'up':
				parent.getPrevious() ? parent.injectBefore(parent.getPrevious()) : parent.injectInside(list);
				break;

			case 'down':
				parent.getNext() ? parent.injectAfter(parent.getNext()) : parent.injectBefore(list.getFirst());
				break;

			case 'delete':
				(items.length > 1) ? parent.remove() : null;
				break;
		}
	},


	/**
	 * Table wizard
	 * @param object
	 * @param string
	 * @param string
	 */
	tableWizard: function(el, command, id)
	{
		var table = $(id);
		var tbody = table.getFirst();
		var rows = tbody.getChildren();
		var parentTd = $(el).getParent();
		var parentTr = parentTd.getParent();
		var cols = parentTr.getChildren();
		var index = 0;

		for (var i=0; i<cols.length; i++)
		{
			if (cols[i] == parentTd)
			{
				break;
			}

			index++;
		}

		Backend.getScrollOffset();

		switch (command)
		{
			case 'rcopy':
				var tr = new Element('tr');
				var childs = parentTr.getChildren();

				for (var i=0; i<childs.length; i++)
				{
					var next = childs[i].clone(true).injectInside(tr);
					next.getFirst().value = childs[i].getFirst().value;
				}

				tr.injectAfter(parentTr);
				break;

			case 'rup':
				parentTr.getPrevious().getPrevious() ? parentTr.injectBefore(parentTr.getPrevious()) : parentTr.injectInside(tbody);
				break;

			case 'rdown':
				parentTr.getNext() ? parentTr.injectAfter(parentTr.getNext()) : parentTr.injectBefore(tbody.getFirst().getNext());
				break;

			case 'rdelete':
				(rows.length > 2) ? parentTr.remove() : null;
				break;

			case 'ccopy':
				for (var i=0; i<rows.length; i++)
				{
					var current = rows[i].getChildren()[index];
					var next = current.clone(true).injectAfter(current);
					next.getFirst().value = current.getFirst().value;
				}
				break;

			case 'cmovel':
				if (index > 0)
				{
					for (var i=0; i<rows.length; i++)
					{
						var current = rows[i].getChildren()[index];
						current.injectBefore(current.getPrevious());
					}
				}
				else
				{
					for (var i=0; i<rows.length; i++)
					{
						var current = rows[i].getChildren()[index];
						current.injectBefore(rows[i].getLast());
					}
				}
				break;

			case 'cmover':
				if (index < (cols.length - 2))
				{
					for (var i=0; i<rows.length; i++)
					{
						var current = rows[i].getChildren()[index];
						current.injectAfter(current.getNext());
					}
				}
				else
				{
					for (var i=0; i<rows.length; i++)
					{
						var current = rows[i].getChildren()[index];
						current.injectBefore(rows[i].getFirst());
					}
				}
				break;

			case 'cdelete':
				if (cols.length > 2)
				{
					for (var i=0; i<rows.length; i++)
					{
						rows[i].getChildren()[index].remove();
					}
				}
				break;
		}

		rows = tbody.getChildren();

		for (var i=0; i<rows.length; i++)
		{
			var childs = rows[i].getChildren();

			for (var j=0; j<childs.length; j++)
			{
				var first = childs[j].getFirst();

				if (first && first.type == 'textarea')
				{
					first.name = first.name.replace(/\[[0-9]+\][[0-9]+\]/ig, '[' + (i-1) + '][' + j + ']')
				}
			}
		}
	},


	/**
	 * Module wizard
	 * @param object
	 * @param string
	 * @param string
	 */
	moduleWizard: function(el, command, id)
	{
		var table = $(id);
		var tbody = table.getFirst().getNext();
		var parent = $(el).getParent().getParent();
		var rows = tbody.getChildren();

		Backend.getScrollOffset();

		switch (command)
		{
			case 'copy':
				var tr = new Element('tr');
				var childs = parent.getChildren();

				for (var i=0; i<childs.length; i++)
				{
					var next = childs[i].clone(true).injectInside(tr);
					next.getFirst().value = childs[i].getFirst().value;
				}

				tr.injectAfter(parent);
				break;

			case 'up':
				parent.getPrevious() ? parent.injectBefore(parent.getPrevious()) : parent.injectInside(tbody);
				break;

			case 'down':
				parent.getNext() ? parent.injectAfter(parent.getNext()) : parent.injectBefore(tbody.getFirst());
				break;

			case 'delete':
				(rows.length > 1) ? parent.remove() : null;
				break;
		}

		rows = tbody.getChildren();

		for (var i=0; i<rows.length; i++)
		{
			var childs = rows[i].getChildren();

			for (var j=0; j<childs.length; j++)
			{
				var first = childs[j].getFirst();

				if (first.type == 'select-one')
				{
					first.name = first.name.replace(/\[[0-9]+\]/ig, '[' + i + ']');
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
	optionsWizard: function(el, command, id)
	{
		var table = $(id);
		var tbody = table.getFirst().getNext();
		var parent = $(el).getParent().getParent();
		var rows = tbody.getChildren();

		Backend.getScrollOffset();

		switch (command)
		{
			case 'copy':
				var tr = new Element('tr');
				var childs = parent.getChildren();

				for (var i=0; i<childs.length; i++)
				{
					var next = childs[i].clone(true).injectInside(tr);
					next.getFirst().value = childs[i].getFirst().value;

					if (next.getFirst().type == 'checkbox')
					{
						next.getFirst().checked = childs[i].getFirst().checked ? 'checked' : '';
						if (window.ie6) next.innerHTML = next.innerHTML.replace(/CHECKED/ig, 'checked="checked"');
					}
				}

				tr.injectAfter(parent);
				break;

			case 'up':
				parent.getPrevious() ? parent.injectBefore(parent.getPrevious()) : parent.injectInside(tbody);
				break;

			case 'down':
				parent.getNext() ? parent.injectAfter(parent.getNext()) : parent.injectBefore(tbody.getFirst());
				break;

			case 'delete':
				(rows.length > 1) ? parent.remove() : null;
				break;
		}

		rows = tbody.getChildren();
		var fieldnames = new Array('value', 'label', 'default');

		for (var i=0; i<rows.length; i++)
		{
			var childs = rows[i].getChildren();

			for (var j=0; j<childs.length; j++)
			{
				var first = childs[j].getFirst();

				if (first.type == 'text' || first.type == 'checkbox')
				{
					first.name = first.name.replace(/\[[0-9]+\]/ig, '[' + i + ']')
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
	checkboxWizard: function(el, command, id)
	{
		var container = $(id);
		var parent = $(el).getParent();

		Backend.getScrollOffset();

		switch (command)
		{
			case 'up':
				if (!parent.getPrevious() || parent.getPrevious().hasClass('fixed'))
				{
					parent.injectInside(container);
				}
				else
				{
					parent.injectBefore(parent.getPrevious());
				}
				break;

			case 'down':
				if (parent.getNext())
				{
					parent.injectAfter(parent.getNext());
				}
				else
				{
					var fel = container.getFirst();

					if (fel.hasClass('fixed'))
					{
						fel = fel.getNext();
					}

					parent.injectBefore(fel);
				}
				break;

		}
	}
};


/**
 * Initialize class Backend
 */
document.onmousedown = Backend.getMousePosition.bindWithEvent(document);


/**
 * Hide all pagetree and filetree nodes by default
 */
window.addEvent('domready', function()
{
	Backend.hideTreeBody();
	Backend.limitPreviewHeight();
	Backend.blink.periodical(600);

	$$('textarea.monospace').each(function(el)
	{
		Backend.toggleWrap(el);
	});
});


/**
 * Class TinyCallback
 *
 * Provide callback functions for TinyMCE.
 * @copyright  Leo Feyer 2006
 * @author     Leo Feyer <leo@typolight.org>
 * @package    Backend
 */
var TinyCallback =
{

	/**
	 * Replace target="_blank" and close <br> tags
	 * @param string
	 * @param string
	 * @return string
	 */
	cleanXHTML: function(id, html)
	{
		var modify = '';
		var a = html.match(/<a[^>]*>/gi);

		if (a != null)
		{
			for (var i=0; i<a.length; i++)
			{
				modify = a[i].replace(/target="_blank"/gi, 'onclick="window.open(this.href); return false;"');
				html = html.replace(a[i], modify);
			}
		}

		return html.replace(/<br>/, '<br />');
	},


	/**
	 * Close <br> tags and remove whitespace from the beginning of the code
	 * @param string
	 * @param string
	 * @return string
	 */
	cleanHTML: function(id, html)
	{
		html = html.replace(/<br \/>/, '<br>');
		html = html.replace(/^\s*/ig, '');

		return html;
	}
};