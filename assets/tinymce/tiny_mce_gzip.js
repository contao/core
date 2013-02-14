/**
 * tiny_mce_gzip.js
 *
 * Copyright 2010, Moxiecode Systems AB
 * Released under LGPL License.
 *
 * License: http://tinymce.moxiecode.com/license
 * Contributing: http://tinymce.moxiecode.com/contributing
 */
var tinyMCE_GZ = {
	settings : {
		themes : '',
		plugins : '',
		languages : '',
		disk_cache : true,
		page_name : 'tiny_mce_gzip.php',
		debug : false,
		suffix : ''
	},

	init : function(s, cb, sc) {
		var t = this, n, i, nl = document.getElementsByTagName('script');

		for (n in s)
			t.settings[n] = s[n];

		s = t.settings;

		if (window.tinyMCEPreInit) {
			t.baseURL = tinyMCEPreInit.base;
		} else {
			for (i=0; i<nl.length; i++) {
				n = nl[i];

				if (n.src && n.src.indexOf('tiny_mce') != -1)
					t.baseURL = n.src.substring(0, n.src.lastIndexOf('/'));
			}
		}

		if (!t.coreLoaded)
			t.loadScripts(1, s.themes, s.plugins, s.languages, cb, sc);
	},

	loadScripts : function(co, th, pl, la, cb, sc) {
		var t = this, x, w = window, q, c = 0, ti, s = t.settings;

		function get(s) {
			x = 0;

			try {
				x = new ActiveXObject(s);
			} catch (s) {
			}

			return x;
		};

		// Build query string
		q = 'js=true&diskcache=' + (s.disk_cache ? 'true' : 'false') + '&core=' + (co ? 'true' : 'false') + '&suffix=' + escape(s.suffix) + '&themes=' + escape(th) + '&plugins=' + escape(pl) + '&languages=' + escape(la);

		if (co)
			t.coreLoaded = 1;

		// Send request
		x = w.XMLHttpRequest ? new XMLHttpRequest() : get('Msxml2.XMLHTTP') || get('Microsoft.XMLHTTP');
		x.overrideMimeType && x.overrideMimeType('text/javascript');
		x.open('GET', t.baseURL + '/' + s.page_name + '?' + q, !!cb);
//		x.setRequestHeader('Content-Type', 'text/javascript');
		x.send('');

		// Handle asyncronous loading
		if (cb) {
			// Wait for response
			ti = w.setInterval(function() {
				if (x.readyState == 4 || c++ > 10000) {
					w.clearInterval(ti);

					if (c < 10000 && x.status == 200) {
						t.loaded = 1;
						t.eval(x.responseText);
						tinymce.dom.Event.domLoaded = true;
						cb.call(sc || t, x);
					}

					ti = x = null;
				}
			}, 10);
		} else
			t.eval(x.responseText);
	},

	start : function() {
		var t = this, each = tinymce.each, s = t.settings, ln = s.languages.split(',');

		tinymce.suffix = s.suffix;

		function load(u) {
			tinymce.ScriptLoader.markDone(tinyMCE.baseURI.toAbsolute(u));
		};

		// Add core languages
		each(ln, function(c) {
			if (c)
				load('langs/' + c + '.js');
		});

		// Add themes with languages
		each(s.themes.split(','), function(n) {
			if (n) {
				load('themes/' + n + '/editor_template' + s.suffix + '.js');

				each (ln, function(c) {
					if (c)
						load('themes/' + n + '/langs/' + c + '.js');
				});
			}
		});

		// Add plugins with languages
		each(s.plugins.split(','), function(n) {
			if (n) {
				load('plugins/' + n + '/editor_plugin' + s.suffix + '.js');

				each(ln, function(c) {
					if (c)
						load('plugins/' + n + '/langs/' + c + '.js');
				});
			}
		});
	},

	end : function() {
	},

	eval : function(co) {
		var se = document.createElement('script');

		// Create script
		se.type = 'text/javascript';
		se.text = co;

		// Add it to evaluate it and remove it
		(document.getElementsByTagName('head')[0] || document.documentElement).appendChild(se);
		se.parentNode.removeChild(se);
	}
};


/**
 * Allow to run multiple TinyMCE instances with different
 * configurations on the same page 
 *
 * @copyright Andreas Schempp, 2013
 */
if (Object.create) {
	var tinyMCE_GZ_shim = tinyMCE_GZ_shim || (function() {
		"use strict";

		var tinyMCE_GZ = window.tinyMCE_GZ,
			tinyMCE = null,
			initialized = false,
			config_gz = [],
			config_tiny = {};

		var create_shim = function(t, s) {
			var shim = Object.create(t),
				k;

			for (k in s) {
				if (s.hasOwnProperty(k)) {
					shim[k] = s[k];
				}
			}

			return shim;
		}

		var array_unique = function(arr) {
			var unique = [],
				i, total;

			arr = arr.sort();

			for (i=0, total=arr.length; i<total; i++) {
				if (arr[i + 1] != arr[i]) {
					unique.push(arr[i]);
				}
			}

			return unique;
		}

		var tinyMCE_GZ_shim = {
			init: function(s) {
				config_gz.push(s);
			}
		}

		var tinyMCE_shim = {
			init: function(s) {
				var elements = s.elements.split(','),
					i, total;

				for (i=0, total=elements.length; i<total; i++) {
					config_tiny[elements[i]] = s;
				}
			},
			execCommand: function(c, u, v) {
				initialize();

				if (tinyMCE && typeof config_tiny[v] != 'undefined') {
					tinyMCE.init(config_tiny[v]);
					tinyMCE.execCommand(c, u, v);
				}
			}
		}

		var initialize = function() {
			if (initialized) return;

			var settings = {plugins:[], themes:[], languages:[]},
				i, s, k, total;

			for (i=0, total=config_gz.length;i<total; i++) {
				s = config_gz[i];

				for (k in s) {
					if (k == 'plugins' || k == 'themes' || k == 'languages') {
						[].push.apply(settings[k], s[k].split(','));
					}
					else if (s.hasOwnProperty(k)) {
						settings[k] = s[k];
					}
				}
			}

			settings.plugins = array_unique(settings.plugins).join(',');
			settings.themes = array_unique(settings.themes).join(',');
			settings.languages = array_unique(settings.languages).join(',');

			// load tinyMCE
			tinyMCE_GZ.init(settings);

			tinyMCE = window.tinyMCE;
			tinyMCE_shim = create_shim(tinyMCE, tinyMCE_shim);
			window.tinyMCE = tinyMCE_shim;

			initialized = true;
		}

		window.tinyMCE = tinyMCE_shim;
		tinyMCE_GZ_shim = create_shim(tinyMCE_GZ, tinyMCE_GZ_shim);

		return tinyMCE_GZ_shim;
	})();

	window.tinyMCE_GZ = tinyMCE_GZ_shim;
}
