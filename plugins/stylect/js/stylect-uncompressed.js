/**
 * Contao Open Source CMS
 * Copyright (C) 2005-2011 Leo Feyer
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
 * @copyright  Leo Feyer 2005-2011
 * @author     Leo Feyer <http://www.contao.org>
 * @package    Tablesort
 * @license    LGPL
 * @filesource
 */


/**
 * Find all select menus and style them
 */
window.addEvent('domready', function() {
	$$('select').each(function(el) {
		// Handled by chosen
		if (el.getStyle('display') == 'none') return;

		// Get the selected option label
		if ((active = el.getElement('option[selected]')) != null) {
			var label = active.get('html');
		} else {
			var label = el.getElement('option').get('html');
		}
 
		// Create the div element
		var div = new Element('div', {
			'id': el.get('id') + '_styled',
			'class': 'styled_select',
			'html': '<span>' + label + '</span><b><i></i></b>',
			'styles': {
				'width': el.getComputedSize().totalWidth - 8
			}
		}).inject(el, 'before');

		// Fix right-aligned elements
		if (el.hasClass('right-aligned')) {
			div.setStyle('left', el.getPosition().x);
		}

		// Mark active elements
		if (el.hasClass('active')) {
			div.addClass('active');
		}

		// Update the div onchange
		el.addEvent('change', function() {
			var option = el.getElement('option[value="' + el.value + '"]');
			div.getElement('span').set('html', option.get('html'));
		}).addEvent('keydown', function(event) {
			if (event.key == 'up' || event.key == 'down') {
				setTimeout(function() {	el.fireEvent('change'); }, 100);
			}
		}).addEvent('focus', function() {
			div.addClass('focused');
		}).addEvent('blur', function() {
			div.removeClass('focused');
		}).setStyle('opacity', 0);

		// Browser-specific adjustments
		if (Browser.Engine.webkit || Browser.Engine.trident) {
			el.setStyle('margin-bottom', '4px');
			div.setStyle('width', div.getStyle('width').toInt()-4);
		}
	});
});