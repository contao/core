/* Contao Open Source CMS, (c) 2005-2013 Leo Feyer, LGPL license */
var Theme = {
	hoverRow: function(el, state) {
		var items = $(el).getChildren();
		for (var i=0; i<items.length; i++) {
			if (items[i].nodeName.toLowerCase() == 'td') {
				items[i].setStyle('background-color', (state ? '#ebfdd7' : ''));
			}
		}
	},
	hoverDiv: function(el, state) {
		if (!state) {
			el.removeAttribute('data-visited');
		}
		$(el).setStyle('background-color', (state ? '#ebfdd7' : ''));
	},
	toggleSelect: function(el) {
		var input = $(el).getElement('input');
		if (input) {
			if (input.checked) {
				input.checked = '';
				$(el).setStyle('background-color', '');
			} else {
				input.checked = 'checked';
				$(el).setStyle('background-color', '#ebfdd7');
			}
		}
	},
	fixLabelLastChild: function() {
		if (Browser.ie7 || Browser.ie8) {
			$$('.tl_checkbox_container label:last-child').each(function(el) {
				el.setStyle('margin-bottom', 0);
			});
		}
	}
};

// Initialize
window.addEvent('domready', function() {
	Theme.fixLabelLastChild(); // see #4017

	// Do not propagate the click events in the page/file selector
	$$('.picker_selector').each(function(ul) {
		ul.getElements('a').each(function(el) {
			el.addEvent('click', function(e) {
				e.stopPropagation();
			});
		});
		ul.getElements('input[type="checkbox"]').each(function(el) {
			el.addEvent('click', function(e) {
				e.stopPropagation();
			});
		});
	});

	// [Alt] + click or touch twice to edit
	$$('.click2edit').each(function(el) {
		if (Browser.Features.Touch) {
			el.addEvent('click', function(e) {
				if (!el.getAttribute('data-visited')) {
					el.setAttribute('data-visited', 1);
				} else {
					el.getElements('a').each(function(a) {
						if (a.href.indexOf('act=edit') != -1) {
							document.location.href = a.href;
							return;
						}
					});
					el.removeAttribute('data-visited');
				}
			});
		} else {
			el.addEvent('click', function(e) {
				if (!e.alt) return;
				el.getElements('a').each(function(a) {
					if (a.href.indexOf('act=edit') != -1) {
						document.location.href = a.href;
						return;
					}
				});
			});
		}
	});
});