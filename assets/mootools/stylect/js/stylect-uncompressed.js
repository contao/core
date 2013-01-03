/**
 * Contao Open Source CMS
 * 
 * Copyright (C) 2005-2013 Leo Feyer
 * 
 * @package Stylect
 * @link    https://contao.org
 * @license http://www.gnu.org/licenses/lgpl-3.0.html LGPL
 */



/**
 * Find all select menus and style them
 */
var Stylect =
{
	convertSelects: function() {
		$$('select').each(function(el) {
			// Not supported in IE < 9
			if (Browser.ie6 || Browser.ie7 || Browser.ie8) return;

			// Multiple select
			if (el.get('multiple')) return;

			// Handled by chosen
			if (el.hasClass('tl_chosen')) return;

			// Get the selected option label
			if ((active = el.getElement('option[value="' + el.value + '"]')) != null) {
				var label = active.get('html');
			} else {
				var label = el.getElement('option').get('html');
			}

			// Try to calculate the width of the select menu (getComputedSize() does not seem to work in Webkit)
			var tw = el.getComputedSize().totalWidth || (el.getStyle('width').toInt() + el.getStyle('border-left-width').toInt() + el.getStyle('border-right-width').toInt());

			// Create the div element
			var div = new Element('div', {
				'class': 'styled_select',
				'html': '<span>' + label + '</span><b><i></i></b>',
				'styles': {
					'width': tw - ((Browser.safari || Browser.chrome) ? 6 : 8)
				}
			}).inject(el, 'before');

			// Mark disabled elements
			if (el.disabled) {
				div.addClass('disabled');
			}

			// Fix right-aligned elements (e.g. Safari and Opera)
			if (div.getPosition().x != el.getPosition().x) {
				div.position({ relativeTo:el, ignoreMargins:true });
				if (Browser.safari) {
					div.setStyle('top', (div.getStyle('top') == '22px') ? '24px' : 0);
				} else if (Browser.opera && div.getStyle('top') == '23px') {
					div.setStyle('top', '24px'); // see #4343
				}
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
				setTimeout(function() {	el.fireEvent('change'); }, 100);
			}).addEvent('focus', function() {
				div.addClass('focused');
			}).addEvent('blur', function() {
				div.removeClass('focused');
			}).setStyle('opacity', 0);

			Browser.webkit = (Browser.chrome || Browser.safari || navigator.userAgent.match(/(?:webkit|khtml)/i));

			// Browser-specific adjustments
			if (Browser.webkit) {
				el.setStyle('margin-bottom', '4px');
			}
			if (Browser.webkit || Browser.ie) {
				div.setStyle('width', div.getStyle('width').toInt()-4);
			}
		});
	}
};

window.addEvent('domready', function() {
	Stylect.convertSelects();
});
window.addEvent('ajax_change', function() {
	Stylect.convertSelects();
});
