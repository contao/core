/**
 * Contao Open Source CMS
 *
 * Copyright (c) 2005-2013 Leo Feyer
 *
 * @package Stylect
 * @link    https://contao.org
 * @license http://www.gnu.org/licenses/lgpl-3.0.html LGPL
 */


/**
 * Class Stylect
 *
 * Replace select menus with a nicer, JavaScript based solution.
 * @copyright  Leo Feyer 2005-2013
 * @author     Leo Feyer <http://contao.org>
 * @author     Joe Ray Gregory <https://github.com/may17>
 */
var Stylect =
{
	/**
	 * Check for WebKit
	 */
	isWebkit: (Browser.chrome || Browser.safari || navigator.userAgent.match(/(?:webkit|khtml)/i)),

	/**
	 * Create the div template
	 */
	template: new Element('div', {
		'class': 'styled_select',
		'html': '<span></span><b><i></i></b>'
	}),

	/**
	 * Change event
	 */
	change: function(div, el) {
		div.getElement('span').set('text', el.getElement('option[value=' + el.value + ']').get('text'));
	},

	/**
	 * Keydown event
	 */
	keydown: function(div, el) {
		setTimeout(function() { Stylect.change(div, el); }, 100);
	},

	/**
	 * Focus event
	 */
	focus: function(div) {
		div.addClass('focused');
	},

	/**
	 * Blur event
	 */
	blur: function(div) {
		div.removeClass('focused');
	},

	/**
	 * Find all select menus and try to convert them
	 */
	convertSelects: function() {

		// Not supported in IE < 9
		if (Browser.ie6 || Browser.ie7 || Browser.ie8) return;

		$$('select').each(function(el) {

			// Multiple select
			if (el.get('multiple')) return;

			// Handled by chosen
			if (el.hasClass('tl_chosen')) return;

			// Clone the template
			var div = Stylect.template.clone(),
				cls = el.get('class');

			// Hide the original select menu
			el.setStyle('opacity', 0);

			// Apply an extra bottom margin in WebKit
			if (Stylect.isWebkit) {
				el.setStyle('margin-bottom', '4px');
			}

			// Update the div onchange
			el.addEvents({
				'change': function() {
					Stylect.change(div, el)
				},
				'keydown': function() {
					Stylect.keydown(div, el)
				},
				'focus': function() {
					Stylect.focus(div)
				},
				'blur': function() {
					Stylect.blur(div)
				}
			});

			// Mark disabled elements
			if (el.disabled) {
				div.addClass('disabled');
			}

			// Mark active elements
			if (el.hasClass('active')) {
				div.addClass('active');
			}

			// Add the CSS class and inject
			div.addClass(cls).inject(el, 'before');

			// Activate
			Stylect.change(div, el);
		});
	}
};

window.addEvent('domready', function() {
	Stylect.convertSelects();
});
window.addEvent('ajax_change', function() {
	Stylect.convertSelects();
});
